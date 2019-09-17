<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uuids;

class Notifications extends Model
{
    use Uuids;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'message_id',
        'time_log',
        'type_message',
        'title',
        'message',
        'isRead',
        'isAllowed'
    ];

    protected $table = 'message_notifications';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
