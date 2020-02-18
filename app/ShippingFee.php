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

    //Get Shipping Fee
    public function getShippingFee($shipping_fee_id)
    {
        $shipping_fee = ShippingFee::where('id', $shipping_fee_id)
            ->get()
            ->first();

        return $shipping_fee;
    }

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

    //Update Shipping Fee
    public function updateShippingFee($shipping_fee_id, $shipping_fee_details)
    {
        $new_shipping_fee_details = [
            'province_id' => $shipping_fee_details['shipping_province'],
            'city' => $shipping_fee_details['shipping_city'],
            'price' => $shipping_fee_details['shipping_price']
        ];

        $update_shipping_fee = ShippingFee::where('id', $shipping_fee_id)
            ->update($new_shipping_fee_details);
        
        return $update_shipping_fee;
    }

    //Check if shipping fee exists
    public function shippingFeeExists($shipping_fee_id)
    {
        $shipping_fee = ShippingFee::where('id', $shipping_fee_id)->count();

        return $shipping_fee != 0;
    }
}
