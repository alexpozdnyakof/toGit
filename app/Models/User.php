<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $username
 * @property int $v_shtate
 * @property int $glavniy
 * @property string $email
 * @property string $user_role
 */
class User extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'pro_users';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'username', 'v_shtate', 'glavniy', 'email', 'user_role'];


    public function clients()
    {
        return $this->hasMany(Client::class, 'user_id', 'id');
      //  return Client::whereIn('user_id', $id)->get();
    }


}

