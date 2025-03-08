<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAepsDetails extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'merchantLoginId',
        'merchantpassword',
        'merchantLoginPin',
        'merchantName',
        'merchantPhoneNumber',
        'emailId',
        'merchantPinCode',
        'merchantCityName',
        'merchantAddress',
        'merchantState',
        'userPan',
        'aadharNumber',
        'latitude',
        'longitude',
        'otp_status',
        'biometric_status',
        'primaryKeyId',
        'encodeFPTxnId',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'merchantLoginId', 'userid');
    }

}
