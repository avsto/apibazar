<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'number',
        'mobile',
        'provider_id',
        'api_id',
        'amount',
        'charge',
        'profit',
        'gst',
        'tds',
        'apitxnid',
        'txnid',
        'payid',
        'refno',
        'description',
        'option1',
        'option2',
        'option3',
        'option4',
        'status',
        'user_id',
        'credit_by',
        'rtype',
        'via',
        'adminprofit',
        'balance',
        'trans_type',
        'product',
        'service',
        'api_profit',
        'api_charge',
        'api_gst',
        'api_tds',
        'wallet_type',
    ];
}
