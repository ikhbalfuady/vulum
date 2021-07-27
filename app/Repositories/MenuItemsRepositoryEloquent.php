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

    protected $log;

    public function __construct(
        Application $app,
        ActivityRepository $log
    ){
        parent::__construct($app);

        $this->log = $log;
    }

    public function model() {
        return MenuItems::class;
    }

    public function initModel($id = null) {
        $model = new MenuItems;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            $this->checkAvailMenu($request['name'], $id);

            //storing defined property    
            $data->name = $request['name']; 
            if (H_hasRequest($request, 'slug')) $slug = $request['slug'];
            else $slug = H_makeSlug(strtolower($data->name));
            $data->slug = $slug;
            $data->icon = H_handleRequest($request, 'icon'); 
            $data->path = H_handleRequest($request, 'path'); 

            if ($id) $data->updated_by = H_handleRequest($request, 'updated_by', H_JWT_getUserId($raw_request)); 
            else $data->created_by = H_handleRequest($request, 'created_by', H_JWT_getUserId($raw_request)); 
            
            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function checkAvailMenu($name, $id = null) {
        $check = $this->model->whereName($name);
        if ($id) $check = $check->where('id', '!=', $id);
        $check = $check->first();

        if (!empty($check)) {
            throw new Exception('Menu with name "'.$name.'" has taken, please create another name!'); 
        }
    }

    public function create($raw_request) {
        try {
 
            $payload = $raw_request->all();

            if (isset($payload['generate_default']) && $payload['generate_default'] == true) {
                $this->generateDefault($raw_request);
            } else $this->store($raw_request, null, null);

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function generateDefault($raw_request) {
        try {
            $payload = $raw_request->all();
            $parent_data = [
                "name" => $payload['name'],
            ];
            $parent = $this->store(null, null, $parent_data);
            $children = [
                [
                    "name" => $payload['name'] . " List"
                ],
                [
                    "name" => "Add " . $payload['name']
                ]
            ];

            $child = [];
            foreach ($children as $key => $menu) {
                $menu_data = [
                    "name" => $menu['name']
                ];
                $child[] = $this->store(null, null, $menu_data);
            }
            $child[] = $parent;

            return $child;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function

    public function picker($raw_request) {
        try {
            $data = $this->model->get();
            $send = [];
            foreach ($data as $key => $value) {
                $send[] = [
                    "id" => null,
                    "parent_id" => null,
                    "menu_item_id" => null,
                    "master_menu_id" => null,
                    "overline" => null,
                    "ordering" => null,
                    "name" => $value->name,
                    "detail" => $value,
                ];
            }
            return $send;
        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        }
        
    }

    public function generateNewModule($modules = []) {
        try {
            $data = [];
            foreach ($modules as $module) {
                $fixName = H_splitUppercaseWithSpace($module);
                $slug = H_makeSlug($module);

                $data[] = [
                    'name' => $fixName,
                    'slug' => strtolower($slug),
                    'path' => '/'.strtolower($slug),
                ];
            }
            $this->model->insert($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

}
