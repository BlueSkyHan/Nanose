<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'district_id',
        'line',
        'postcode'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function district(){
        return $this->belongsTo('App\District', 'district_id', 'district_id');
    }
}
