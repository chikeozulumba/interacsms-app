<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uuids;

class Payment extends Model
{
    use Uuids;
    public $incrementing = false;

    // protected $fillable = [
    //     'user_id', 'trx_ref', 'reference', 'amount', 'status'
    // ];
    protected $fillable = [
            'user_id',
            'trx_ref',
            'reference',
            'amount',
            'units',
            'P_message',
            'P_status',
            'P_reference',
            'P_paid_at',
            'P_ipAddress',
            'P_authorization_code',
            'P_bin',
            'P_last4',
            'P_exp_month',
            'P_exp_year',
            'P_channel',
            'P_card_type',
            'P_bank',
            'P_country_code',
            'P_brand',
            'P_signature',
            'P_reusable',
            'P_customer_id',
            'P_customer_code',
            'P_created_at',
            'P_paidAt',
            'P_transaction_date',
    ];

    public function user ()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
