<?php

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\UploadFile;

/**
 * Get the configuration path.
 *
 * @param  string $path
 * @return string
 */

function H_appVersion() {
    return '2.0.1';
}

function H_lastUpdateApp() {
    return '2021-07-27 11:52';
}

function H_resources_path($path = null) {
    return rtrim(app()->basePath('resources/' . $path), '/');
}

function config_path($path = '') {
    return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
}

function H_pub_path($path = null) {
    return rtrim(app()->basePath('public/' . $path), '/');
}

function H_uploads_path($path = null) {
    return rtrim(app()->basePath('public/uploads/' . $path), '/');
}

function H_uploads_dir($path = null) {
    return rtrim('/uploads/' . $path, '/');
}

function H_fixPath($path) {
    return realpath($path);
}

function H_apiModel($data, $msg = 'success', $res = true){
    $res = array(
        'result' => $res,
        'message' => $msg,
        'data' => $data
    );
    return $res;
}

function H_apiResponse($data, $msg = 'success', $code = 200){
    $res = array(
        'message' => $msg,
        'data' => $data
    );
    return response($res, $code);
}


function H_api403(){
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: *");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header("HTTP/1.0 403");
    $result = array(
        "message"=> "You don't have permission to perform this!",
        "data"=> null,
    );
    echo json_encode($result);
    die();
}

function H_apiResError($e){

    $message = 'ERROR';

    if (gettype($e) === 'array'){
        if (isset($e['code'])) $code = $e['code'];
        if (isset($e['message'])) $message = $e['message'];
    } else $message = $e->getMessage();

    return H_apiResponse(null, $message, 400);
}

function H_paginate($items, $endpointName, $perPage = 5, $paramsUrl = "", $page = null, $totalData){

    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $resolvePathUrl = env("APP_URL") ."/$endpointName/table";

    $options = array(  // "fragment" => '', "pageName" => 'page',
        "path"=> $resolvePathUrl, 
        "query" => ($paramsUrl != "") ? H_getQueryStringPaginate($paramsUrl) : null
    );

    return new LengthAwarePaginator($items, $totalData, $perPage, $page, $options);
}

function H_getQueryStringPaginate($paramsUrl){
    $queryString = array();
    if($paramsUrl != ""){
        $paramsUrl = explode("&", $paramsUrl); // pecah by &
        $last = count($paramsUrl) - 1;

        foreach ($paramsUrl  as $index => $params) {
            $amp = "&";
            if($index == $last) $amp = "";

            $paramName = explode("=", $params); // pecah by =
            $name = $paramName[0]; 
            $value = str_replace("%7C", "|", $paramName[1]);
            $value = str_replace("%20", " ", $value);

            $newParams = array($name => $value); 
            if($paramName[0] !== 'page') $queryString += $newParams;  

        }
    }

    return $queryString;
}

function H_getQueryString() {
    if(isset($_SERVER['QUERY_STRING'])){
        return $_SERVER['QUERY_STRING'];
    } else {
        return null;
    }
}

function H_extractLikeQuery($queryString) {
    $column = "";
    $value = "";
    $queryLikeSearch = ($queryString !== null) ?  $queryString : null; 
    $queryLikeSearch = explode("|", $queryLikeSearch);
    if (isset($queryLikeSearch[0])) $column =  $queryLikeSearch[0];
    if (isset($queryLikeSearch[1])) $value =  $queryLikeSearch[1];
    
    $res = array(
        "error" => true,
        "error_type" => "column",
        "column" => "Please define name of column in 'like' params, ex : ?like={colum_name}|value ",
        "value" => "Please define value of column in 'like' params, ex : ?like=colum_name|{value} ",
    );

    if ($column !== "") {
        if ($value !== "") {
            $res['error'] = false;
            $res['column'] = $column;
            $res['value'] = $value;

        } else $res['error_type'] = "value";
    }

    return $res;
}

function H_getWildcardType($request){

    $wildcard = "all"; // all, start, end
    if ( isset($request["wc"]) && $request["wc"] !== "") $wildcard = $request["wc"];
    elseif ( isset($request["wildcard"]) && $request["wildcard"] !== "") $wildcard = $request["wildcard"];

    // create default
    if($wildcard == "start") $wildcard = "start";
    elseif($wildcard == "end") $wildcard = "end";
    else $wildcard = "all";

    return $wildcard;
}

function H_encryptText($text, $salt) {
    $key = hash('sha256', $salt);
    $iv = substr(hash('sha256',  $salt), 0, 16);
    $result = openssl_encrypt($text, "AES-256-CBC", $key, 0, $iv);
    return $result;
}

function H_decryptText($text, $salt) {
    $key = hash('sha256',  $salt);
    $iv = substr(hash('sha256',  $salt), 0, 16);
    $result = openssl_decrypt($text, "AES-256-CBC", $key, 0, $iv);
    return $result;
}

function H_getUrlFile($path, $name, $extension) {
    // return env("APP_URL") ."/". env("APP_DIR") ."/". $path ."/". $name .".". $extension;
    return env("APP_URL") ."/". $path ."/". $name .".". $extension;
}

function H_getPathFile($path, $name, $extension) {
    return "public/" . $path ."/". $name .".". $extension;
}

function H_getDirFile($fileFullPath) {
    $path = str_replace("/","'\'",$fileFullPath);
    $path = str_replace("'","",$path);
    $dir = __DIR__ ;
    $dir = str_replace("app","",$dir);
    $fullPath = $dir.$path;
    return  $fullPath;
}

function H_checkFileExist($filePath) {
    if (file_exists($filePath)) {
            return true;
        } else {
            return false;
        }
}

function H_deleteFile($path) {
    $fullPath = H_getDirFile($path);
    try {
        if (H_checkFileExist($fullPath)) {
            unlink($fullPath);
            return true;
        } else {
            return false;
        }
    } catch (Exception $e){
        return $e->getMessage();
    }
    
}

function H_deleteFilePath($fullPath) {
    try {
        if (H_checkFileExist($fullPath)) {
            unlink($fullPath);
            return true;
        } else {
            return false;
        }
    } catch (Exception $e){
        return $e->getMessage();
    }
    
}

function H_fileSizeFormater($size) {
    $units = array('KB', 'MB', 'GB', 'TB');
    $currUnit = '';
    while (count($units) > 0  &&  $size > 1024) {
        $currUnit = array_shift($units);
        $size /= 1024;
    }
    return ($size | 0) . $currUnit;
}

function H_today(){
    return date_format(Carbon::now(),"Y-m-d H:i:s"); 
}

function H_todayUTC(){
    $date = H_today();
    return H_toUTC($date); 
}

function H_toUTC($date){
    $date = Carbon::parse($date)->setTimezone('UTC');
    return ''.$date;
}

function H_getCurrentDate($time = false, $format = null){
    $date = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    if (!$time) $date = H_formatDate($date, 'Y-m-d');
    if ($format != null) $date = H_formatDate($date, $format);

    return $date;
}

function H_formatDate($date, $format = 'Y-m-d H:i:s') {
    return date($format, strtotime($date));
}

function H_getDateFormat($format = 'Y-m-d H:i:s'){
    return gmdate($format, time() + 60 * 60 * 7); 
}

function H_createDate($d, $type = ''){
            
    $hexdate = '/[^a-z-A-Z\^0-9\x00-\x1f\x05\x0E\x16\x03]/';
    
    if($type == 'system'){
        $date= preg_replace($hexdate, '',$d);
        $date= date('Y-m-d H:i:s', strtotime($date));    
        
    }elseif($type == 'start'){
        $date= preg_replace($hexdate, '',$d);
        $date = date('Y-m-d ', strtotime($date)); 
        $time = '00:00:00';
        $date = "$date $time";
        
    }elseif($type == 'end'){
        $date= preg_replace($hexdate, '',$d);
        $date = date('Y-m-d ', strtotime($date)); 
        $time = '23:59:59';
        $date = "$date $time";
        
    }else{
        if($type == ''){
            $dateType= 'Y-m-d H:i:s';
        }else{
            $dateType= $type;
        }
        
        $date= preg_replace($hexdate, '',$d);
        $date= date($dateType, strtotime($date));    
    }
    
    return $date;    
    
}

function H_getDiffDate($d1, $d2, $format = '%a') { // with indicator use format : %R%a
    $datetime1 = new DateTime($d1);
    $datetime2 = new DateTime($d2);
    $interval = $datetime1->diff($datetime2);
    return $interval->format($format);
}

function H_getTotalHours($date1, $date2) {
    $hour1 = 0; 
    $hour2 = 0;
    // $date1 = "2014-05-27 01:00:00";
    // $date2 = "2014-05-28 02:00:00";
    $datetimeObj1 = new DateTime($date1);
    $datetimeObj2 = new DateTime($date2);
    $interval = $datetimeObj1->diff($datetimeObj2);
    
    if($interval->format('%a') > 0){
    $hour1 = $interval->format('%a')*24;
    }
    if($interval->format('%h') > 0){
    $hour2 = $interval->format('%h');
    }
    
    return $hour1 + $hour2;
}

function H_addDays($d1, $days) { // with indicator use format : %R%a
    $date = date_create($d1);
    date_add($date, date_interval_create_from_date_string("$days days"));
    return date_format($date, 'Y-m-d H:i');
}


function H_overDate($d1, $d2) { // with indicator use format : %R%a
    
    if (strtotime($d1) > strtotime($d2)) return true;
    else return false;
}

function H_formatName($prefix = 'FU_', $subfix = ''){
    $random = H_randomString();
    $name = $prefix . date_format(Carbon::now(),"YmdHis").'_'. $random . $subfix;
    return $name ; 
}

function H_randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function H_randomNumber($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function H_nullDate(){
    return "0000-00-00 00:00:00";
}

function H_JWT_encode($user_id, $data){
    $expired = env('SESSION_EXPIRED_DAY');
    $payload = [
        'iss' => "lumen-jwt",
        'sub' => $user_id, // user id 
        'data' => $data, // data credentials
        'iat' => time(), // Time when JWT was issued.
        'exp' => Carbon::now()->addDay($expired)->timestamp // Expiration time
    ];

    // As you can see we are passing `JWT_SECRET` as the second parameter that will
    // be used to decode the token in the future.
    return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
}

function H_JWT_decode($token){
    $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
    return $credentials;
}

function H_JWT_getUserId($raw_request){
    $token = H_getHeaderToken($raw_request);
    $res = null;
    if ($token != "null") {
        if (!empty($token)) {
            $info = H_JWT_decode($token);
            if (isset($info->sub) && !empty($info->sub)) $res = $info->sub;
        }
    }
    return $res;
}

function H_JWT_getData($raw_request){
    $token = H_getHeaderToken($raw_request); 
    $info = H_JWT_decode($token); 
    return $info->data;
}

function H_cleanToken($token){
    return str_replace("Bearer ", "", $token);
}

function H_getHeaderToken($raw_request){
    if ($raw_request) {
        $headers = $raw_request->headers->all();
        if (isset($headers['authorization']) && !empty($headers['authorization'])) {
            $token = H_cleanToken($headers['authorization'][0]); 
            return $token;
        } else return null;  
    } else return null;
}

function H_hasValue($value, $typeValue = 'string' ,$defaultValue = null){
        
    $data = $defaultValue;
    if ($value !== null) {
        if(isset($value) && $typeValue == 'integer') $data = intval($value);
        elseif(isset($value) && $typeValue == 'numeric') $data = (float) $value;
        elseif(isset($value) && $typeValue == 'boolean') $data = true;
        else $data = $defaultValue;
    }

    return $data;
} 

function H_setter($value, $defaultValue = null){
        
    $data = $defaultValue;
    if(isset($value) && $value != '') $data = $value;
    elseif(isset($value) && $value != null) $data = $value;

    return $data;
}

function H_setterModel($model, $attribute, $defaultValue = null){
    
    $res = $defaultValue;
    if ($model) {
        if (isset($model->{$attribute})) $res = $model->{$attribute};
    }

    return $res;
}

function H_uploadFile($request, $attributeName = 'file', $customPath = ''){
    try {
        $fileInfo = null;
        if ($request->hasFile($attributeName)) {
            $file = $request->file($attributeName);

            $basePathUpload = 'uploads';
            $path = env('APP_UPLOAD_DIR', $basePathUpload) . $customPath;
            if (!file_exists($path)) mkdir($path, 0777, true); // generate folder output

            $fileName = H_formatName() .'.'. $file->getClientOriginalExtension();
            $fileInfo = array(
                "name" => $fileName,
                "size" => $file->getSize(),
                "size_readable" => H_fileSizeFormater($file->getSize()),
                "type" => $file->getClientMimeType(),
                "extension" => $file->getClientOriginalExtension(),
                "ip_location" => H_getIpClient(),
                "error" => $file->getError(),
                "error_message" => $file->getErrorMessage(),
                "path" => $path,
                "url" => $path .'/'. $fileName,
            );

            if ($fileInfo['extension'] == 'exe' || $fileInfo['extension'] == 'bat') {
                $msg = "File format not allowed to upload";
                return H_apiResponse(null, $msg, 400);
            }

            $storeFile = $file->move($fileInfo['path'], $fileName);

            if ($storeFile) {
                $fileInfo['message'] = "Upload succesfully.";
                $fileInfo['error_message'] = null;
            } else{
                $fileInfo['error'] = true;
                $fileInfo['message'] = "Upload failed." . $fileInfo['error_message'];
            } 
        }

        return $fileInfo;
    } catch (Exception $e){
        return $e->getMessage();
    }
}

function H_cleanJSON($data){

    $json = str_replace("\n", '', $data); // clean enter
    $json = preg_replace('/\s/', '', $json); // clean space
    $json = json_decode($json, true); // clean space
    if($data !== '' && $json == null) throw new Exception("[H_cleanJSON] Failed Extract data");
    
    return $json;
}

function H_validateJSON($data, $assoc = true){
    $json = json_decode($data, $assoc); // clean space
    if($data !== '' && $json == null) throw new Exception("[H_validateJSON] Failed Extract data");
    return $json;
}

function H_counter($val){
    $ret = 0;
    if ($val !== null) $ret += $val;
    $ret += 1;
    return $ret;
}

function H_currency($_number = 0){
    $number = floatval($_number);
    $decimalDigit = 0;
    if (H_haveDecimal($number)) $decimalDigit = 2;
    $val = number_format($number, $decimalDigit, ',','.');
    return $val;
}

function H_haveDecimal($number){
    if(fmod($number, 1) !== 0.00) return true;
    else return false;
}

function H_getObjectKey($obj){
    return array_keys($obj[0]);
}

function H_getFileSize($fileSize, $fromPath = '') {

    $size = $fileSize;
    if($fromPath != '') $size = filesize($fromPath);

    $units = array('KB', 'MB', 'GB', 'TB');
    $currUnit = 'B';
    while (count($units) > 0  &&  $size > 1024) {
        $currUnit = array_shift($units);
        $size /= 1024;
    }
    return ($size | 0) . $currUnit;
}

function H_getListFile($dir) {

    $result = array();

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value)
    {
    if (!in_array($value,array(".","..")))
    {
        if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
        {
            $result[$value] = getListFile($dir . DIRECTORY_SEPARATOR . $value);
        }
        else
        {
            $result[] = $value;
        }
    }
    }

    return $result;
}

function H_autoNumber($lastIndex = 1, $digit = 5){
    if($lastIndex == 0 || $lastIndex == "0") $lastIndex = 1;
    else $lastIndex = $lastIndex+1;
    return str_pad($lastIndex, $digit, "0", STR_PAD_LEFT);
}

function H_autoNumberDateBy($lastIndex = 1, $date = 'y', $digit = 4){
    $date   = gmdate($date, time()+60*60*7);    
    $number = str_pad($lastIndex, $digit, "0", STR_PAD_LEFT);
    return $date.$number;
}

/*
    |--------------------------------------------------------------------------
    | getServerInfo : Directory configuration
    |--------------------------------------------------------------------------
    |
    | for getting information server  
    | like http or https protocol , url , directory path
    |
    | syntax : Helper::getServerInfo('url');
    |
*/
function H_getServerInfo($case){

    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
    $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    $url = "".$_SERVER['HTTP_HOST']."/";
    $host = "$protocol://$url";
    $dir= dirname(__FILE__);

    if($case == 'protocol') return $protocol;
    elseif($case == 'url') return $url;
    elseif($case == 'host') return $host;
    elseif($case == 'dir') return $dir;
    elseif($case == 'root') return $GLOBALS['rootDir']."/";
    elseif($case == 'upl_base') return $GLOBALS['upl_base']."/";
    elseif($case == 'upl_gallery') return $GLOBALS['upl_gallery']."/";
    elseif($case == 'upl_primary_img') return $GLOBALS['upl_primary_img']."/";
    else return null;

}

/*
    |--------------------------------------------------------------------------
    | H_getDayFromDateRange : Counter
    |--------------------------------------------------------------------------
    |
    | for getting value of day in to range date
    | only accept date system value, dont use time for efficiency calculate
    |
    | syntax : H_getDayFromDateRange('2019-01-01','2019-01-03');
    |
*/
function H_getDayFromDateRange($date1, $date2){
    $days = strtotime($date2) - strtotime($date1);
    return floor($days/(60*60*24));
}

/*
    |--------------------------------------------------------------------------
    | checkValue : Validation
    |--------------------------------------------------------------------------
    |
    | for checking the absolute value
    |
    | syntax : Helper::checkValue($value,'string');
    |
*/
function H_checkValue($value, $type = 'string')
{

    if($type == 'string'){
    if($value != '' OR $value != null) return true;
    else return false;
    }
    elseif($type == 'number'   OR $type == 'float'){
    if($value != 0 OR $value != null) return true;
    else return false;
    }
    elseif($type == 'int'      OR $type == 'integer'){
    if($value != 0 OR $value != null) return true;
    else return false;
    }
    elseif($type == 'int'      OR $type == 'integer'){
    if($value != 0 OR $value != null) return true;
    else return false;
    }
    elseif($type == 'array'      OR $type == 'object'){
    if(gettype($value) == 'array' || gettype($value) == 'object' ){
        if(count($value) != 0 ) return true;
        else return false;
    } 
    else return false;
    }
    else return false;

}

function H_toBoolean($val) {
    $res = false;
    if ($val == 0 || $val == '0')  $res =  false;
    elseif ($val == 1 || $val == '1')  $res =  true;
    
    if ($val === 'true')  $res =  true;
    elseif ($val === 'false')  $res =  false;
    
    return $res ;
}

function H_validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function H_splitUppercaseWithStrip($string){
    $selector = preg_replace('/([a-z0-9])?([A-Z])/','$1-$2',$string);
    if($selector[0] == '-') $selector = substr($selector, 1); // hapus underscore di awal text
    return $selector;
}

function H_splitUppercaseWithUnderscore($string){
    $selector = preg_replace('/([a-z0-9])?([A-Z])/','$1_$2',$string);
    if($selector[0] == '_') $selector = substr($selector, 1); // hapus underscore di awal text
    return $selector;
}

function H_splitUppercaseWithSpace($string){
    $selector = preg_replace('/([a-z0-9])?([A-Z])/','$1 $2',$string);
    if($selector[0] == ' ') $selector = substr($selector, 1); // hapus underscore di awal text
    return $selector;
}

function H_sortBy($array, $by) {
    usort($array, build_sorter($by));

    $res = array();    
    foreach ($array as $item) {
        array_push($res, $item);
    } 

    return $res;
}

function build_sorter($key) {
    return function ($a, $b) use ($key) {
        return strnatcmp($a[$key], $b[$key]);
    };
}

function H_stop($data) {
    // $res = H_jsonBeauty($data);
    // print_r("<pre>$data</pre>");
    print_r($data);
    die();
}

function H_hasRequest($request, $attribute) {
    $res = false;
    if (isset($request[$attribute]) && $request[$attribute] !== '') $res =  true;
    if (isset($request[$attribute]) && $request[$attribute] === null) $res =  false;
    return $res ;
}

function H_isRequestNum($payload, $attribute, $default = 0) {
    $check = H_hasRequest($payload, $attribute);
    if ($check) {
        if (is_numeric($payload[$attribute])) {
            return intval($payload[$attribute]);
        } else return $default;
    } else return $default;
}

function H_handleRequest($payload, $attribute, $default = null) {
    $check = H_hasRequest($payload, $attribute);
    if ($check) {
        return $payload[$attribute];
    } else return $default;
}

function H_handleRequestJson($payload, $attribute, $default = null) {
    $check = H_hasRequest($payload, $attribute);
    if ($check) {
        if (is_array($payload[$attribute])) return $payload[$attribute];
        else return json_decode($payload[$attribute]);
    } else return $default;
}

function H_sumArrayBy($raw, $column, $column2 = null)
{   
    $res = 0;
    foreach ($raw as $key => $row) {
        if ($column2 == null) $res += $row[$column];
        else  $res += $row[$column] +  $row[$column2];

    }
    return $res;
}

function H_findObjectByKey($array, $key, $value, $getIndex = false)
{
    for ($i=0; $i < count($array) ; $i++) { 
        if ($array[$i][$key] === $value) {
            if ($getIndex) return $i;
            else return $array[$i];
        }
    }
}

function H_findObjectByKey2Value($array, $key, $value, $key2, $value2, $getIndex = false)
{
    for ($i=0; $i < count($array) ; $i++) { 
        if ($array[$i][$key] === $value && $array[$i][$key2] === $value2) {
            if ($getIndex) return $i;
            elseif (!$getIndex) return $array[$i];
            else return null;
        }
    }
}

function H_jsonBeauty($array)
{
    return json_encode($array, JSON_PRETTY_PRINT);
}

function H_persentase ($val, $from, $up = true) { // up : pembulatan keatas (true), pembulatan kebawah (false)
    if ($val != 0) {
        $res = ($val / 100) * $from;
        if ($up) return ceil($res);
        else return floor($res);
    } else return $val;

}

function H_passwordMaker($password) {
    $salt = H_encryptText($password, $password); // make salt
    $pwd = H_encryptText($password, $salt); // compiled password
    return $pwd;
}

function H_passwordChecker($password_input, $password_db) {
    $password = H_passwordMaker($password_input);
    if($password === $password_db) return true;
    else return false;
}

function H_getIpClient() {
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)) $clientIp = $client;
    elseif(filter_var($forward, FILTER_VALIDATE_IP))  $clientIp = $forward;
    else $clientIp = $remote;

    return $clientIp;
}

function H_extractParamsAttribute($params) {

    $fix_params = [];
    $parent = explode('|', $params);
    if (count($parent) != 0) {
        foreach ($parent as $value) {
            if ($value != '') {
                $child = explode(':', $value);
                if (count($child) != 0) {
                    if (isset($child[0]) && $child[0] != '') {
                        if (isset($child[1]) && $child[1] != '') {
                            if ($child[0] != '' && $child[1] != '') {
                                $val = $child[1];
                                if (isset($child[2])) $val = $val.':'.$child[2]; // untuk optimal nilai type dateTime
                                if (isset($child[3])) $val = $val.':'.$child[3]; // untuk optimal nilai type dateTime
                                $fix_params[] = array(
                                    "key" => $child[0],
                                    "value" => $val,
                                );
                            }
                        }
                    }
                }
            }
        }
    }
    return $fix_params;
}

function H_extractSingleParamsAttribute($params) {

    $parent = explode('|', $params);
    return $parent;
}

function H_toArrayObject($data) {
    $data = json_encode($data);
    $data = json_decode($data);
    return $data;
}

function H_escapeStringTable($data){
    $hex = '/[^a-z-A-Z_!.-@\^0-9\x00-\x1f\x05\x0E\x16\x03]/';
    $steril = preg_replace($hex, '',$data);
    $steril = str_replace('-', '',$steril);

    return $steril;
}

function H_escapeString($data, $useStrip = true){
    $hex = '/[^a-z-A-Z_ \-\^0-9\x00-\x1f\x05\x0E\x16\x03]/';
    $steril = preg_replace($hex, '',$data);
    if ($useStrip) $steril = str_replace('-', '',$steril);

    return $steril;
}

function H_jsonToArray($data) {
    $res = [];
    if (!empty($data)) $res = json_decode($data);
    return $res;
}

function H_arrayToJson($data, $default = '[]') {
    $res = $default;
    if (!empty($data)) $res = json_encode($data);
    return $res;
}

function H_hasDataArray($data) {
    if (count($data) == 0) return false;
    else return true; 
}

function H_isNullArray($data) {
    return (count($data) == 0) ? true : false;
}

function H_isNullList($data) {
    $data = H_toArrayObject($data);
    if (isset($data->data) && count($data->data) == 0) return true;
    else return false;
}

function H_isNoData($request, $data) {
    $payload = $request->all();
    if (H_hasRequest($payload, 'table')) $null_info = H_isNullList($data);
    else $null_info = H_isNullArray($data);

    return $null_info;
}

function H_isObject($arr) {
    if (!empty($arr) && is_array($arr)) { // user is_array to makesure array format sended
        if (count($arr) != 0 ) { 
            $arr = H_toArrayObject($arr);
            return is_object($arr);
        }
    } else return false;
}

function H_isArray($arr) {
    if (!empty($arr) && is_array($arr)) { // user is_array to makesure array format sended
        if (count($arr) != 0 ) { 
            $arr = H_toArrayObject($arr);
            return is_array($arr);
        }
    } else return false;
}

function H_makeSlug($var) {
    $var = H_splitUppercaseWithSpace($var);
    $var = str_replace(' ', '-', $var);
    return strtolower($var);
}

function H_makeSlugString($var) {
    $var = H_escapeString($var, false);
    $var = str_replace(' ', '-', $var);
    $var = str_replace('--', '-', $var);
    $var = str_replace('---', '-', $var);
    $var = str_replace('----', '-', $var);
    return strtolower($var);
}

function H_fixNullValue($arr) {
    foreach ($arr as $key => $value) {
        if ($value == 'null') $arr[$key] = null;
    }
    return $arr;
}

function H_causer($id) {
    return \App\Models\Users::find($id);
}

function formatGetRepo($model) {
    $str = strtolower(substr($model, 0, 1));
    $checkIes = substr($model, -3);
    if ($checkIes === 'ies') $str .= substr($model, 1, strlen($model) - 4).'y';
    else $str .= substr($model, 1, strlen($model) - 2);
    $str .= 'Repository';

    return $str;
    return $str;
}

function H_formatToRoman($number) {
    $map = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1
    ];
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

function H_uploadFileV2($file) {
    return new UploadFile($file);
}

function H_getModuleName ($data) {
    $moduleName = str_replace(
        ['App\Models\\'],
        ['', ''],
        $data->getMorphClass()
    );
    return $moduleName;
}

function H_dropColumnIfExists($myTable, $column)
{
    if (Schema::hasColumn($myTable, $column)) //check the column
    {
        Schema::table($myTable, function (Blueprint $table) use ($column)
        {
            $table->dropColumn($column); //drop it
        });
    }

}

function H_extractKeyRelation($key) {
    $res = null;
    $ex = explode('.', $key);
    if (
        count($ex) > 1 && count($ex) < 3 && // check validation
        strlen($ex[0]) > 0 && strlen($ex[1]) > 0  // check value
    ) {
        $importantCheck = explode('!', $ex[1]);
        $column = $importantCheck[0];

        $operatoCheck = explode('@', $column);
        $column = $operatoCheck[0];

        $res = [
            'relation' => $ex[0],
            'column' => $column,
        ];
    }
    return $res;
}

function H_toStandardDate($date) { 
    // agar tanggal sesua dengan db dan config laravel
    // jadi saat query ke db jam yg di ui akan sesuai dengan di db
    $date = Carbon::parse($date);
    $date->setTimezone('-3');
    return $date;
}

function H_isValidDate($date) {
    if (strtotime($date)) {
        $date = Carbon::parse(strtotime($date))->toDateTimeString();
        return H_toStandardDate($date);
    } else return false;
}

function H_operatorIdentifier() {
    return '@';
}

function H_getOperatorType($type) {
    $identifier = H_operatorIdentifier();
    if (
        // integer format
        $type == 'lt' // less than
        || $type == 'lte' // less than equal
        || $type == 'gt' // greater than
        || $type == 'gte' // greater than equal
        // date format
        || $type == 'ltd' // less than equal
        || $type == 'lted' // less than equal
        || $type == 'gtd' // greater than
        || $type == 'gted' // greater than equal
        || $type == 'start' // start point
        || $type == 'end' // end point
    ) {
        return $identifier.$type;
    } else return null;
}

function H_getOperatorMode($key) {
    $ex = explode(H_operatorIdentifier(), $key);
    return $ex[1];
}

function H_getOperatorSearch($key) {
    $res = null;
    $ex = explode(H_operatorIdentifier(), $key);
    if (count($ex) > 1) {
        if ($ex[1] == 'lt' || $ex[1] == 'ltd') $res = '<';
        if ($ex[1] == 'lte' || $ex[1] == 'lted') $res = '<=';
        if ($ex[1] == 'gt' || $ex[1] == 'gtd') $res = '>';
        if ($ex[1] == 'gte' || $ex[1] == 'gted') $res = '>=';
        if ($ex[1] == 'start') $res = '>=';
        if ($ex[1] == 'end') $res = '<=';
    }
    return $res;
}

function H_getColumSearch($key) {
    $checker = explode('!', $key); // exact checker
    $column = $checker[0];
    
    // integer checker
    $checker = explode(H_getOperatorType('gt'), $column); // greater than checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('gte'), $column); // greater than equal checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('lt'), $column); // greater than checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('lte'), $column); // less than equal checker
    $column = $checker[0];
    // date checker
    $checker = explode(H_getOperatorType('gtd'), $column); // greater than checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('gted'), $column); // greater than equal checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('ltd'), $column); // greater than checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('lted'), $column); // less than equal checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('start'), $column); // less than equal checker
    $column = $checker[0];
    $checker = explode(H_getOperatorType('end'), $column); // less than equal checker
    $column = $checker[0];

    // if use relation
    $checker = explode('.', $column); // exact checker
    $column = $checker[1] ?? $checker[0];
    return $column;
}

function H_getValueForOperator($key, $val) {
    try {

        $mode = H_getOperatorMode($key);
        // integer format
        if ( $mode == 'lt' // less than
            || $mode == 'lte' // less than equal
            || $mode == 'gt' // greater than
            || $mode == 'gte' // greater than equal
        ) {
            $val = (int) $val;
        }

        // date format
        if ( $mode == 'ltd' // less than equal
            || $mode == 'lted' // less than equal
            || $mode == 'gtd' // greater than
            || $mode == 'gted' // greater than equal
            || $mode == 'start' // start point
            || $mode == 'end' // end point
        ) {
            $val = H_isValidDate($val);
            if ($mode == 'start') $val = H_formatDate($val, 'Y-m-d ').'00:00:00';
            if ($mode == 'end') $val = H_formatDate($val, 'Y-m-d ').'23:59:00';
            if ($val == false) throw new Exception('Date value not compatible, please makesure the value is valid date format (Y-m-d) or (Y-m-d H:i)');
            // dd($val);
        } 

        return $val;
    } catch (Exception $e){
        throw new Exception($e->getMessage());
    }
}

function H_extractParamLike($str) {
    if ($str && $str !== '') {
        $ex = explode(':', $str);
        $columns = collect(explode(',', $ex[0]));
        $value = $ex[1];

        return (object) [
            'columns' => $columns,
            'value' => $value,
        ];
    }
    return null;
}
