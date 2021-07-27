<?php
namespace App\Http\Controllers;
use Validator;
use Exception;
use Carbon\Carbon;
use App\Models\Users;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Repositories\UsersRepository;
use App\Repositories\UserSessionsRepository;
use Jenssegers\Agent\Agent;
use App\Providers\HelperProvider;
use App\Providers\SocialLoginProvider;
use DB;

 
class UsersAuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */

    protected $userSessionsRepository;
    protected $linkedAccount;
    protected $agent;

    public function __construct(
        Request $request,
        UsersRepository $usersRepository,
        UserSessionsRepository $userSessionsRepository
        ) {
        $this->request = $request;
        $this->usersRepository = $usersRepository;
        $this->userSessionsRepository = $userSessionsRepository;

        $this->agent = new Agent();
    }
    /**
     * Create a new token.
     *
     * @param  \App\Users   $user
     * @return string
     */

    public function setSession($user) {
        //generate the token
        $auth = $this->userSessionsRepository->login($this->request, $user);
        return $auth;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate() {
        try {
            $payload = $this->request->all();
            $validator = Validator::make( $this->request->all(),
                [
                    'email'    => 'required',
                    'password'    => 'required',
                ],
                [
                    'email.required' => 'Email is required',
                    'password.required' => 'password is required',
                ]
            );
            if ($validator->fails()) {
                $message = $validator->messages()->first();
                return H_apiResponse(null, $message, 400);
            }

            // Find the user by username
            $username = $this->request->input('email');
            $password = $this->request->input('password');
            $user = Users::whereRaw('email = ? OR username = ? ', [$username, $username])->first();
 
            if (!$user) {
                $msg = "User does'nt exist!";
                return H_apiResponse(null, $msg, 400);
            }

            if (!H_passwordChecker($password, $user->password)) {
                $msg = "Wrong password!";
                return H_apiResponse(null, $msg, 400);
            }
             
            //generate the token
            $auth = $this->setSession($user);
            $user->token = $auth->token;
            return H_apiResponse($user, 'Login successfully');
 
        } catch(Exception $e) {
            return H_apiResponse(null, $e, 400);
        }
    }

    public function logout() {
        $request = $this->request;
        $user_id = H_JWT_getUserId($request);
        $token = H_getHeaderToken($request);
        $data = null;
        if ($user_id != null) {
            $data = $this->userSessionsRepository->logout($token, $user_id);
            $msg = 'Logout has successfully.';
        } else $msg = 'Token not provide';
 
        return H_apiResponse($data, $msg, 200); 
    }

}