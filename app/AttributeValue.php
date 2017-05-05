<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value'
    ];

    public function attribute(){
        return $this->belongsTo('App\Attribute');
    }

    public function products(){
        return $this->belongsToMany('App\Product')->withTimestamps();
    }
}
