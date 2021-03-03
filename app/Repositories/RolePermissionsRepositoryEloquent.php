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

    public function __construct(
        Application $app
	){
		parent::__construct($app);
    }


    public function model() {
        return RolePermissions::class;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->permission_id = $request['permission_id']; 
            $data->role_id = $request['role_id']; 

            
            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function

}
