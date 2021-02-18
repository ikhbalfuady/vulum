<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;


/**
 * @property bigIncrements $id 
 * @property string $name 
 * @property string $username 
 * @property string $password 
 * @property string $email 
 * @property text $picture 
 * @property unsignedBigInteger $role_id 
 * @property unsignedBigInteger $menu_id 
 * @property tinyInteger $active 

 */
class Users extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes;

    /**
     * Table Configuration
     * @var string
     */
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * List of allowed column to insert / update 
     * @var array
     */
    protected $fillable = [
        'name', 
        'username', 
        'password', 
        'email', 
        'picture', 
        'role_id', 
        'menu_id', 
        'active'
    ];

    // disabled timestamps data
    public $timestamps = true;

    // disable update col id
    protected $guarded = ['id'];
    protected $hidden = ['password'];

    public function Columns() {
        return $this->fillable;
    }


    public function Role()
    {
        return $this->belongsTo('App\Models\Roles', 'role_id', 'id');
    }

    public function Roles()
    {
        return $this->hasMany('App\Models\RolePermissions', 'role_id', 'role_id')->with(['Permissions']);
    }

    public function Menu()
    {
        return $this->belongsTo('App\Models\MasterMenus', 'menu_id', 'id');
    }

    public function Menus()
    {
        return $this->hasMany('App\Models\Menus', 'master_menu_id', 'menu_id')->with(['MenuItems']);
    }

}        
        