<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PermissionsRepository;
use App\Models\Permissions;
use App\Validators\PermissionsValidator;
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class PermissionsRepositoryEloquent extends BaseRepository implements PermissionsRepository
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
        return Permissions::class;
    }

    public function initModel($id = null) {
        $model = new Permissions;
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

            if (H_hasRequest($request, 'slug')) $slug = $request['slug'];
            else $slug = H_makeSlug(strtolower($data->name));
            $data->slug = $slug ; 

            if ($id) $data->updated_by = $request['updated_by'] ?? H_JWT_getUserId($raw_request);
            else $data->created_by = $request['created_by'] ?? H_JWT_getUserId($raw_request);
    
            $data->save();
 
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function
    public function generateNewModule($modules = []) {
        try {
            $crud = [ 'Browse', 'Create', 'Read', 'Update', 'Delete', 'Restore' ];
            $data = [];
            foreach ($modules as $module) {
                $fixName = H_splitUppercaseWithSpace($module);
                $slug = H_makeSlug($module);

                foreach ($crud as $r) {
                    $data[] = [
                        'name' => $fixName.' '.$r,
                        'slug' => strtolower($slug.'-'.$r),
                    ];
                }
            }
            $this->model->insert($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }
}
