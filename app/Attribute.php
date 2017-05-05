<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name'
    ];

    public function attributeValues(){
        return $this->hasMany('App\AttributeValue');
    }

    public function productTypes(){
        return $this->belongsToMany('App\ProductType')->withTimestamps();
    }
}
