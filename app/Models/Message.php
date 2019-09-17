<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uuids;


class Message extends Model
{
    use Uuids;
    public $incrementing = false;

    protected $fillable = [
        'user_id','sender', 'content', 'pages' ,'volume', 'recipients', 'cost', 'brodcast_time', 'permission', 'sent', 'format', 'type', 'flash'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\Message\Log', 'message_id');
    }
}
