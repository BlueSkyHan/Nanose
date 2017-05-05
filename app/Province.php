<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'province_id',
        'name'
    ];

    public function cities(){
        return $this->hasMany('App\City', 'province_id', 'province_id');
    }
}
