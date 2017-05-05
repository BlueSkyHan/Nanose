<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'district_id',
        'name',
        'city_id'
    ];

    public function city(){
        return $this->belongsTo('App\City', 'city_id', 'city_id');
    }

    public function addresses(){
        return $this->hasMany('App\Address');
    }
}
