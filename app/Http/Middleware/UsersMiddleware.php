<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Models\Users;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Providers\HelperProvider;

class UsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
		try {
			
			$token = $request->header('Authorization');
			$token = H_cleanToken($token);

			if(!$token || $token == 'null') {
				// Unauthorized response if token not there
				$msg = 'Token not provided!';
				return H_apiResponse(null, $msg, 401);
				exit(); 
			}

			try {

				$one_day = 60 * 60 * 24;
				JWT::$leeway = $one_day * env('SESSION_EXPIRED_DAY', 1); // $leeway in day
				$credentials = H_JWT_decode($token);
				
			} catch(ExpiredException $e) {
				throw new Exception('Token has expired');
			} catch(Exception $e) {
				$msg =  "Token Error, INFO : " .$e->getMessage();
				throw new Exception($msg);
			}

			// checking session
			$auth = true;
			$id = H_JWT_getUserId($request);
			if ($id == null) $auth = false;
			else {

				$user = Users::find($id);
				if ($user) {
					if ($token != $user->remember_token) $auth = false;
				} else $auth = false;

			}
			if (!$auth) throw new Exception('Token Expired!');

			return $next($request);

		} catch (Exception $e) {
			return H_apiResponse(null, $e->getMessage(), 401);
		}
    }
}