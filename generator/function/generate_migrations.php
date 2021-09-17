<?php

function generateMigrations ($list, $outputDir = '') {

$scriptMigrations = '';
$reverseList = '';
$schema  = '';
foreach($list as $item){

    // print_r($item); echo "<hr>";
    logInfoGenerate($item);
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

function logInfoGenerate_ ($item) {
    echo "<b style='font-size:23px;' >".$item->name . "</b>  <small style='padding:2px; color:#fff; background:green' > &nbsp; Generated &nbsp; </small><br>";
    $code = '';
    foreach ($item->column as $key => $col) {
        $attr = '';
        $enumList = '';
        $default = '';
        $length = '';
        $belongsTo = '';

        if (isset($col->attributes)) {
            $attr = "".json_encode($col->attributes)."";
            $attr = str_replace('"','', $attr);
        }

        if (isset($col->enum_list)) {
            $enumList = $col->enum_list;
        }
        if (isset($col->default)) $default = 'default('.$col->default.')';

        if (isset($col->length)) $length .= $col->length;
        if (isset($col->length2)) $length .= ','.$col->length2;
        if ($length !== '') $length =  '('.$length.')';
        
        if (isset($col->belongsTo)) $belongsTo .= '(Foreign: ' . $col->belongsTo->model.')';

        $colType = $col->type;
        if ($col->name === 'id') $colType = $col->type . ' PRIMARY KEY (Auto Increments)';

        // format table
        // $code .= "<tr>
        // <td><b>".$col->name . "</b></td> 
        // <td>: <span style='font-style: italic;color: #955504;letter-spacing: 0.5;'>". $col->type."</span></td> 
        // <td>&nbsp; ".$attr."</td>
        // </tr>";
        // format text
        $code .='- '.$col->name . ' : '.$colType. ''.$length. ' '.$belongsTo.' '.$enumList.' '.$default.' <br>';
    }
    echo "<div style='margin:5px; background:#ccc; padding:5px; border-radius:5px; font-family:consolas;line-height: 1.2em;'>$code</div>";
    // echo "<div style='margin:5px; background:#ccc; padding:5px; border-radius:5px;'><table class='table'><tbody>$code</tbody></table></div>";
    echo "<hr>";

}

function logInfoGenerate ($item) { // format table
    $nameCase =  strtolower(splitUppercaseToStrip($item->name));
    $headingStyle = 'font-size:18px;';
    echo "<strong style='$headingStyle'>Module Name &nbsp; : ".$item->name . "</strong><br>";
    echo "<strong style='$headingStyle'>Table Name &nbsp; &nbsp; &nbsp; : ".$nameCase . "</strong><br><br>";
    $colorSoft = '#9b9a9a';
    $colorPrimary = '#0878ac';
    $code = '';
    $styleTh = 'text-align:center; color: #fff; background:'.$colorPrimary.'; padding: 5px 15px 5px 15px;';
    $thead = "<tr>
        <th style='$styleTh' >Name</th>
        <th style='$styleTh' >Type</th>
        <th style='$styleTh' >Length</th>
        <th style='$styleTh' >Default</th>
        <th style='$styleTh' >Attributes</th>
        </tr>";

    foreach ($item->column as $key => $col) {
        $attr = '';
        $enumList = '';
        $default = '-';
        $length = '';
        $belongsTo = '';

        if (isset($col->attributes)) {
            $attr = "".json_encode($col->attributes)."";
            $attr = str_replace('"','', $attr);
        }

        if (isset($col->enum_list)) {
            $enumList = $col->enum_list;
        }
        if (isset($col->default)) $default = str_replace("'","",$col->default);

        if (isset($col->length)) $length .= $col->length;
        if (isset($col->length2)) $length .= ','.$col->length2;
        if ($length !== '') $length =  ''.$length.'';
        else $length =  '<small><em>default</em></small>';
        
        
        $colType = $col->type;
        if ($col->name === 'id') $colType = $col->type . ' <br><small style="color:'.$colorSoft.'">~ PRIMARY KEY (Auto Increments)</small>';
        
        if (isset($col->belongsTo)) $colType = $colType . '<br><small style="color:'.$colorSoft.'">~ Foreign : '.$col->belongsTo->model.' </small>';
        if (isset($col->hasMany)) $colType = $colType . '<br><small style="color:'.$colorSoft.'">~ HasMany : '.$col->hasMany->model.' </small>';

        // format table
        $attr = str_replace("[","",$attr);
        $attr = str_replace("]","",$attr);
        $styleTr = 'padding: 5px 15px 5px 15px; border-bottom: 1px solid #ccc;';
        $code .= "<tr>
        <td style='$styleTr'><b>".$col->name . "</b></td> 
        <td style='$styleTr '> <span style='font-style: italic;color: $colorPrimary;letter-spacing: 0.5;'>". $colType."</span></td> 
        <td style='$styleTr text-align:center;' >&nbsp; ".$length."</td>
        <td style='$styleTr text-align:center;' >&nbsp; ".$default."</td>
        <td style='$styleTr '>&nbsp; ".$attr."</td>
        </tr>";
        // format text
        // $code .='- '.$col->name . ' : '.$colType. ''.$length. ' '.$belongsTo.' '.$enumList.' '.$default.' <br>';

        // $host = '<span style="color:'.$colorSoft.'">{host}</span>';
        $host = '';
        $index = "$host/$nameCase";
        $getById = "$host/$nameCase/{id}";
        $store = "$host/$nameCase/";
        $delete = "$host/$nameCase/{id}";
        $restore = "$host/$nameCase/{id}";

        $apiRoute = "<ul>
            <li><strong style='color: $colorPrimary;'>Index</strong> [GET] : $index</li>
            <li><strong style='color: $colorPrimary;'>Get Single</strong> [GET] : $getById</li>
            <li><strong style='color: $colorPrimary;'>Insert</strong> [POST] : $store</li>
            <li><strong style='color: $colorPrimary;'>Update</strong> [PUT / POST </b><small style='color:$colorSoft'>(if have file upload)</small>] : $store{id}</li>
            <li><strong style='color: $colorPrimary;'>Delete</strong> [DELETE] : $delete</li>
            <li><strong style='color: $colorPrimary;'>Restore</strong> [PUT] : $restore</li>
        </ul>";

        $uiRoute = "<ul>
            <li><strong style='color: $colorPrimary;'>Index</strong>  &nbsp; &nbsp; &nbsp;: /$nameCase</li>
            <li><strong style='color: $colorPrimary;'>Create</strong> &nbsp; &nbsp;: /$nameCase/form</li>
            <li><strong style='color: $colorPrimary;'>Update</strong> &nbsp;  : /$nameCase/form/{id}</li>
            <li><strong style='color: $colorPrimary;'>Detail</strong> &nbsp; &nbsp; : /$nameCase/view/{id}</li>
        </ul>";
    }

    

    // echo "<div style='margin:5px; background:#ccc; padding:5px; border-radius:5px; font-family:consolas;line-height: 1.2em;'>$code</div>";
    echo "<div style='margin:5px;'><table class='table' style='border-collapse: collapse;' ><thead>$thead</thead><tbody>$code</tbody></table></div>";
    echo "<br><br>";
    echo "<strong style='$headingStyle'>API Route Information</strong><br>";
    echo "$apiRoute <br>";
    echo "<strong style='$headingStyle'>Front-End Route Information</strong><br>";
    echo "$uiRoute";
    echo "<br><hr><br><br>";
    echo "<strong>#Rules</strong><br><br> - <br><br>";
    echo "<strong>#Notes</strong><br><br> - <br><br>";

}