<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'code', 'recipient', 'remarks',
        'total'
    ];

    public $timestamps = true;

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'customer_order');
    }

    public function product()
    {
        return $this->belongsToMany('App\Product', 'order_product');
    }
}
