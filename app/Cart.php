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

    public function product()
    {
        return $this->belongsToMany('App\Product', 'cart_product');
    }

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'customer_cart');
    }
}
