<?php

function generateController ($list, $outputDir = '') {

foreach($list as $item){

    $name = $item->name;
    $selector = strtolower(splitUppercaseToUnderscore($name));
    $slug = strtolower(splitUppercaseToStrip($name));
 
    $repoName = "App\Repositories\ ".$name."Repository;";
    $repoName = fixUseName($repoName);

    $objectKey = '';
    $objectMessage = '';
    $last = count($item->column) - 1;
    $no = 1;
    foreach ($item->column as $index => $col) {
        if($col->name != 'id') {
            $coma = ', ';
            if($index == $last) $coma = '';

            $tab = '';
            if($no != 1) $tab = '                    ';

            $required = true;
            if (isset($col->attributes)){
                foreach ($col->attributes as $value) {
                    if ($value == 'nullable') $required = false;
                    break;
                }
            }

            if ($required) {
                $objectKey .= $tab . '"'.$col->name.'" => "required"'. $coma .' ' ."\r\n";
                $objectMessage .= $tab . '"'.$col->name.'.required" => "'.$col->name.' is required"'. $coma .' ' ."\r\n";
            }
        }
 
    $no++;}

    $script = '<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Exports\ExportFromArray;
use Illuminate\Http\Request;
use '.$repoName.'
use App\Providers\HelperProvider;
use App\Providers\AuthProvider;
use Maatwebsite\Excel\Facades\Excel;

class '.$name.'Controller extends Controller
{
    protected $repository;
    protected $request;

    public function __construct(
        Request $request,
        '.$name.'Repository $repository
    ){
        $this->request = $request;
        $this->repository = $repository;
    }
    
    public function index(Request $request) {
        AuthProvider::has($request, "'.$slug.'-browse");
        try {
            $payload = $request->all();
            $data = $this->repository->getList($request);
            if (isset($payload["csv"])) return $this->exportCSV($data);
            else return H_apiResponse($data);
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function findById(Request $request, $id) {
        AuthProvider::has($request, "'.$slug.'-read");
        try {
            $data = $this->repository->findById($request, $id);
            return H_apiResponse($data);
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function store(Request $request, $id = null) {
        if ($id) AuthProvider::has($request, "'.$slug.'-update");
        else AuthProvider::has($request, "'.$slug.'-create");
        try {
            $validate = $this->validateStore($request, $id);
            if($validate["result"]) {
                $data = $this->repository->store($request, $id);
                $msg = "succes saving data";
                if ($id) $msg = "success update data";
                return H_apiResponse($data, $msg);
            } else {
                return H_apiResponse(null, $validate["message"], 400);
            }
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function restore(Request $request, $id = null) {
        AuthProvider::has($request, "'.$slug.'-restore");
        try {
            $data = $this->repository->restore($request, $id);
            return H_apiResponse($data, "Data has successfully restored");
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function remove(Request $request, $id) {
        AuthProvider::has($request, "'.$slug.'-delete");
        try {
            $payload = $request->all();
            $data = $this->repository->remove($request, $id);
            $msg = "success deleted data";
            if(isset($payload["permanent"])) $msg = $msg . " permanently";
            return H_apiResponse($data, $msg);
        } catch (Exception $e){
            return H_apiResError($e);
        }
    }

    public function exportCSV($data) {
        $data = H_toArrayObject($data);
        $export = new ExportFromArray($data);

        $fileName = "'.$name.'-".H_getCurrentDate();
        return Excel::download($export, "".$fileName.".csv");
    }

    // Validator
    public function validateStore($request, $id = null) {
        try {
            $result = true;
            $message = "";
            $payload = $request->all();

            $validator = Validator::make( $request->all(),
                [
'.$objectKey.'
                ],
                [
'.$objectMessage.'
                ]
            );
            if ($validator->fails()) {
                $message = $validator->messages()->first();
                $result = false;
            }

            if ($id != null && empty($this->repository->findById($request, $id))) {
                $message = "Data not found";
                $result = false;
            }

            return [
                "result" => $result,
                "message" => $message,
            ];
        } catch (Exception $e){
            if(env("APP_DEBUG")) return H_apiResError($e);
            else {
                $msg = $e->getMessage();
                return H_apiResponse(null, $msg, 400);
            }
        }
    }

}
  
        ';

    $script = str_replace('"',"'", $script);    

    if($name != null || $name != ''){
        if (!file_exists($outputDir."Controller")) mkdir($outputDir."Controller", 0777, true); // generate folder module
        $create = fopen($outputDir."Controller/".$name."Controller.php", "w") or die("Unable to open file!");
        fwrite($create, $script);
        fclose($create);     
    }    
  

}



}