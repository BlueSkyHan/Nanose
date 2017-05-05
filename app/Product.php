<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_type_id',
        'name',
        'price'
    ];

    public function productType(){
        return $this->belongsTo('App\ProductType');
    }

    public function attributeValues(){
        return $this->belongsToMany('App\AttributeValue')->withTimestamps();
    }

    public function warehouses(){
        return $this->belongsToMany('App\Warehouse')->withPivot('quantity', 'batch_number', 'expiration_date')->withTimestamps();
    }

    public function salesOrder(){
        return $this->belongsToMany('App\SalesOrder')->withPivot('quantity');
    }
}
