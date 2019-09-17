<?php

namespace App\Models\Contacts;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uuids;

class Group extends Model
{
    use Uuids;
    public $incrementing = false;

    protected $fillable = [
        'user_id','name', 'description'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
