<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanah extends Model
{
    protected $table = 'tanahs';

    protected $guarded = []; 

    protected $casts = [
        'foto' => 'array',
        'video' => 'array',
    ];
}
