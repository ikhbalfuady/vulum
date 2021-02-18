<?php

	Route::group(["prefix" => "/users"], function() use ($router) {
		$router->get("/", "UsersController@index");
		$router->get("/{id}", "UsersController@findById");
		$router->post("/", "UsersController@store");
		$router->put("/{id}", "UsersController@store");
		$router->put("/restore/{id}", "UsersController@restore");
		$router->delete("/{id}", "UsersController@remove");
	});
        

	Route::group(["prefix" => "/user-sessions"], function() use ($router) {
		$router->get("/", "UserSessionsController@index");
		$router->get("/{id}", "UserSessionsController@findById");
		$router->post("/", "UserSessionsController@store");
		$router->put("/{id}", "UserSessionsController@store");
		$router->put("/restore/{id}", "UserSessionsController@restore");
		$router->delete("/{id}", "UserSessionsController@remove");
	});
        

	Route::group(["prefix" => "/permissions"], function() use ($router) {
		$router->get("/", "PermissionsController@index");
		$router->get("/{id}", "PermissionsController@findById");
		$router->post("/", "PermissionsController@store");
		$router->put("/{id}", "PermissionsController@store");
		$router->put("/restore/{id}", "PermissionsController@restore");
		$router->delete("/{id}", "PermissionsController@remove");
	});
        

	Route::group(["prefix" => "/role-permissions"], function() use ($router) {
		$router->get("/", "RolePermissionsController@index");
		$router->get("/{id}", "RolePermissionsController@findById");
		$router->post("/", "RolePermissionsController@store");
		$router->put("/{id}", "RolePermissionsController@store");
		$router->put("/restore/{id}", "RolePermissionsController@restore");
		$router->delete("/{id}", "RolePermissionsController@remove");
	});
        

	Route::group(["prefix" => "/roles"], function() use ($router) {
		$router->get("/", "RolesController@index");
		$router->get("/{id}", "RolesController@findById");
		$router->post("/", "RolesController@store");
		$router->put("/{id}", "RolesController@store");
		$router->put("/restore/{id}", "RolesController@restore");
		$router->delete("/{id}", "RolesController@remove");
	});
        

	Route::group(["prefix" => "/menu-items"], function() use ($router) {
		$router->get("/", "MenuItemsController@index");
		$router->get("/{id}", "MenuItemsController@findById");
		$router->post("/", "MenuItemsController@store");
		$router->put("/{id}", "MenuItemsController@store");
		$router->put("/restore/{id}", "MenuItemsController@restore");
		$router->delete("/{id}", "MenuItemsController@remove");
	});
        

	Route::group(["prefix" => "/menus"], function() use ($router) {
		$router->get("/", "MenusController@index");
		$router->get("/{id}", "MenusController@findById");
		$router->post("/", "MenusController@store");
		$router->put("/{id}", "MenusController@store");
		$router->put("/restore/{id}", "MenusController@restore");
		$router->delete("/{id}", "MenusController@remove");
	});
        

	Route::group(["prefix" => "/master-menus"], function() use ($router) {
		$router->get("/", "MasterMenusController@index");
		$router->get("/{id}", "MasterMenusController@findById");
		$router->post("/", "MasterMenusController@store");
		$router->put("/{id}", "MasterMenusController@store");
		$router->put("/restore/{id}", "MasterMenusController@restore");
		$router->delete("/{id}", "MasterMenusController@remove");
	});