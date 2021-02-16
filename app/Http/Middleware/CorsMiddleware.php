<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		
		$IlluminateResponse = 'Illuminate\Http\Response';
		$SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => '*'
            //'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

		$response = $next($request);

		if($response instanceof $IlluminateResponse) {
			foreach ($headers as $key => $value) {
				$response->header($key, $value);
			}
			return $response;
		}

		if($response instanceof $SymfonyResopnse) {
			foreach ($headers as $key => $value) {
				$response->headers->set($key, $value);
			}
			return $response;
		}
    }
}