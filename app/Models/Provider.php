<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'option1',
        'option2',
        'option3',
        'option4',
        'option5',
        'option6',
        'api_id',
        'type',
        'status',
    ];
}
