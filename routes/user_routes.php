
<?php 
$router->post('user/login', ['uses' => 'UsersAuthController@authenticate']);
$router->post('user/logout', ['uses' => 'UsersAuthController@logout']);

$router->group(['middleware' => 'users.auth'],  function() use ($router) {

	Route::group(["prefix" => "/me"], function() use ($router) {
		$router->get("/", "UsersController@info");
		$router->get("/permissions", "UsersController@permissions");
		$router->get("/menus", "UsersController@menus");
	});

// 	$router->get('me/dashboard-pro', ['uses' => 'UserInvestmentController@dashboard']);

// 	Route::group(["prefix" => "/me"], function() use ($router) {
// 		$router->get("/", "UsersController@userProfile");
// 		$router->get("/get_user_id", "UsersController@getUserId");
// 	});

});
