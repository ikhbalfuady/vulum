<?php

function generateModel ($list, $outputDir = '') {

foreach($list as $item){

    $name = $item->name;
    $selector =  strtolower(splitUppercaseToUnderscore($name));

    $property = '';
    foreach ($item->column as $col) {
        $property .= ' * @property '.$col->type.' $'.$col->name.' ' ."\r\n";
    }

    $last = count($item->column) - 1;
    $fillable = '';

    foreach ($item->column as $index => $value) {
        $coma = ', 
        ';
        if($index == $last) $coma = '';
        if($value->name !== 'id') $fillable .= '"'.$value->name.'"'.$coma ;

    }

    $script = '<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

use App\Traits\RelationActionBy;


/**
'.$property.'
 */
class '.$name.' extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes, RelationActionBy; 

    /**
     * Table Configuration
     * @var string
     */
    protected $table = "'.$selector.'";
    protected $primaryKey = "id";

    /**
     * List of allowed column to insert / update 
     * @var array
     */
    protected $fillable = [
        '. $fillable.'
    ];

    // disabled timestamps data
    public $timestamps = true;

    // disable update col id
    protected $guarded = ["id"];

    public function Columns() {
        return $this->fillable;
    }

}        
        ';

    $script = str_replace('"',"'", $script); 

    if($name != null || $name != ''){
        if (!file_exists($outputDir."Models")) mkdir($outputDir."Models", 0777, true); // generate folder module
        $create = fopen($outputDir."Models/$name.php", "w") or die("Unable to open file!");
        fwrite($create, $script);
        fclose($create);     
    }  

}



}