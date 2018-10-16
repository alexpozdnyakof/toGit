<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $prospect_id
 * @property string $user_id
 */
class Client extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'crm_clients_manager';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    //public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['id','prospect_id', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }


}


