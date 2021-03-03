<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MenuItemsRepository;
use App\Models\MenuItems;
use App\Validators\MenuItemsValidator;
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class MenuItemsRepositoryEloquent extends BaseRepository implements MenuItemsRepository
{
    use StandardRepo;

    public function __construct(
        Application $app
	){
		parent::__construct($app);
    }


    public function model() {
        return MenuItems::class;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->name = $request['name']; 
            $data->slug = $request['slug']; 
            $data->icon = H_handleRequest($request, 'icon'); 
            $data->path = H_handleRequest($request, 'path'); 
            $data->ordering = H_handleRequest($request, 'ordering'); 

            
            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function

}
