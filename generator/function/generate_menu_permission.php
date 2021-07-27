<?php

function generateMenuPermission($list, $outputDir = '') {

$permission = '';
$menu = '';
foreach($list as $item){

    $name = $item->name;
    $module = strtolower(splitUppercaseToStrip($name));
    $slug = strtolower(splitUppercaseToUnderscore($name));
    $label = strtolower(splitUppercaseToSpace($name));
    
    $permission .= '
    "'.$module.'" => [
      "add" => true,
      "edit" => true,
      "delete" => true,
      "view" => true,
    ],
    ';

    $menu .= '
    [
        "name" => "'.$label.'",
        "icon" => "stop_circle",
        "path" => $adminPath ."/'.$module.'",
        "page" => "'.$module.'",
        "separator" => false, // boolean
        "params" => null, // [ "status" => "active" ]
        "sub" => [
            [
                "name" => "List '.$label.'",
                "icon" => "stop_circle",
                "path" => $adminPath ."/'.$module.'",
                "page" => "'.$module.'",
                "separator" => false, // boolean
                "params" => null,
            ],
            [
                "name" => "Add '.$label.'",
                "icon" => "stop_circle",
                "path" => $adminPath ."/'.$module.'/form",
                "page" => "'.$module.'-add",
                "separator" => false, // boolean
                "params" => null,
            ]
        ]
    ],';

}

$permission = '<?php 
$permission = [
'.$permission.'
];
';

$menu = '<?php 
$adminPath = "";

$menu = [
'.$menu.'
];
';
 
$createPermission = fopen($outputDir."permission.php", "w") or die("Unable to open file!");
fwrite($createPermission, $permission);
fclose($createPermission);   

$createMenu = fopen($outputDir."menu.php", "w") or die("Unable to open file!");
fwrite($createMenu, $menu);
fclose($createMenu);

}