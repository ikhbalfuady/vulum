<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UsersRepository;
use App\Models\Users;
use App\Validators\UsersValidator;
use Exception;

use App\Providers\HelperProvider;
use App\Traits\StandardRepo;

class UsersRepositoryEloquent extends BaseRepository implements UsersRepository
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

    /**
     * Specify Model class name
     * @return array
     */
    public function model() {
        return Users::class;
    }

    public function initModel($id = null) {
        $model = new Users;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->name = $request['name']; 
            $data->username = $request['username']; 
            $data->email = $request['email']; 
            $data->picture = H_handleRequest($request, 'picture'); 
            $data->role_id = H_handleRequest($request, 'role_id'); 
            $data->menu_id = H_handleRequest($request, 'menu_id'); 
            $data->department_id = H_handleRequest($request, 'department_id'); 
            $data->active = H_handleRequest($request, 'active', 1); 

            if ($id) $data->updated_by = H_handleRequest($request, 'updated_by', H_JWT_getUserId($raw_request));
            else $data->created_by = H_handleRequest($request, 'created_by', H_JWT_getUserId($raw_request));

            if ($id) {
                if (!empty($request['password'])) {
                    $data->password = H_passwordMaker($request['password']);   
                }
            } else $data->password = H_passwordMaker($request['password']);  

            $data->save();
            $this->log->store([
                'description' => $id ? 'Update user' : 'Create user',
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

    public function info($raw_request, $id) {
        $payload = $raw_request->all();
        $data = $this->findAll($raw_request, true);
        $data = $data->with(['Role']);
        $data = $data->where($this->model->getKeyName(), $id)->first();
        return !empty($data) ? $data : null;
    }

    public function rolePermissions($raw_request, $id) {
        try {
            $payload = $raw_request->all();
            $data = $this->findAll($raw_request, true);
            $data = $data->with([
                'Role',
                'Roles'
            ]);
            $data = $data->where($this->model->getKeyName(), $id)->first();
            // dd(json_decode($data->roles));
            $send = [];
            if ($data) {
                if (count($data->roles) != 0) {
                    foreach ($data->roles as $key => $role) {
                        if ($role->permissions) {
                            $obj = [];
                            $obj['name'] = $role->permissions->name;
                            $obj['slug'] = $role->permissions->slug;
                            $send[] = $obj;
                        }
                    }
                }
            } else return null;
            return $send;
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function changePassword($raw_request, $id = null, $customRequest = null) {
        try {
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);
            if ($id) {
                if (!empty($request['password'])) {
                    $data->password = H_passwordMaker($request['password']);   
                }
            } else $data->password = H_passwordMaker($request['password']);  

            if ($id) $data->updated_by = $request['updated_by'] ?? H_JWT_getUserId($raw_request);
            else $data->created_by = $request['created_by'] ?? H_JWT_getUserId($raw_request);
            $data->save();
            $this->log->store([
                'description' => 'Change password user',
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

    public function updateProfile($raw_request, $id = null, $customRequest = null) {
        try {
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);
            $data->name = $request['name']; 
            $data->username = $request['username'];
            $data->email = $request['email'];

            if ($id) $data->updated_by = $request['updated_by'] ?? H_JWT_getUserId($raw_request);
            else $data->created_by = $request['created_by'] ?? H_JWT_getUserId($raw_request);

            $data->save();
            $this->log->store([
                'description' => 'Update user',
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
    

}
        