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
 * @property unsignedBigInteger $user_id 
 * @property boolean $is_read 
 * @property string $title 
 * @property text $description 
 * @property string $type 

 */
class UserNotifications extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes, GlobalRelations;

    /**
     * Table Configuration
     * @var string
     */
    protected $table = 'user_notifications';
    protected $primaryKey = 'id';

    /**
     * List of allowed column to insert / update 
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'is_read', 
        'title', 
        'description', 
        'type',
        'link_path',
        'link_params', 
        'created_by', 
        'updated_by', 
        'deleted_by'
    ];

    // disabled timestamps data
    public $timestamps = true;

    // disable update col id
    protected $guarded = ['id'];

    protected $casts = [ 
        'user_id' => 'integer',
        'is_read' => 'boolean',
        'link_params' => 'json',

    ];

    public function Columns() {
        return $this->fillable;
    }

}        
        