<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function products(){
        return $this->hasMany('App\Product');
    }

    public function attributes(){
        return $this->belongsToMany('App\Attribute')->withTimestamps();
    }
}
