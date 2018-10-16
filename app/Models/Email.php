<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $prospect_id
 * @property string $user_id
 */
class Email extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'pro_emails';
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
    protected $fillable = ['to', 'subject','body', 'attachments', 'created'];
    protected $primaryKey = 'id';



}


