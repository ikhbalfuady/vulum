<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RolesRepository;
use App\Repositories\RolePermissionsRepository;
use App\Models\Roles;
use App\Models\Permissions;
use App\Models\RolePermissions;
use App\Validators\RolesValidator;
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class RolesRepositoryEloquent extends BaseRepository implements RolesRepository
{
    use StandardRepo;

    protected $log;
    protected $rolePermissionsRepository;

    public function __construct(
        Application $app,
        ActivityRepository $log,
        RolePermissionsRepository $rolePermissionsRepository
    ){
        $this->log = $log;
        parent::__construct($app);
        $this->log = $log;
        $this->rolePermissionsRepository = $rolePermissionsRepository;
    }

    public function model() {
        return Roles::class;
    }

    public function initModel($id = null) {
        $model = new Roles;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->code = $request['code']; 
            $data->name = $request['name']; 
            if (H_hasRequest($request, 'slug')) $slug = $request['slug'];
            else $slug = H_makeSlug($data->name);
            $data->slug = $slug;

            if ($id) $data->updated_by = $request['updated_by'] ?? H_JWT_getUserId($raw_request);
            else $data->created_by = $request['created_by'] ?? H_JWT_getUserId($raw_request);
    
            $data->save();
            $this->log->store([
                'description' => $id ? 'Update role' : 'Create role',
                'subject' => $data,
                'causer' => $id ? $data->updated_by : $data->created_by,
                'properties' => [
                    'color' => $id ? 'yellow' : 'green',
                ]
            ]);

            if ($id) { // only for update mode
                if (isset($request['detail']) && count($request['detail']) != 0) {
                    $details = $request['detail'];
                    $data->detail = $this->rolePermissionsRepository->saveRolePermissions($raw_request, $data, $details);
                } else {
                    throw new Exception('Permissions not defined for this role!');
                }
            }

            
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function

    public function getRole($raw_request, $id) {
        try {
            $data = $this->model;
            $data = $data->where($this->model->getKeyName(), $id)->first();
            if (empty($data)) throw new Exception("Data with id $id not found");
            else {
                $permissions = Permissions::whereRaw('deleted_at IS NULL')->get();
                $send = [];
                foreach ($permissions as $key => $value) {
                    $obj = [
                        "id" => null,
                        "permission_id" => $value->id,
                        "role_id" => null,
                        "name" => $value->name,
                        "slug" => $value->slug,
                        "current_allow" => false,
                        "allow" => false,
                    ];
                    $hasRole = RolePermissions::whereRoleId($id)->wherePermissionId($value->id)->first();
                    if ($hasRole) {
                        $obj['id'] = $hasRole->id;
                        $obj['role_id'] = $hasRole->role_id;
                        $obj['current_allow'] = true;
                        $obj['allow'] = true;
                    }
                    $send[] = $obj;
                }
                $data->detail = $send;
            }

            return $data;
        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        }
    }

}
