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

	protected $usersRepository;
	protected $linkedAccount;
	protected $agent;

	public function __construct(
		Request $request,
		UsersRepository $usersRepository 
		) {
		$this->request = $request;
		$this->usersRepository = $usersRepository;

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
		$auth = $this->usersRepository->tokenizer($this->request, $user);
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
					'email'	=> 'required',
					'password'	=> 'required',
				],
				[
					'email.required' => 'Email ' . HelperProvider::getMessageInfo('required'),
					'password.required' => 'password ' . HelperProvider::getMessageInfo('required'),
				]
			);
			if ($validator->fails()) {
				$message = $validator->messages()->first();
				return H_apiResponse(null, $message, 400);
				die();
			}

			// Find the user by username
			$username = $this->request->input('email');
			$password = $this->request->input('password');
			$user = Users::where('email', $username)->first();

			if (!$user) {
				$msg = HelperProvider::getMessageInfo('login_email_not_exist');
				return H_apiResponse(null, $msg, 400);
				exit();
			}

			if (!H_passwordChecker($password, $user->password)) {
				$msg = HelperProvider::getMessageInfo('login_password_wrong');
				return H_apiResponse(null, $msg, 400);
				exit();
			}
             
			//generate the token
			$auth = $this->usersRepository->tokenizer($this->request, $user);
			$user->remember_token = $auth['token'];
			$user->save();
			$user->token = $auth['token'];
			
			$msg = HelperProvider::getMessageInfo('login_success');

			return H_apiResponse($user, $msg);
 
		} catch(Exception $e) {
			return H_apiResponse(null, $e, 400);
		}
	}

	public function logout() {
		$request = $this->request;
		$user_id = H_JWT_getUserId($request);
		$token = H_getHeaderToken($request);
		if ($user_id != null) {
			HelperProvider::deleteTokenUser($user_id, $token);
			$msg = 'Logout has successfully.';
		} else $msg = 'Token not provide';
 
		return H_apiResponse(null, $msg, 200); 
	}

}