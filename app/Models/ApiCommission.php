<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCommission extends Model
{
    //
    use HasFactory;
    protected $fillable = ['api_id', 'provider_id', 'type', 'value'];

    public function api()
    {
        return $this->belongsTo('App\Models\Api');
    }

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
}
