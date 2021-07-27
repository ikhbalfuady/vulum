<?php

function generateRoute ($list, $outputDir = '') {

$_seedMenu = '';
$_scriptRoute = '';
$importer = '';
$no = 1;
foreach($list as $item){

    $name = $item->name;
    $selector =  strtolower(splitUppercaseToStrip($name));

    $importer .=  "\r\n".'"'.$item->name.'",' ;

    $_scriptRoute .= '

    Route::group(["prefix" => "/'.$selector.'"], function() use ($router) {
        $router->get("/", "'.$name.'Controller@index");
        $router->get("/{id}", "'.$name.'Controller@findById");
        $router->post("/", "'.$name.'Controller@store");
        $router->put("/{id}", "'.$name.'Controller@store");
        $router->put("/{id}/restore", "'.$name.'Controller@restore");
        $router->delete("/{id}", "'.$name.'Controller@remove");
    });
        ';

        $menuName = splitUppercaseToSpace($name);

        $subMenu = "[
            'parent_id' => $no,
            'name' => '$menuName List',
            'slug' => null,
            'path' => '$selector',
            'icon' => null,

        ],
        [
            'parent_id' => $no,
            'name' => 'Add $menuName',
            'slug' => null,
            'path' => '$selector/form',
            'icon' => null,

        ],
        ";

        $_seedMenu .= "[
            'parent_id' => null,
            'name' => '$menuName',
            'slug' => null,
            'path' => '/',
            'icon' => null,

        ],
        $subMenu
        ";


        $no = $no + 3;
}

$nl = "\r\n\r\n";

$scriptRoute = '<?php ' ."\r\n\r\n" .'$importer = array( '.$importer . "\r\n".');'. "\r\n\r\n" .$_scriptRoute . "\r\n\r\n";
$scriptRoute = $scriptRoute . '$menus = ['.$_seedMenu . $nl .'];';
 
$createRoute = fopen($outputDir."routes.php", "w") or die("Unable to open file!");
fwrite($createRoute, $scriptRoute);
fclose($createRoute);   

}