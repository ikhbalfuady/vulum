<?php

function generateMigrations ($list, $outputDir = '') {

$scriptMigrations = '';
$reverseList = '';
$schema  = '';
foreach($list as $item){

    print_r($item); echo "<hr>";
    if(!isset($item->name))  die();
 
	$selector =  strtolower(splitUppercaseToUnderscore($item->name));

    if (isset($item->loging_user) && $item->loging_user == true) {
        $item->column = enableLogingUser($item->column);
    }

    $columList = '';
    $no = 1;
    foreach ($item->column as $col) {
        $tab = '            ';
        if($no == 1) $tab = '    ';

        $basic = $tab.'$table->'.$col->type.'("'.$col->name.'"';
        if (isset($col->length))  $basic =  $basic . ', '.$col->length;
        if (isset($col->length2))  $basic =  $basic . ', '.$col->length2;
        if ($col->type == 'enum' && isset($col->enum_list))  $basic =  $basic . ', '.$col->enum_list;

        $basic = $basic . ')'; // wrapping

        $attr = '';
        if (isset($col->attributes)) {
            foreach ($col->attributes as $value) {
                $attr .= '->'.$value.'()';
            }
        }
        $basic = $basic . $attr; // merging attributes

        if (isset($col->default)) $basic = $basic . '->default('.$col->default.')';

        //fixing 
        $basic = $basic . ';' ."\r\n";

        $columList .= $basic;

    $no ++;
    }

    $fulltext = '';
    $fulltext_list = '';
    $no = 1;
    foreach ($item->column as $key => $col) {
        if (isset($col->fulltext) && $col->fulltext == true) {
            if ($no == 1) $fulltext_list .= $col->name;
            else $fulltext_list .= ','.$col->name;
            $no++;
        }
        
    }

    if ($fulltext_list != '') {
        $fulltext = 'DB::statement("ALTER TABLE 
        '.$selector.' ADD FULLTEXT 
        '.$selector.'_fulltext('.$fulltext_list.')
        "); ';
    }

    $loging_date = '            $table->timestamps(0);
            $table->softDeletes("deleted_at");';

 
    $reverseList .= '        Schema::dropIfExists("'.$selector.'"); ' ."\r\n";

    $schema .= '        Schema::create("'.$selector.'", function (Blueprint $table) {' ."\r\n";
    $schema .= '        '. $columList .'' ."\r\n";
    $schema .= ''. $loging_date .'' ."\r\n";
    $schema .= '        });' ."\r\n\r\n";
    $schema .= '        '. $fulltext .'' ."\r\n";

    $scriptMigrations = '<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FirstInit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        '. $schema .'
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    '. $reverseList .'
    }
}        
        ';

}

$scriptMigrations = str_replace('"',"'", $scriptMigrations);
// $fname = gmdate("Y_m_d_His", time() + 60 * 60 * 7) . "_update_" . gmdate("YmdHis", time() + 60 * 60 * 7);
$fname = '2020_11_04_184938_first_init';
$createMigrations = fopen($outputDir."$fname.php", "w") or die("Unable to open file!");
fwrite($createMigrations, $scriptMigrations);
fclose($createMigrations);   

}