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
 * @property unsignedBigInteger $user_id 
 * @property text $token 
 * @property string $ip 
 * @property string $agent 
 * @property string $platform 

 */
class UserSessions extends Model
{
    use Authenticatable, Authorizable, HasFactory;
    use SoftDeletes;

    /**
     * Table Configuration
     * @var string
     */
    protected $table = 'user_sessions';
    protected $primaryKey = 'id';

    /**
     * List of allowed column to insert / update 
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'token', 
        'ip', 
        'agent', 
        'platform', 
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

    ];

    public function Columns() {
        return $this->fillable;
    }

}        
        