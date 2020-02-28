<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;


class ShippingFee extends Model
{
    protected $table = "shipping_fees";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'city_province_id', 'price'
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

    //Get Shipping fee by city_province_id and courier
    public function getShippingFeeByCityProvinceAndCourier($courier_id, $city_province_id)
    {
        $shipping_fee = DB::table('shipping_fees')
            ->selectRaw('shipping_fees.id, shipping_fees.price')
            ->leftJoin('courier_shipping_fee', function ($query) {
                $query->on('shipping_fees.id', 'courier_shipping_fee.shipping_fee_id');
            })
            ->leftJoin('couriers', function ($query) {
                $query->on('couriers.id', 'courier_shipping_fee.courier_id');
            })
            ->where(['couriers.id' => $courier_id, 'shipping_fees.city_province_id' => $city_province_id])
            ->get()
            ->first();

        return $shipping_fee;
    }

    //GetShippingFees
    public function getShippingFees($courier_id, $province_id)
    {
        $shipping_fees = DB::table('shipping_fees')
            ->selectRaw('shipping_fees.id, couriers.name as courier_name, city_province.city, provinces.name as province_name, shipping_fees.price')
            ->leftJoin('courier_shipping_fee', function ($query) {
                $query->on('shipping_fees.id', 'courier_shipping_fee.shipping_fee_id');
            })
            ->leftJoin('couriers', function ($query) {
                $query->on('couriers.id', 'courier_shipping_fee.courier_id');
            })
            ->leftJoin('city_province', function ($query) {
                $query->on('city_province.id', 'shipping_fees.city_province_id');
            })
            ->leftJoin('provinces', function ($query) {
                $query->on('provinces.id', 'city_province.province_id');
            });

        if ($courier_id != 0) {
            $shipping_fees = $shipping_fees->where('couriers.id', $courier_id);
        }

        if ($province_id != 0) {
            $shipping_fees = $shipping_fees->where('provinces.id', $province_id);
        }

        return $shipping_fees->get();
    }

    //Check for duplicates
    public function duplicateExists($courier_id, $city_province_id, $shipping_fee_id)
    {
        $shipping_fee = DB::table('shipping_fees')
            ->selectRaw('*')
            ->leftJoin('courier_shipping_fee', function ($query) {
                $query->on('shipping_fees.id', 'courier_shipping_fee.shipping_fee_id');
            })
            ->leftJoin('couriers', function ($query) {
                $query->on('couriers.id', 'courier_shipping_fee.courier_id');
            })
            ->where(['couriers.id' => $courier_id, 'shipping_fees.city_province_id' => $city_province_id]);

        if ($shipping_fee_id != null) {
            $shipping_fee = $shipping_fee->where('shipping_fees.id', '<>', $shipping_fee_id);
        }

        return $shipping_fee->get()->count() != 0;
    }

    //Store Shipping Fee
    public function storeShippingFee($shipping_fee_details)
    {   
        $store_shipping_fee = ShippingFee::create($shipping_fee_details);

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

    public function courier()
    {
        return $this->belongsToMany('App\Courier', 'courier_shipping_fee');
    }
}
