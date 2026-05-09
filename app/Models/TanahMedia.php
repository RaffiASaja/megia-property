<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanahMedia extends Model
{
    protected $table = 'tanah_media';

    protected $guarded = [];

    public function tanah()
    {
        return $this->belongsTo(Tanah::class);
    }
}
