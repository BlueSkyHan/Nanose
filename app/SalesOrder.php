<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'employee_id',
        'member_id',
        'sales_channel_id',
        'payment_method_id',
        'delivery_method_id',
        'transaction_date',
        'receiver',
        'note',
        'total',
        'actual_total'
    ];

    public function phone(){
        return $this->morphOne('App\Phone', 'phoneable');
    }

    public function address(){
        return $this->morphOne('App\Address', 'addressable');
    }

    public function salesOrderProducts(){
        return $this->hasMany('App\SalesOrderProduct');
    }

    public function salesOrderGifts(){
        return $this->hasMany('App\SalesOrderGift');
    }

    public function employee(){
        return $this->belongsTo('App\Employee');
    }

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function salesChannel(){
        return $this->belongsTo('App\SalesChannel');
    }

    public function paymentMethod(){
        return $this->belongsTo('App\PaymentMethod');
    }

    public function deliveryMethod(){
        return $this->belongsTo('App\DeliveryMethod');
    }
}
