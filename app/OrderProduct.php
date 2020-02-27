<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = "order_product";

    protected $fillable = [
        'product_id', 'pot_id', 'quantity',
        'sub_total', 'order_id'
    ];

    public $timestamps = false;

    //Get Order Products
    public function getOrderProducts($order_id)
    {
        $order_products = OrderProduct::where('order_id', $order_id)
            ->get();
        
        return $order_products;
    }

    //Store Order Product
    public function storeOrderProduct($order_product_details)
    {
        $store_order_product = OrderProduct::create($order_product_details);

        return $store_order_product;
    }
}
