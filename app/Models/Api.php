<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Api extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'product', 'name', 'url', 'username', 'password', 'option1', 'code', 'status'
    ];
}
