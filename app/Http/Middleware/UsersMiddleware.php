<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Models\Users;
use App\Models\UserSessions;
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
			}

			try {

				$one_day = 60 * 60 * 24;
				JWT::$leeway = $one_day * env('SESSION_EXPIRED_DAY', 1); // $leeway in day
				// $credentials = H_JWT_decode($token);
				
			} catch(ExpiredException $e) {
				throw new Exception('Token has expired');
			} catch(Exception $e) {
				$msg =  "Token Error, INFO : " .$e->getMessage();
				throw new Exception($msg);
			}

			// checking session
			$id = H_JWT_getUserId($request);
			if ($id == null) throw new Exception('Please login first!');

			if (!$this->findSession($id, $token)) throw new Exception('Session Expired, please login!');

			return $next($request);

		} catch (Exception $e) {
			return H_apiResponse(null, $e->getMessage(), 401);
		}
    }

	public function findSession($user_id, $token) {
		$session = UserSessions::where('user_id', $user_id)->where('token', $token)->first();
		if ($session) {
			if ($token != $session->token) return false;
			else return true;
		} else return false;
	}
}