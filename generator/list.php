<?php 

$listExample = [
    [
        /** Module Name 
         * use PascalCase to define module name, letter char tobe replacing with uderscore if you use 2 words like "UserSession = user_session"
        */
        "name" => "Users", // use PascalCase to define module name, letter tobe replacing with uderscore if you use 2 words li
        "loging_date" => true, // to activate created_at & updated_at including soft delete of table.
        "column" => [
            [
                "type" => "enum",  "name" => "id", 
                "length" => 18, 
                "length2" => 3, 
                "default" => "0", 
                "attributes" => ['index','nullable'], 
                "fulltext" => true,
                "enum_list" => "['open', 'closed', 'expired']",
                // realtion
                "belongsTo" => [ 
                    "model" => "Users",
                    "name" => "User", // delete this property to use default from "model" value
                    "foreign" => "_self", // define "_self" or delete this property to use default column name
                    "foreign2" => "id", // delete this property to use default id of foreign module
                ],
                "hasMany" => [ 
                    "model" => "Roles",
                    "name" => "Roles", // delete this property to use default from "model" value
                    "foreign" => "_self", // define "_self" or delete this property to use default column name
                    "foreign2" => "id", // delete this property to use default id of foreign module
                ],
            ],
        ],
    ]
];

$list = [
    /** DEFAULT MODULE
     * Be careful about changing / deleting the user & userSessions module, 
     * because this module is the default module for setting up authentication, 
     * unless you already understand or don't need it and are aware of the risks.
     * Users         : module to manage data user of application
     * UserSession     : module to manage all session of user logged in
    */ 
 
    ["name" => "Users",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'], 
            ],
            [ "name" => "name",
                "type" => "string", 
            ],
            [ "name" => "username",
                "type" => "string", 
            ],
            [ "name" => "password",
                "type" => "string", 
            ],
            [ "name" => "email",
                "type" => "string", 
            ],
            [ "name" => "picture",
                "type" => "text", 
                "attributes" => ['nullable'], 
            ],
            [ "name" => "role_id",
                "type" => "unsignedBigInteger", 
                "attributes" => ['nullable'],
            ],
            [ "name" => "menu_id",
                "type" => "unsignedBigInteger", 
                "attributes" => ['nullable'],
            ],
            [ "name" => "department_id",
                "type" => "unsignedBigInteger", 
                "attributes" => ['nullable'],
            ],
            [ "name" => "active",
                "type" => "tinyInteger", 
                "default" => 0, 
            ],
            
        ],
    ],

    ["name" => "UserSessions",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'],
            ],
            [ "name" => "user_id",
                "type" => "unsignedBigInteger",
                "attributes" => ['index'],
            ],
            [ "name" => "token",
                "type" => "text", 
            ],
            [ "name" => "ip",
                "type" => "string", 
                "attributes" => ['nullable'], 
            ],
            [ "name" => "agent",
                "type" => "string", 
                "attributes" => ['nullable'], 
            ],
            [ "name" => "platform",
                "type" => "string", 
                "attributes" => ['nullable'], 
            ],
            
        ],
    ],

    ["name" => "Permissions",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'],
            ],
            [ "name" => "name",
                "type" => "string", 
            ],
            [ "name" => "slug",
                "type" => "string", 
                "attributes" => ['nullable'], 
            ],
            
        ],
    ],

    ["name" => "RolePermissions",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'],
            ],
            [ "name" => "permission_id",
                "type" => "unsignedBigInteger",
                "attributes" => ['index'],
            ],
            [ "name" => "role_id",
                "type" => "unsignedBigInteger",
                "attributes" => ['index'],
            ],
            
        ],
    ],

    ["name" => "Roles",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'],
            ],
            [ "name" => "name",
                "type" => "string", 
            ],
            [ "name" => "slug",
                "type" => "string", 
                "attributes" => ['nullable'], 
            ],
            
        ],
    ],

    ["name" => "MenuItems",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'],
            ],
            [ "name" => "name",
                "type" => "string",
            ],
            [ "name" => "slug",
                "type" => "string",
                "attributes" => ['index'],
            ],
            [ "name" => "icon",
                "type" => "text",
                "attributes" => ['nullable'],
            ],
            [ "name" => "path",
                "type" => "text",
                "attributes" => ['nullable'],
            ],
            
        ],
    ],

    ["name" => "Menus",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'],
            ],
            [ "name" => "parent_id",
                "type" => "unsignedBigInteger", 
                "attributes" => ['index', 'nullable'],
            ],
            [ "name" => "menu_item_id",
                "type" => "unsignedBigInteger",
                "attributes" => ['index'],
            ],
            [ "name" => "master_menu_id",
                "type" => "unsignedBigInteger",
                "attributes" => ['index'],
            ],
            [ "name" => "overline",
                "type" => "string",
                "attributes" => ['nullable'],
            ],
            [ "name" => "ordering",
                "type" => "integer", 
                "default" => "0",
            ],
            
        ],
    ],

    ["name" => "MasterMenus",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'],
            ],
            [ "name" => "name",
                "type" => "string",
            ],
            
        ],
    ],

    ["name" => "UserNotifications",
        "loging_date" => true,
        "loging_user" => true,
        "column" => 
        [
            [ "name" => "id",
                "type" => "bigIncrements", 
                "attributes" => ['index'], 
            ],
            [ "name" => "user_id",
                "type" => "unsignedBigInteger",
                "attributes" => ['index'],
            ],
            [ "name" => "is_read",
                "type" => "boolean",
                "default" => "false",
            ],
            [ "name" => "title",
                "type" => "string", 
                "attributes" => ['nullable'],
                "fulltext" => true, 
            ],
            [ "name" => "description",
                "type" => "text", 
                "attributes" => ['nullable'],
                "fulltext" => true, 
            ],
            [ "name" => "type",
                "type" => "string", 
                "attributes" => ['nullable'],
            ],
            [ "name" => "link_path",
                "type" => "string", 
                "attributes" => ['nullable'],
            ],
            [ "name" => "link_params",
                "type" => "json", 
                "attributes" => ['nullable'],
            ],
            
        ],
    ],

    // custom module

    ["name" => "Department",
        "loging_date" => true,
        "loging_user" => true,
        "column" => [
            [ "name" => "id",
                "type" => "bigIncrements",
                "attributes" => ["index"],
            ],
            [ "name" => "name",
                "type" => "string",
                "attributes" => ["index"],
            ],
            [ "name" => "parent",
                "type" => "unsignedBigInteger",
                "attributes" => ["index"],
                "belongsTo" => [ 
                    "model" => "Users",
                    "name" => "User", // delete this property to use default from "model" value
                    "foreign" => "parent", // define "_self" or delete this property to use default column name
                    "foreign2" => "id", // delete this property to use default id of foreign module
                ],
            ],
            [ "name" => "rate",
                "type"    => "double",
                "length"  => 18,
                "length2" => 2,
            ],
            
        ],
    ],
];  
