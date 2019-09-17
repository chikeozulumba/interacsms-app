<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\Uuids;

class User extends Authenticatable
{
    use Notifiable;
    use Uuids;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function message()
    {
        return $this->hasMany('App\Models\Message', 'user_id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Contacts\Contact', 'user_id');
    }

    public function groups()
    {
        return $this->hasMany('App\Models\Contacts\Group', 'user_id')->orderBy('updated_at', 'desc')->where('isDeleted', '=', 0);
    }

    public function notifications ()
    {
        return $this->hasMany('App\Models\Notifications', 'user_id');
    }

    public function logs()
    {
        return $this->hasManyThrough(
            'App\Models\Message\Log',
            'App\Models\Message',
            'user_id',
            'message_id',
            'id',
            'id'
        );
    }

    public function payment ()
    {
        return $this->hasMany('App\Models\Payment', 'user_id');
    }
}
