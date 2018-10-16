<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $prospect_id
 * @property string $user_id
 */
class Code extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'amulex_codes';
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
    protected $fillable = ['id','serial_code', 'activated_code', 'type', 'activated'];

    public function type(){
        return $this->belongsTo(Certs::class, 'type', 'id');
       // return $this->belongsTo(Certs::class, 'type', 'id');
    }
    public function store(){
        return $this->belongsTo(CertsStore::class, 'activated', 'id');

       // return $this->belongsTo(CertsStore::class, 'activated', 'id');
    }

}


