<?php

namespace turnip;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'price',
        'price2',
        'unit'
    ];

    public function orders()
    {
        return $this->hasMany('turnip\OrderProducts', 'product_id');
    }
}
