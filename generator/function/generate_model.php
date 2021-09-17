<?php

function generateModel ($list, $outputDir = '') {

foreach($list as $item){

    $belongsTo = '';
    $hasMany = '';
    $searchRelations = '';

    $name = $item->name;
    $selector =  strtolower(splitUppercaseToUnderscore($name));

    $property = '';

    foreach ($item->column as $col) {
        $property .= ' * @property '.$col->type.' $'.$col->name.' ' ."\r\n";

        // get blongsTo
        if (isset($col->belongsTo)) {
            $bt = $col->belongsTo;
            if (isset($bt->model)) {
                $btName = (isset($bt->name)) ? $bt->name : $bt->model;
                $fk = (isset($bt->foreign)) ? $bt->foreign : $col->name;
                $fk = ($fk == '_self') ? $col->name : $fk;

                $fk2 = (isset($bt->foreign2)) ? $bt->foreign2 : 'id';
                $fk2 = ($fk2 == '_self') ? 'id' : $bt->foreign2;

                $belongsTo .= '    public function '.$btName.'() {'."\r\n";
                $belongsTo .= '        return $this->belongsTo('.$bt->model.'::class, "'.$fk.'", "'.$fk2.'")->withTrashed();'."\r\n";
                $belongsTo .= '    }'."\r\n\r\n";

            }

            $searchRelations .= "            '".$bt->name."' => (new ".$bt->model."())->Columns(),"."\r\n";
        }

        // get hasMany
        if (isset($col->hasMany)) {
            $hm = $col->hasMany;
            if (isset($hm->model)) {
                $hmName = (isset($hm->name)) ? $hm->name : $hm->model;
                $fk = (isset($hm->foreign)) ? $hm->foreign : $col->name;
                $fk = ($fk == '_self') ? $col->name : $fk;

                $fk2 = (isset($hm->foreign2)) ? $hm->foreign2 : 'id';

                $hasMany .= '    public function '.$hmName.'() {'."\r\n";
                $hasMany .= '        return $this->hasMany('.$hm->model.'::class, "'.$fk2.'", "'.$fk.'")->withTrashed();'."\r\n";
                $hasMany .= '    }'."\r\n\r\n";

            }
        }



    }

    $last = count($item->column) - 1;
    $fillable = '';
    $casts = '';

    foreach ($item->column as $index => $value) {
        $coma = ', 
        ';
        if($index == $last) $coma = '';
        if($value->name !== 'id') $fillable .= '"'.$value->name.'"'.$coma ;

        $type = 'string';
        if ($value->type == 'decimal') $type = 'real';
        if ($value->type == 'double') $type = 'real';
        if ($value->type == 'json') $type = 'array';
        if ($value->type == 'boolean' || $value->type == 'tinyInteger') $type = 'boolean';
        if ($value->type == 'integer' || $value->type == 'unsignedBigInteger') $type = 'integer';

        if($value->name !== 'id'
        && $value->type !== 'string'
        && $value->type !== 'text'
        && $value->type !== 'longtext'
        && $value->type !== 'enum'
        && $value->name !== 'date'
        && $value->name !== 'dateTime'
        && $value->name !== 'created_by'
        && $value->name !== 'updated_by'
        && $value->name !== 'deleted_by'
        )  $casts .="        '".$value->name."' => '$type',"."\r\n";

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

use App\Traits\GlobalRelations;


/**
'.$property.'
 */
class '.$name.' extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes, GlobalRelations; 

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

    
    protected $casts = [
        // casting type
'.$casts.'
    ];

    public function Columns() {
        return $this->fillable;
    }

    public function searchRelations() {
        return [
'.$searchRelations.'
        ];
    }

'.$belongsTo.'

'.$hasMany.'

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