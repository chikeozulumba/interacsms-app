<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uuids;

class Log extends Model
{
    use Uuids;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'message_id',
        'bulk_id',
        'each_id',
        'volume',
        'phone_number',
        'network',
        'geolocation',
        'delivery_status',
        'time_zone',
        'is_deliveryReport',
        'mccMnc',
        'title',
        'message',
        'sentAt',
        'doneAt',
        'smsCount',
        'price_per_message',
        'price_currency',
        'delivery_status_groupId',
        'delivery_status_groupName',
        'delivery_status_id',
        'delivery_status_name',
        'delivery_status_description',
        'delivery_error_groupId',
        'delivery_error_groupName',
        'delivery_error_id',
        'delivery_error_name',
        'delivery_error_description',
        'delivery_error_permanent'
    ];

    public function message()
    {
        return $this->belongsTo('App\Models\Message', 'message_id');
    }
}
