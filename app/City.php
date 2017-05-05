<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'city_id',
        'name',
        'province_id'
    ];

    public function province(){
        return $this->belongsTo('App\Province', 'province_id', 'province_id');
    }

    public function districts(){
        return $this->hasMany('App\District', 'city_id', 'city_id');
    }
}
