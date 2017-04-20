<?php

namespace turnip;

use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    public function orders()
    {
        return $this->belongsTo('turnip\Orders', 'order_id');
    }

    public function products()
    {
        return $this->belongsTo('turnip\Products', 'product_id');
    }
}
