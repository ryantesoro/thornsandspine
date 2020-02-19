<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingFeeController extends Controller
{
    public function province(Request $request)
    {
        $provinces = $this->shipping_province()->getProvinces();
        $plucked_provinces = $provinces->pluck('id', 'name');

        return response()->json([
            'success' => true,
            'data' => $plucked_provinces
        ]);
    }

    public function city(Request $request, $province_id)
    {
        $shipping_fees = $this->shipping_fee()->getCitiesByProvince($province_id);
        
        $cities = array();
        foreach($shipping_fees as $shipping_fee) {
            $city = $shipping_fee->city;
            $shipping_id = $shipping_fee->id;
            $cities[$shipping_id] = $city;
        }

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }
}
