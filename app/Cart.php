<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

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
    public function getCartTotal($customer_cart)
    {
        $total = 0;
        foreach ($customer_cart->get() as $cart) {
            $product_id = $cart->product_id;
            $product = Product::find($product_id);
            
            $price = $product->price;
            $quantity = $cart->quantity;

            $total += $quantity * $price;
        }

        return $total;
    }

    //Get Cart
    public function getCart($customer_model, $where)
    {
        $cart = $customer_model->cart()
            ->where($where)
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

    //Clears cart
    public function clearCart($customer_cart)
    {
        foreach ($customer_cart->get() as $cart) {
            $this->destroyCart($cart->id);
        }
        $customer_cart->detach();
    }

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'customer_cart');
    }
}
