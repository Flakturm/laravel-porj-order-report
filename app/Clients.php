<?php

namespace turnip;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        'route',
        'route_number',
        'name',
        'is_small',
        'invoiced_daily'
    ];
    
    public function orders()
    {
        return $this->hasMany('turnip\Orders', 'client_id');
    }
}
