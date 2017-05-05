<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrderGift extends Model
{
    protected $fillable = [
        'sales_order_id',
        'warehouse_id',
        'product_id',
        'batch_number',
        'production_date',
        'quantity',
        'gift'
    ];
}
