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
 * @property string $name 

 */
class MasterMenus extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes, GlobalRelations;

    /**
     * Table Configuration
     * @var string
     */
    protected $table = 'master_menus';
    protected $primaryKey = 'id';

    /**
     * List of allowed column to insert / update 
     * @var array
     */
    protected $fillable = [
        'name', 
        'created_by', 
        'updated_by', 
        'deleted_by'
    ];

    // disabled timestamps data
    public $timestamps = true;

    // disable update col id
    protected $guarded = ['id'];

    public function Columns() {
        return $this->fillable;
    }

    public function Users()
    {
        return $this->hasMany('App\Models\Users', 'menu_id', 'id');
    }

    public function menus() {
        return $this->hasMany(Menus::class, 'master_menu_id');
    }
}        
        