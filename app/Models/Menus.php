<?php

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
 * @property bigIncrements $id 
 * @property unsignedBigInteger $parent_id 
 * @property unsignedBigInteger $menu_item_id 
 * @property unsignedBigInteger $master_menu_id 

 */
class Menus extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes, GlobalRelations;

    /**
     * Table Configuration
     * @var string
     */
    protected $table = 'menus';
    protected $primaryKey = 'id';

    /**
     * List of allowed column to insert / update 
     * @var array
     */
    protected $fillable = [
        'parent_id', 
        'menu_item_id', 
        'master_menu_id',
        'overline',
        'ordering',
        'created_by', 
        'updated_by', 
        'deleted_by'
    ];

    // disabled timestamps data
    public $timestamps = true;

    // disable update col id
    protected $guarded = ['id'];

    protected $casts = [
        'parent_id' => 'integer',
        'menu_item_id' => 'integer',
        'master_menu_id' => 'integer',
        'ordering' => 'integer',
    ];

    public function Columns() {
        return $this->fillable;
    }

    public function Detail()
    {
        return $this->belongsTo('App\Models\MenuItems', 'menu_item_id', 'id');
    }

}        
        