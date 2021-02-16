<?php
Route::group(["prefix" => "/users"], function() use ($router) {
	$router->get("/", "UsersController@index");
	$router->get("/{id}", "UsersController@findById");
	$router->post("/", "UsersController@store");
	$router->put("/{id}", "UsersController@store");
	$router->put("/restore/{id}", "UsersController@restore");
	$router->delete("/{id}", "UsersController@remove");
	
});