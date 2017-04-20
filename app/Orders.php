<?php

namespace turnip;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [
        'client_id',
        'total',
        'ordered_date'
    ];

    public function clients()
    {
        return $this->belongsTo('turnip\Clients', 'client_id');
    }

    public function orderProducts()
    {
        return $this->hasMany('turnip\OrderProducts', 'order_id')->with('products')->orderBy('product_id');
    }

    public function scopeDailySums($query, $id = null, $start, $end)
    {
        $results = $query->groupBy('ordered_date')
                         ->selectRaw('sum(total) as sum, ordered_date')
                         ->whereBetween('ordered_date', [$start, $end])
                         ->orderBy('ordered_date', 'ASC');
        if ($id)
        {
            $query->where('client_id', $id);
        }
        return $results;
    }

    public function scopeMonthlySums($query, $id = null, $is_small = false, $start, $end)
    {
        if ($is_small)
        {
            $results = $query->selectRaw('products.name, products.price2 as price, products.unit, sum(order_products.quantity) as total_quantity, sum(order_products.total) as sum');
        }
        else
        {
            $results = $query->selectRaw('products.name, products.price, products.unit, sum(order_products.quantity) as total_quantity, sum(order_products.total) as sum');
        }
        $query->join('order_products', 'order_products.order_id', '=', 'orders.id')
                         ->join('products', 'order_products.product_id', '=', 'products.id')
                         ->whereBetween('ordered_date', [$start, $end])
                         ->groupBy('order_products.product_id')
                         ->orderBy('order_products.product_id', 'ASC');
        if ($id)
        {
            $query->where('client_id', $id);
        }
        
        return $results;
    }

    public function scopeClientSums($query, $id = null, $start, $end)
    {
        $results = $query->groupBy('client_id')
                         ->selectRaw('sum(total) as sum')
                         ->whereBetween('ordered_date', [$start, $end]);
        if ($id)
        {
            $query->where('client_id', $id);
        }
        return $results;
    }

    public function scopeMonthlyOrders($query, $id = null, $start, $end)
    {
        $results = $query->selectRaw(
                            'ordered_date, products.id AS product_id, products.name, SUM(order_products.quantity) AS quantity, SUM(order_products.total) AS total'
                        )
                        ->join('order_products', 'order_products.order_id', '=', 'orders.id')
                        ->join('products', 'order_products.product_id', '=', 'products.id')
                        ->join('clients', 'orders.client_id', '=', 'clients.id')
                        ->whereBetween('ordered_date', [$start, $end])
                        ->groupBy('ordered_date')
                        ->groupBy('products.name')
                        ->orderBy('ordered_date', 'ASC');
        if ($id)
        {
            $query->where('client_id', $id);
        }
        return $results;
    }
}
