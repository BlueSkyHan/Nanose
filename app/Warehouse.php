<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'store_id',
        'name'
    ];

    public function address()
    {
        return $this->morphOne('App\Address', 'addressable');
    }

    public function store(){
        return $this->belongsTo('App\Store');
    }

    public function products(){
        return $this->belongsToMany('App\Product')->withPivot('quantity', 'batch_number', 'production_date')->withTimestamps();
    }

    public function gifts(){
        return $this->belongsToMany('App\Product', 'gift_warehouse', 'warehouse_id', 'product_id')->withPivot('quantity', 'batch_number', 'production_date')->withTimestamps();
    }
}
