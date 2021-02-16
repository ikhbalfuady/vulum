
<?php 
$router->post('user/login', ['uses' => 'UsersAuthController@authenticate']);

Route::group(["prefix" => "/me"], function() use ($router) {
	$router->get("/menu", "UsersController@menu");
});

$router->get('payment_gateways', ['uses' => 'MetaController@payment_gateways']);


$router->group(['middleware' => 'users.auth'],  function() use ($router) {

	$router->get('me/dashboard-pro', ['uses' => 'UserInvestmentController@dashboard']);

	Route::group(["prefix" => "/me"], function() use ($router) {
		$router->get("/", "UsersController@userProfile");
		$router->get("/get_user_id", "UsersController@getUserId");
	});

});
