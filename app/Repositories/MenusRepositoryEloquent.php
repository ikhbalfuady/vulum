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
        return Menus::class;
    }

    public function initModel($id = null) {
        $model = new Menus;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function findAllRelation($raw_request, $raw = false) {
        $data = $this->getList($raw_request, ['Detail']);
        // $data = $data->with(['Detail']);
        // $data = $data->get();

        return $data;
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
            $data->parent_id = H_handleRequest($request, 'parent_id'); 
            $data->menu_item_id = $request['menu_item_id']; 
            $data->master_menu_id = $request['master_menu_id']; 
            $data->overline = H_handleRequest($request, 'overline'); 
            $data->ordering = H_handleRequest($request, 'ordering'); 

            if ($id) $data->updated_by = H_handleRequest($request, 'updated_by', H_JWT_getUserId($raw_request)); 
            else $data->created_by = H_handleRequest($request, 'created_by', H_JWT_getUserId($raw_request)); 

            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function saveMenu($menu, $details) {
        $no = 1;
        $saved = [];
        foreach ($details as $key => $list) { // insert menu lv 1
            $data = $this->prepareAndStore($menu, $list, null, $no);
            
            if (isset($list['children']) && count($list['children']) != 0) {
                $data->children = $this->saveChildrenMenu($menu, $data->id, $list['children']);
            }
            $no++;
            
            $saved[] = $data;
        }
        return $saved;
    }

    public function saveChildrenMenu($menu, $parent_id = null, $details) {

        $no = 1;
        $saved = [];
        foreach ($details as $key => $list) { // insert menu lv 1
            $data = $this->prepareAndStore($menu, $list, $parent_id, $no);
            if (isset($list['children']) && count($list['children']) != 0) {
                $data->children = $this->saveChildrenMenu($menu, $data->id, $list['children']);
            }
            $no++;
            $saved[] = $data;
        }

        return $saved;
    }

    function prepareAndStore($menu, $list, $parent_id = null, $ordering = null) {
        $listDetail = $list['detail'];
        $data = [
            "id" => $list['id'],
            "parent_id" => $parent_id,
            "menu_item_id" => $listDetail['id'],
            "master_menu_id" => $menu->id,
            "overline" => (isset($list['overline'])) ? $list['overline'] : null,
            "ordering" => $ordering,
        ];
        $save = $this->store(null, $list['id'], $data);
        return json_decode($save);
    }

    public function getMenu($raw_request, $id, $subName = 'sub') {
        try {
            $payload = $raw_request->all();
            $data = $this->model;
            $data = $data->with(['Detail']);
            $data = $data->where('master_menu_id', $id);
            $data = $data->where('parent_id', null);
            $data = $data->orderBy('ordering', 'ASC');
            $data = $data->get();

            $fix = [];
            foreach ($data as $key => $menu) {
                $obj = $menu;
                $obj->{$subName} = $this->getSubMenu($menu->id, $subName);
                $fix[] = $obj;
            }

            return $fix;

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    function getSubMenu($id, $subName = 'sub') {
        try {
            $data = $this->model;
            $data = $data->where('parent_id', $id);
            $data = $data->with(['Detail']);
            $data = $data->orderBy('ordering', 'ASC');
            $data = $data->get();

            $fix = [];
            foreach ($data as $key => $menu) {
                $haveSub = $this->model->where('parent_id', $menu->id)->count();
                $obj = $menu;
                $obj->{$subName} = [];
                if ($haveSub > 0) $obj->{$subName} = $this->getSubMenu($menu->id, $subName);
                $fix[] = $obj;
            }

            return $fix;

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function deleteMultiple($masterMenuId, array $menuId) {
        try {
            return $this->model->where('master_menu_id', $masterMenuId)
                ->whereIn('id', $menuId)
                ->delete();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
        