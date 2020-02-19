<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "carts";

    protected $hidden = ['pivot'];

    const UPDATED_AT = null;

    protected $fillable = [
        'quantity', 'product_id', 'pot_id'
    ];

    public $timestamps = true;

    //Get Cart Total
    public function getCartTotal($customer_model)
    {
        $cart_model = $customer_model->cart();
        
        $total = 0;
        foreach ($cart_model->get() as $cart) {
            $product = $cart->product();
            
            $quantity = $cart->quantity;
            $price = $product->value('price');

            $total += $quantity * $price;
        }

        return $total;
    }

    //Get Cart
    public function getCart($where)
    {
        $cart = Cart::where($where)
            ->get()
            ->first();

        return $cart;
    }

    //Get Cart Model
    public function getCartModel($cart_id)
    {
        $cart_model = Cart::find($cart_id);

        return $cart_model;
    }

    //Store Cart
    public function storeCart($cart_details)
    {
        $store_cart = Cart::create($cart_details);

        return $store_cart;
    }

    //Update Cart
    public function updateCart($cart_id, $cart_details)
    {
        $update_cart = Cart::find($cart_id)
            ->update($cart_details);

        return $update_cart;
    }

    //Destroy Cart (1 row)
    public function destroyCart($cart_id)
    {
        $destroy_cart = Cart::find($cart_id)
            ->delete();
        
        return $destroy_cart;
    }

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'customer_cart');
    }
}
