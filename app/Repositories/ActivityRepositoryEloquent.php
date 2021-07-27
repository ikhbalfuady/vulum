<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\ActivityRepository;
use Spatie\Activitylog\Models\Activity;
use Exception;
use App\Models\Users;
use App\Models\UserNotifications;
use App\Traits\StandardRepo;

class ActivityRepositoryEloquent extends BaseRepository implements ActivityRepository
{
    use StandardRepo;

    public function __construct(
        Application $app
    ){
        parent::__construct($app);
    }


    public function model() {
        return Activity::class;
    }

    /**
     * Model initiate
     * @return object
     */
    public function initModel($id = null) {
        $model = new Activity;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function store($request = []) {
        try {
            $data = $this->initModel();

            $data->log_name = $request['log_name'] ?? 'default';
            $data->description = $request['description'] ?? '';

            if(isset($request['subject'])) {
                $data->subject_id = $request['subject']->getKey();
                $data->subject_type = $request['subject']->getMorphClass();
            }

            if(isset($request['causer'])) {
                $data->causer_id = $request['causer'];
                $data->causer_type = 'App\Models\Users';
            }
            $data->properties = $request['properties'] ?? [];

            $data->save();
            return $data;
        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function make($send, $mode, $properties = null) { // mode : c,u,d,r (r = restore)
        try {

            $type = "Create";
            $causer = $send->created_by;
            $color = "primary";
            if ($mode == 'u') {
                $type = "Update";
                $causer = $send->updated_by;
                $color = "green";
            }
            if ($mode == 'd') {
                $type = "Delete";
                $causer = $send->deleted_by;
                $color = "red";
            }
            if ($mode == 'r') {
                $type = "Restore";
                $causer = $send->updated_by;
                $color = "orange";
            }

            $data = $this->initModel();
            $data->subject_id = $send->getKey();
            $data->subject_type = $send->getMorphClass();

            $moduleName = H_getModuleName($send);
            $causer = Users::find($causer);

            $data->log_name = $send->log_name ?? 'default';

            $name = '';
            if (isset($send->title)) $name = '"'.$send->title.'"';
            if (isset($send->name)) $name = '"'.$send->name.'"';

            $description = $send->log_description ?? "$type $moduleName : ".$name." by ".$causer->name;
            $data->description = $description;

            $data->causer_id = $causer->id;
            $data->causer_type = 'App\Models\Users';

            // using log definer when log type set
            if (isset($send->log_type) && $send->log_type != null) $data = $this->logDefiner($data, $send, $send->log_type);

            $data->properties = $properties ?  $properties : ["color" => $color];

            $data->save();
            return $data;
        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function logDefiner($model, $data, $type) {
        $causer         = Users::find($model->causer_id);
        $name           = $type['moduleName'];
        
        $notif_template = $this->getTemplate($type['template']);
        
        $title          = str_replace($type['replace']['title']['from'], $type['replace']['title']['to'], $notif_template['title']);
        $description    = str_replace($type['replace']['desc']['from'], $type['replace']['desc']['to'], $notif_template['description']);

        $pathParams     = $type['pathParams'];

        if (isset($type['for']['role'])) {
            $this->createNotifByRole($type['for']['role'] ,$data, $title, $description, $pathParams);
        }
        if(isset($type['for']['user'])){
            $this->createNotifByUser($type['for']['user'], $data, $title . ' (user)', $description, $pathParams);
        }
 
        $model->log_name = $name;
        $model->description = $description;
        return $model;
    }

    public static function getTemplate($type, $lang = 'en')
    {
        $path = H_resources_path('comunication-template/' . $lang . '.json');
        $content = json_decode(file_get_contents($path), true);
        return $content[$type];
    }

    public function createNotifByUser($user_id = [], $data, $title, $description, $pathParams = null) {
        $path = null;
        $params = null;

        if ($pathParams) {
            $path = $pathParams['path'];
            $params = $pathParams['params'];;
        }

        $input = [];
        foreach($user_id as $r) {
            $input[] = [
                "user_id" => $r,
                "title" => $title,
                "description" => $description,
                "type" => 'info',
                "link_path" => $path,
                "link_params" => json_encode($params),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $data->causer_id,
            ];
        }

        UserNotifications::insert($input);

    }

    public function createNotifByRole($slugRole, $data, $title, $description, $pathParams = null) {

        $path = null;
        $params = null;

        if ($pathParams) {
            $path = $pathParams['path'];
            $params = $pathParams['params'];;
        }

        $users = Users::select([
                    'users.id',
                    'users.name',
                    'roles.slug',
                ])
                ->join('roles', function ($query) use ($slugRole) {
                    $query->on('users.role_id', 'roles.id');
                    $query = $query->whereIn('slug', $slugRole);
                })->get();

        foreach ($users as $key => $user) {
            $send = [
                "user_id" => $user->id,
                "title" => $title,
                "description" => $description,
                "type" => 'info',
                "link_path" => $path,
                "link_params" => $params,
            ];
            UserNotifications::create($send);
        }
    }

    // add your customize function

}
