<?php

function generateRepository($list, $outputDir = '') {

foreach($list as $item){

    $name = $item->name;
	$selector =  strtolower(splitUppercaseToUnderscore($name));
 
    $objectName = '';
    $no = 1;
    foreach ($item->column as $col) {
        $tab = '';
        if($no != 1) $tab = '            ';

        $setter = '$request["'.$col->name.'"];';
        if (isset($col->attributes)){
            foreach ($col->attributes as $value) {
                if ($value == 'nullable') $setter = 'H_handleRequest($request, "'.$col->name.'");';
                break;
            }
        }
        if($col->name != 'id') $objectName .= $tab . '$data->'.$col->name.' = '.$setter.' ' ."\r\n";
    $no++;}
 
    $repoName = "App\Repositories\ ".$name."Repository;";
    $repoName = fixUseName($repoName);

    $modelName = "App\Models\ ".$name.";";
    $modelName = fixUseName($modelName);

    $validator = "App\Validators\ ".$name."Validator;";
    $validator = fixUseName($validator);

    $dq = '"'; // double quotes

    $script = '<?php

namespace App\Repositories;

use Laravel\Lumen\Application;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use '.$repoName.'
use '.$modelName.'
use '.$validator.'
use Exception;

use App\Providers\HelperProvider;

use App\Traits\StandardRepo;

class '.$name.'RepositoryEloquent extends BaseRepository implements '.$name.'Repository
{
    use StandardRepo;

    public function __construct(
        Application $app
	){
		parent::__construct($app);
    }


    public function model() {
        return '.$name.'::class;
    }

    public function store($raw_request, $id = null, $customRequest = null) {
        try {
 
            if ($customRequest === null) $request = $raw_request->all();
            else $request = $customRequest;

            $data = $this->initModel($id);

            //storing defined property    
'.$objectName.'
            
            $data->save();
            return $data;

        } catch (Exception $e){ 
            throw new Exception($e->getMessage());
        } 
    }

    // add your customize function

}
';

        $script = str_replace('"',"'", $script); 

    if($name != null || $name != ''){    
        if (!file_exists($outputDir."Repositories")) mkdir($outputDir."Repositories", 0777, true); // generate folder module
        $create = fopen($outputDir."Repositories/".$name."RepositoryEloquent.php", "w") or die("Unable to open file!");
        fwrite($create, $script);
        fclose($create);  
    }

   // $contract = "Prettus\Repository\Contracts\ ".$name."Interface;";
    $contract = "Prettus\Repository\Contracts\RepositoryInterface;";
    $contract = fixUseName($contract); 

    $scriptInterface = '<?php

namespace App\Repositories;

use '.$contract.'

/**
 * Interface '.$name.'Repository.
 *
 * @package namespace App\Repositories;
 */
interface '.$name.'Repository extends RepositoryInterface
{
    public function initModel($id = null);
    public function totalData($request);
    public function findAll($request, $raw = false);
    public function findById($request, $id);
    public function getList($request);
    public function store($request, $id = null, $customRequest = null);
    public function remove($request, $id);
    public function restore($request, $id);
}
';  
    if($name != null || $name != ''){
        $create2 = fopen($outputDir."Repositories/".$name."Repository.php", "w") or die("Unable to open file!");
        fwrite($create2, $scriptInterface);
        fclose($create2);  
    }


}



}