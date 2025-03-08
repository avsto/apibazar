<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = ['scheme_id', 'provider_id', 'type', 'value', 'parent_value'];

    public function scheme()
    {
        return $this->belongsTo('App\Models\Scheme');
    }

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
}