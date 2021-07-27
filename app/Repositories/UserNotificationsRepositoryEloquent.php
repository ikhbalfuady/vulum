<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserNotificationsRepository;
use App\Models\UserNotifications;
use App\Validators\UserNotificationsValidator;
use Exception;
use Carbon\Carbon;
use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class UserNotificationsRepositoryEloquent extends BaseRepository implements UserNotificationsRepository
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
        return UserNotifications::class;
    }

    public function initModel($id = null) {
        $model = new UserNotifications;
        if (!empty($id)) $model = $this->model->where($this->model->getKeyName(), $id)->first();
        return $model;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
            $data->user_id = $request['user_id']; 
            $data->is_read = $request['is_read']; 
            $data->title = H_handleRequest($request, 'title'); 
            $data->description = H_handleRequest($request, 'description'); 
            $data->type = $request['type']; 
            $data->link_path = H_handleRequest($request, 'link_path'); 
            $data->link_params = H_handleRequestJson($request, 'link_params');

            if ($id) $data->updated_by = $request['updated_by'] ?? H_JWT_getUserId($raw_request);
            else $data->created_by = $request['created_by'] ?? H_JWT_getUserId($raw_request);
    
            $data->save();
            $this->log->store([
                'description' => $id ? 'Update user notification' : 'Create user notification',
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

    // add your customize function

    public function read($raw_request, $id) {
     
        try {
            $data = $this->findById($raw_request, $id, true);
            $data->is_read = 1;
            $data->save();
            return $data;
            
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    // only unread notif
    public function getByUser($raw_request, $user_id, $relations = null) {
        try {
            $payload = $raw_request->all();
            $data = $this->findAll($raw_request, true);
            $data = $data->whereUserId($user_id);
            $data = $data->whereIsRead(0);
            $data = $data->orderBy('created_at', 'DESC');
            if ($relations) $data = $data->with($relations);
        
            $limit = env('PAGINATION_LIMIT', 5);
            if (H_hasRequest($payload, 'limit') && $payload['limit'] != '0') $limit = $payload['limit'];
            $listMode = false;
            if (isset($payload['table'])) {
                $listMode = true;
                $data = $data->paginate($limit)->withQueryString();
            } else {
                if (H_hasRequest($payload, 'limit') && $payload['limit'] == '0') return $data->get();
                else $data = $data->limit($limit)->get();
            }

            return $this->formater($data, $listMode);

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getAllByUser($raw_request, $user_id, $relations = null) {
        try {
            $payload = $raw_request->all();
            $data = $this->findAll($raw_request, true);
            $data = $data->whereUserId($user_id);
            $data = $data->orderBy('created_at', 'DESC');
            if ($relations) $data = $data->with($relations);
        
            $limit = env('PAGINATION_LIMIT', 5);
            if (H_hasRequest($payload, 'limit') && $payload['limit'] != '0') $limit = $payload['limit'];
            $listMode = false;
            if (isset($payload['table'])) {
                $listMode = true;
                $data = $data->paginate($limit)->withQueryString();
            } else {
                if (H_hasRequest($payload, 'limit') && $payload['limit'] == '0') return $data->get();
                else $data = $data->limit($limit)->get();
            }

            return $this->formater($data, $listMode);

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function formater ($data, $listMode) {
        $realData = $data;
        if ($listMode) {
            $data = H_toArrayObject($data);
            $realData = $data->data;
        }
        $fix = [];
        foreach ($realData as $key => $row) {
            $obj = $row;
            $fix[] = $this->singleFormater($obj);

        }
        if ($listMode) $data->data = $fix;
        else $data = $fix;

        return $data;
    }

    public function singleFormater($row) {
        $obj = $row;
        $created_at = Carbon::parse($row->created_at);
        $obj->created_at = $created_at->timezone('Asia/Jakarta');
        $obj->date = H_formatDate($created_at);
        $obj->time = $created_at->diffForHumans();
        $obj->icon = $this->iconType($obj->type);

        return $obj;
    }

    public function testNotif ($user_id) {
        $params = [
            "id" => 1,
        ];

        $data = [
            "user_id" => $user_id,
            "is_read" => 0,
            "title" => 'Welcome',
            "description" => 'View detail your account',
            "type" => 'info',
            "link_path" => 'view-users',
            "link_params" => $params
        ];

        return $this->store(null, null, $data);
    }

    function iconType($type) {
        if ($type == 'welcome') return 'record_voice_over';
        else return 'info';
    }
}
