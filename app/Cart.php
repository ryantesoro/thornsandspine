<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "carts";

    protected $hidden = ['pivot'];

    const UPDATED_AT = null;

    protected $fillable = [
        'quantity'
    ];

    public $timestamps = true;

    //Store Cart
    public function storeCart($quantity)
    {
        $cart_details = Cart::create(['quantity' => $quantity]);
        return $cart_details;
    }

    //Update Cart
    public function updateCart($cart_details)
    {
        $update_cart = Cart::find($cart_details['cart_id'])
            ->update(['quantity' => $cart_details['quantity']]);

        return $update_cart;
    }

    public function product()
    {
        return $this->belongsToMany('App\Product', 'cart_product');
    }

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'customer_cart');
    }
}
