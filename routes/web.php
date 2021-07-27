<?php
  
/** @var \Laravel\Lumen\Routing\Router $router */
  
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
| 
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
| 
*/
  
$router->get('/', function () use ($router) {
    return $router->app->version();
});
  
$router->get('/', function () use ($router) {
$appname = env('APP_NAME');
return "<!DOCTYPE html> <html> <head> <title>$appname v." . H_appVersion()."</title> </head> <body style='margin:0 !important; padding:20px; background:#262626; color: #ff1fdc;font-family: consolas;'>
<div style='text-align:center; margin-top:40vh;'>
$appname v." . H_appVersion() ." <br>
<small>Last Update : " . H_lastUpdateApp('api') . "</small> <br>
</div>
</body> </html> 
  	";
});

$router->get('/version', function () use ($router) {
	return H_apiResponse(H_appVersion());
});
  
include 'public_routes.php';
include 'user_routes.php';
