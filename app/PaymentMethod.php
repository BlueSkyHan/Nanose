<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name'
    ];

    public function salesOrders(){
        return $this->hasMany('App\SalesOrder');
    }
}
