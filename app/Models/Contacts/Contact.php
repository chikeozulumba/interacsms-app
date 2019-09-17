<?php

namespace App\Models\Contacts;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uuids;

class Contact extends Model
{
    use Uuids;
    public $incrementing = false;

    protected $fillable = [
        'user_id','group_id', 'first_name', 'last_name' ,'phone_1', 'phone_2', 'location'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
