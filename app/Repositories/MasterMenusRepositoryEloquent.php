<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MasterMenusRepository;
use App\Repositories\MenusRepository;
use App\Models\MasterMenus;
use App\Validators\MasterMenusValidator;
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class MasterMenusRepositoryEloquent extends BaseRepository implements MasterMenusRepository
{
    use StandardRepo;

    protected $menusRepository;

    public function __construct(
        Application $app,
        MenusRepository $menusRepository
    ){
        parent::__construct($app);
        $this->menusRepository = $menusRepository;
    }

    public function model() {
        return MasterMenus::class;
    }

    public function initModel($id = null) {
        $model = new MasterMenus;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function getMenu($raw_request, $id) {
        $data = $this->findAll($raw_request, true);
        $data = $data->where($this->model->getKeyName(), $id)->first();
        if (empty($data)) throw new Exception("Data with id $id not found");
        else {
            $data->detail = [];
            $detail = $this->menusRepository->getMenu($raw_request, $id, 'children');
            if (!empty($detail))  $data->detail = $detail;
            return $data;
        };
    }


    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->name = H_handleRequest($request, 'name', 'menu');

            if ($id) $data->updated_by = H_handleRequest($request, 'updated_by', H_JWT_getUserId($raw_request)); 
            else $data->created_by = H_handleRequest($request, 'created_by', H_JWT_getUserId($raw_request)); 

            $data->save();

            if (isset($request['detail']) && count($request['detail']) != 0) {
                $details = $request['detail'];
                $data->detail = $this->menusRepository->saveMenu($data, $details);
            } else {
                throw new Exception('Please pick one menu items!');
            }
            
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function getListFormater($raw_request) {
        try {
            $payload = $raw_request->all();
            $data = $this->getList($raw_request, ['Users']);

            $source = $data;
            if (isset($payload['table'])) {
                $source = H_toArrayObject($data);
                $source = $source->data;
            }
            
            $fix = [];
            foreach ($source as $key => $menu) {
                $obj = $menu;
                $obj->used = (isset($menu->users)) ? count($menu->users) : 0;
                $fix[] = $obj;
            }

            if (isset($payload['table'])) {
                $data = H_toArrayObject($data);
                $data->data = $fix;
            }else $data = $fix;

            return $data;

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    // add your customize function

}
