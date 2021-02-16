<?php 

$listExample = array(
	array( 
		/** Module Name 
		 * use PascalCase to define module name, letter char tobe replacing with uderscore if you use 2 words like "UserSession = user_session"
		*/
		"name" => "Users", // use PascalCase to define module name, letter tobe replacing with uderscore if you use 2 words li
		"loging_date" => true, // to activate created_at & updated_at including soft delete of table.
		"column" => array(
			array(
				"type" => "enum", 
				"name" => "id", 
				"length" => 18, 
				"length2" => 3, 
				"default" => "0", 
				"attributes" => ['index','nullable'], 
				"fulltext" => true,
				"enum_list" => "['open', 'closed', 'expired']"
			),
		),
	)
);

$list = array( 
	/** DEFAULT MODULE
	 * Be careful about changing / deleting the user & userSessions module, 
	 * because this module is the default module for setting up authentication, 
	 * unless you already understand or don't need it and are aware of the risks.
	 * Users 		: module to manage data user of application
	 * UserSession 	: module to manage all session of user logged in
	*/ 
	array( "name" => "Users",
		"loging_date" => true,
		"column" => 
		array(
			array(
				"name" => "id",
				"type" => "bigIncrements", 
				"attributes" => ['index'], 
			),
			array(
				"name" => "name",
				"type" => "string", 
			),
			array(
				"name" => "username",
				"type" => "string", 
			),
			array(
				"name" => "password",
				"type" => "string", 
			),
			array(
				"name" => "email",
				"type" => "string", 
			),
			array(
				"name" => "picture",
				"type" => "text", 
				"attributes" => ['nullable'], 
			),
			array(
				"name" => "active",
				"type" => "tinyInteger", 
				"default" => 0, 
			),
		),
	),

	array( "name" => "UserSessions",
		"loging_date" => true,
		"column" => 
		array(
			array(
				"name" => "id",
				"type" => "bigIncrements", 
				"attributes" => ['index'],
			),
			array(
				"name" => "user_id",
				"type" => "unsignedBigInteger",
				"attributes" => ['index'],
			),
			array(
				"name" => "token",
				"type" => "text", 
			),
			array(
				"name" => "ip",
				"type" => "string", 
				"attributes" => ['nullable'], 
			),
			array(
				"name" => "agent",
				"type" => "string", 
				"attributes" => ['nullable'], 
			),
			array(
				"name" => "platform",
				"type" => "string", 
				"attributes" => ['nullable'], 
			),
		),
	),

	array( "name" => "Permissions",
		"loging_date" => true,
		"column" => 
		array(
			array(
				"name" => "id",
				"type" => "bigIncrements", 
				"attributes" => ['index'],
			),
			array(
				"name" => "name",
				"type" => "string", 
			),
			array(
				"name" => "slug",
				"type" => "string", 
				"attributes" => ['nullable'], 
			),
			array(
				"name" => "prefix_group",
				"type" => "string", 
				"attributes" => ['nullable'], 
			),
		),
	),

	array( "name" => "Roles",
		"loging_date" => true,
		"column" => 
		array(
			array(
				"name" => "id",
				"type" => "bigIncrements", 
				"attributes" => ['index'],
			),
			array(
				"name" => "permission_id",
				"type" => "unsignedBigInteger",
				"attributes" => ['index'],
			),
			array(
				"name" => "name",
				"type" => "string", 
			),
			array(
				"name" => "slug",
				"type" => "string", 
				"attributes" => ['nullable'], 
			),
		),
	),

	array( "name" => "UserRoles",
		"loging_date" => true,
		"column" => 
		array(
			array(
				"name" => "id",
				"type" => "bigIncrements", 
				"attributes" => ['index'],
			),
			array(
				"name" => "user_id",
				"type" => "unsignedBigInteger",
				"attributes" => ['index'],
			),
			array(
				"name" => "role_id",
				"type" => "unsignedBigInteger",
				"attributes" => ['index'],
			),
		),
	),

	// Define your module here ..
	
);
