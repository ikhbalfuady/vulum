<?php

function generateRouteModelUi ($list, $outputDir = '') {

$scriptRoute = '';
$scriptModel = '';
$importer = '';
foreach($list as $item){

    $name = $item->name;
    $module = strtolower(splitUppercaseToStrip($name));
    $slug = strtolower(splitUppercaseToUnderscore($name));
    
    $scriptRoute .= "
      { name: '$module', path: '/$module', component: () => import('pages/$module/index.vue') },
      { name: 'view-$module', path: '/$module/view/:id', component: () => import('pages/$module/detail.vue') },
      { name: 'add-$module', path: '/$module/form', component: () => import('pages/$module/form.vue') },
      { name: 'edit-$module', path: '/$module/form/:id', component: () => import('pages/$module/form.vue') },
";

  // model
  $no = 1;
  $last = count($item->column);
  $model = "  $name () {
    var model = {
";
  foreach ($item->column as $col) {
    $coma = ',';
    if ($no == $last) $coma = '';
    $model .= "      ".$col->name . ": null$coma\r\n";
  $no++;}
  $scriptModel .= $model . "    }
    return model
  },\r\n\r\n";

}

$scriptRoute = " 
var routes = [
    $scriptRoute 
]
";
 
$createRoute = fopen($outputDir."routes_ui.js", "w") or die("Unable to open file!");
fwrite($createRoute, $scriptRoute);
fclose($createRoute);   

$createModel = fopen($outputDir."model_ui.js", "w") or die("Unable to open file!");
fwrite($createModel, $scriptModel);
fclose($createModel);   

}