<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RolePermissionsRepository;
use App\Models\RolePermissions;
use App\Validators\RolePermissionsValidator;
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class RolePermissionsRepositoryEloquent extends BaseRepository implements RolePermissionsRepository
{
    use StandardRepo;

    protected $log;

    public function __construct(
        Application $app,
        ActivityRepository $log
    ){
        parent::__construct($app);

        $this->log = $log;
    }

    public function model() {
        return RolePermissions::class;
    }

    public function initModel($id = null) {
        $model = new RolePermissions;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->permission_id = $request['permission_id']; 
            $data->role_id = $request['role_id']; 

            if ($id) $data->updated_by = $request['updated_by'] ?? H_JWT_getUserId($raw_request);
            else $data->created_by = $request['created_by'] ?? H_JWT_getUserId($raw_request);
    
            $data->save();
            $this->log->store([
                'description' => $id ? 'Update role permission' : 'Create role permission',
                'subject' => $data,
                'causer' => $id ? $data->updated_by : $data->created_by,
                'properties' => [
                    'color' => $id ? 'yellow' : 'green',
                ]
            ]);
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function

    public function saveRolePermissions ($raw_request, $role, $details) {
        $saved = [];
        foreach ($details as $key => $permission) {
            if ($permission['current_allow'] != $permission['allow']) {
                $data = $this->prepareAndStore($raw_request, $role, $permission);
                $saved[] = $data;
            }
        }
        return $saved;
    }

    function prepareAndStore($raw_request, $role, $permission) {
        $data = [
            "id" => $permission['id'],
            "permission_id" => $permission['permission_id'],
            "role_id" => $role['id'],
        ];
        if ($permission['allow']) {
            $check = $this->findAll($raw_request, true);
            $check = $check->where('permission_id', $permission['permission_id']);
            $check = $check->where('role_id', $role['id']);
            $check = $check->onlyTrashed()->first();
            if ($check) {
                $save = $this->restore($raw_request, $check['id']); // jika pernah dibuat namun pernah di disallow
                $save = $check;
            }
            else $save = $this->store($raw_request, $permission['id'], $data); // belum ada sama sekali
        }
        else $save = $this->remove($raw_request, $permission['id']);
        return json_decode($save);
    }

}
