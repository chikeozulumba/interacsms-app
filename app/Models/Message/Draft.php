<?php

namespace App\Models\Message;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uuids;

class Draft extends Model
{
    use Uuids;
    public $incrementing = false;

    protected $fillable = [
        'user_id','sender', 'message', 'contacts_list', 'standard', 'flash', 'isDeleted'
    ];
}
