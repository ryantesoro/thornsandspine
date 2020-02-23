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

    //Get Shipping Fee by province_id
    public function getCitiesByProvince($province_id)
    {
        $shipping_fee = ShippingFee::where('province_id', $province_id)
            ->get();
        
        return $shipping_fee;
    }

    //Get Shipping Fee by city name and province id
    public function getShippingFeeByCityProvince($city_name, $province_id)
    {
        $shipping_details = [
            'city' => $city_name,
            'province_id' => $province_id
        ];

        $shipping_fee_details = ShippingFee::where($shipping_details)
            ->get()
            ->first();

        return $shipping_fee_details;
    }

    //Fetch all shipping fees
    public function getShippingFees($city_name, $province_id)
    {
        $shipping_fee_list = ShippingFee::select('id', 'city', 'province_id', 'price');
        
        if ($city_name != '' || !empty($city_name)) {
            $search_query = '%'.$city_name.'%';
            $shipping_fee_list->whereRaw('city LIKE ?', [
                $search_query
            ]);
        }
        
        if ($province_id != 0 && ($province_id != '' || !empty($province_id))) {
            $shipping_fee_list->where('province_id', $province_id);
        }

        return $shipping_fee_list->get()
            ->sortBy('created_at');

        return $shipping_fee_list;
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
        $update_shipping_fee = ShippingFee::where('id', $shipping_fee_id)
            ->update($shipping_fee_details);
        
        return $update_shipping_fee;
    }

    //Check if shipping fee exists
    public function shippingFeeExists($shipping_fee_id)
    {
        $shipping_fee = ShippingFee::where('id', $shipping_fee_id)->count();

        return $shipping_fee != 0;
    }
}
