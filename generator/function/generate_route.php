<?php

function generateRoute ($list, $outputDir = '') {

$_scriptRoute = '';
$importer = '';
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

}
$scriptRoute = '<?php ' ."\r\n\r\n" .'$importer = array( '.$importer . "\r\n".');'. "\r\n\r\n" .$_scriptRoute;
 
$createRoute = fopen($outputDir."routes.php", "w") or die("Unable to open file!");
fwrite($createRoute, $scriptRoute);
fclose($createRoute);   

}