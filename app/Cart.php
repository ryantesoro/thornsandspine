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

    //Get Cart Model
    public function getCartModel($cart_id)
    {
        $cart_model = Cart::find($cart_id);

        return $cart_model;
    }

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

    //Destroy Cart (1 row)
    public function destroyCart($cart_id)
    {
        $destroy_cart = Cart::find($cart_id)
            ->delete();
        
        return $destroy_cart;
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
