<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $prospect_id
 * @property string $user_id
 */
class CertsStore extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'amulex_activated';
    public $timestamps = false;
    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    //public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'company', 'tax', 'created', 'updated', 'type', 'status', 'ceo', 'phone', 'email', 'seller'];
    public function certs(){
        return $this->belongsTo(Certs::class, 'type', 'id');
    }
    public function code(){
        return $this->belongsTo(Code::class, 'id', 'activated');
    }



}


