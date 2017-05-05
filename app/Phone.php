<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'number'
    ];

    public function phoneable()
    {
        return $this->morphTo();
    }
}
