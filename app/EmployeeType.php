<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function employees(){
        return $this->hasMany('App\Employee');
    }
}
