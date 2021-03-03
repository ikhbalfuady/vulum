<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RolesRepository;
use App\Models\Roles;
use App\Validators\RolesValidator;
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class RolesRepositoryEloquent extends BaseRepository implements RolesRepository
{
    use StandardRepo;

    public function __construct(
        Application $app
	){
		parent::__construct($app);
    }


    public function model() {
        return Roles::class;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->name = $request['name']; 
            $data->slug = H_handleRequest($request, 'slug'); 

            
            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function

}
