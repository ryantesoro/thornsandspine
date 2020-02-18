<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    protected $table = "shipping_fees";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'province_id', 'city', 'price'
    ];

    public $timestamps = false;

    //Fetch all shipping fees
    public function getShippingFees()
    {
        $shipping_fees = ShippingFee::all();

        return $shipping_fees;
    }

    //Store Shipping Fee
    public function storeShippingFee($shipping_fee_details)
    {
        $new_shipping_fee_details = [
            'province_id' => $shipping_fee_details['shipping_province'],
            'city' => $shipping_fee_details['shipping_city'],
            'price' => $shipping_fee_details['shipping_price']
        ];
        
        $store_shipping_fee = ShippingFee::create($new_shipping_fee_details);

        return $store_shipping_fee;
    }
}
