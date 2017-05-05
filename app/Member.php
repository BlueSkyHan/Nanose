<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_from_id',
        'name',
        'gender',
        'birth_date',
        'referrer',
        'health_status'
    ];

    public function phones()
    {
        return $this->morphMany('App\Phone', 'phoneable');
    }

    public function addresses()
    {
        return $this->morphMany('App\Address', 'addressable');
    }

    public function memberFrom(){
        return $this->belongsTo('App\MemberFrom');
    }

    public function salesOrders(){
        return $this->hasMany('App\SalesOrder');
    }
}
