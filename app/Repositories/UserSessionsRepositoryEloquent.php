<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserSessionsRepository;
use App\Models\UserSessions;
use App\Validators\UserSessionsValidator;
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class UserSessionsRepositoryEloquent extends BaseRepository implements UserSessionsRepository
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
        return UserSessions::class;
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
            $data->user_id = $request['user_id']; 
            $data->token = $request['token']; 
            $data->ip = H_handleRequest($request, 'ip'); 
            $data->agent = H_handleRequest($request, 'agent'); 
            $data->platform = H_handleRequest($request, 'platform'); 

            
            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    public function logout($token, $user_id) {
        try {

            $data = $this->model->where('user_id', $user_id)->where('token', $token)->first();
            if ($data) return $data->forceDelete();
            else return null;

        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function login($request, $user) {
        try {
            $sessions = $this->tokenizer($request, $user);
            $save = [
                "user_id" => $user->id,
                "token" => $sessions->token,
                "ip" => $sessions->ip,
                "agent" => $sessions->agent,
                "platform" => 'web',
            ];
            $save = $this->store($request, null, $save);
        return $save;
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
        
    }

    public function tokenizer($request, $user) {
        $requiredData = array(
            "id" => $user->id,
            "name" => $user->name,
            "username" => $user->username,
            "email" => $user->email,
        );
        $data = [
            "id" => $user->id,
            "token" => H_JWT_encode($user->id, $requiredData),
            "agent" => $request->header('User-Agent'),
            "ip" => H_getIpClient(),
        ];
        return H_toArrayObject($data);
    }

}
        