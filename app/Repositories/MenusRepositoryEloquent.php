<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MenusRepository;
use App\Models\Menus;
use App\Validators\MenusValidator;
use Exception;

use App\Providers\HelperProvider;
use App\Traits\StandardRepo;

class MenusRepositoryEloquent extends BaseRepository implements MenusRepository
{
    use StandardRepo;

    public function __construct(
        Application $app
	){
		parent::__construct($app);
    }

    /**
     * Specify Model class name
     * @return array
     */
    public function model() {
        return Menus::class;
    }

    /**
     * @return object
     * 
     * params information
     * @raw_request : array (with Request model) 
     * @id : integer
     * @customRequest : array --assoc array type, to replace value default from request
     */
    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->parent_id = $request['parent_id']; 
            $data->menu_item_id = $request['menu_item_id']; 
            $data->master_menu_id = $request['master_menu_id']; 

            
            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function getMenu($raw_request, $id) {
        try {
            $payload = $raw_request->all();
            $data = $this->model;
            $data = $data->with(['Detail']);
            $data = $data->where('master_menu_id', $id);
            $data = $data->where('parent_id', null);
            $data = $data->get();

            $fix = [];
            foreach ($data as $key => $menu) {
                $obj = $menu;
                $obj->sub = $this->getSubMenu($menu->id);
                $fix[] = $obj;
            }

            return $fix;

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    function getSubMenu($id) {
        try {
            $data = $this->model;
            $data = $data->where('parent_id', $id);
            $data = $data->with(['Detail']);
            $data = $data->get();

            $fix = [];
            foreach ($data as $key => $menu) {
                $haveSub = $this->model->where('parent_id', $menu->id)->count();
                $obj = $menu;
                $obj->sub = [];
                if ($haveSub > 0) $obj->sub = $this->getSubMenu($menu->id);
                $fix[] = $obj;
            }

            return $fix;

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}
        