<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberFrom extends Model
{
    protected $fillable = [
        'name'
    ];

    public function members(){
        return $this->hasMany('App\Member');
    }
}
