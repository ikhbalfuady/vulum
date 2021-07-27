<?php
function splitUppercaseToStrip($string){
    $selector = preg_replace('/([a-z0-9])?([A-Z])/','$1-$2',$string);
    if($selector[0] == '-') $selector = substr($selector, 1); // hapus underscore di awal text
    return $selector;
}

function splitUppercaseToUnderscore($string){
    $selector = preg_replace('/([a-z0-9])?([A-Z])/','$1_$2',$string);
    if($selector[0] == '_') $selector = substr($selector, 1); // hapus underscore di awal text
    return $selector;
}

function splitUppercaseToSpace($string){
    $selector = preg_replace('/([a-z0-9])?([A-Z])/','$1 $2',$string);
    if($selector[0] == ' ') $selector = substr($selector, 1); // hapus space di awal text
    return $selector;
}

function fixUseName($string){
    return str_replace(" ","",$string);
}

function createList ($obj) {
    $col = $obj['column'];
    $col = str_replace('("','","',$col);
    $col = str_replace('$table->','{"type":"',$col);
    $col = str_replace(',"',',"name":"',$col);
    $col = str_replace(', ',', "length":',$col);
    $col = str_replace(')','}',$col);
    $col = str_replace(';',',',$col);
    $col = str_replace('\n','',$col); // remove enter
    $col = trim($col);
    $res = '{"name":"'.$obj['name'].'","column":['.$col.']}';

    return json_decode($res);
}

function enableLogingUser ($column) {
    $column[] = json_decode(json_encode(["name" => "created_by", "type" => "unsignedBigInteger", "attributes" => ['nullable'] ]));
    $column[] = json_decode(json_encode(["name" => "updated_by", "type" => "unsignedBigInteger", "attributes" => ['nullable'] ]));
    $column[] = json_decode(json_encode(["name" => "deleted_by", "type" => "unsignedBigInteger", "attributes" => ['nullable'] ]));
    return $column;
}

$outputDir = 'output/'; 
if (!file_exists($outputDir)) mkdir($outputDir, 0777, true); // generate folder output

include 'function/generate_migrations.php';
include 'function/generate_route.php';
include 'function/generate_model.php';
include 'function/generate_controller.php';
include 'function/generate_repository.php';
include 'function/generate_menu_permission.php';

include 'function/generate_postman.php';

include 'function/generate_ui.php';
include 'function/generate_ui_non_permission.php';
include 'function/generate_route_model_ui.php';

include 'list.php';

$list = json_encode($list);
$list = json_decode($list);

generateMigrations($list, $outputDir); // login user di exec disini
generateModel($list, $outputDir);
generateController($list, $outputDir);
generateRepository($list, $outputDir);
generateRoute($list, $outputDir);
generatePostman($list, $outputDir);
generateMenuPermission($list, $outputDir);
generateUi($list, $outputDir);
generateUiNonPermission($list, $outputDir);
generateRouteModelUi($list, $outputDir);
echo "Generate Completed! <br>";

