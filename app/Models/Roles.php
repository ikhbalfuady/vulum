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
 * @property string $slug 

 */
class Roles extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes, GlobalRelations;

    /**
     * Table Configuration
     * @var string
     */
    protected $table = 'roles';
    protected $primaryKey = 'id';

    /**
     * List of allowed column to insert / update 
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'slug',
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

}        
        