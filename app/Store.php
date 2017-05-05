<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name'
    ];

    public function phone()
    {
        return $this->morphOne('App\Phone', 'phoneable');
    }

    public function address()
    {
        return $this->morphOne('App\Address', 'addressable');
    }

    public function warehouses(){
        return $this->hasMany('App\Warehouse');
    }

    public function employees(){
        return $this->hasMany('App\Employee');
    }

    public function salesOrders(){
        return $this->hasManyThrough('App\SalesOrder', 'App\Employee');
    }
}
