<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_type_id',
        'store_id',
        'user_id',
        'name',
        'photo_path'
    ];

    protected $uploads = "dist/img/employee/";

    public function getPhotoPathAttribute($value){
        return $this->uploads . $value;
    }

    public function employeeType(){
        return $this->belongsTo('App\EmployeeType');
    }

    public function store(){
        return $this->belongsTo('App\Store');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function salesOrders(){
        return $this->belongsToMany('App\SalesOrders')->withPivot('created_at');
    }
}
