<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ShippingFeeController extends Controller
{
    public function provinces(Request $request)
    {
        $provinces = $this->province()->getProvinces();
        $plucked_provinces = $provinces->pluck('name', 'id');

        return response()->json([
            'success' => true,
            'data' => $plucked_provinces
        ]);
    }

    public function cities(Request $request, $province_id)
    {
        $cities = $this->city()->getCities(null, $province_id)->pluck('city', 'id');

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    public function quotation(Request $request)
    {
        $city_province_id = $request->get('city_province_id');
        $courier_id = $request->get('courier_id');
        
        $validator = Validator::make(['city_province_id' => $city_province_id, 'courier_id' => $courier_id], [
            'city_province_id' => 'required|exists:city_province,id',
            'courier_id' => 'required|exists:couriers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Input'
            ]);
        }

        $shipping_fees = $this->shipping_fee()->getShippingFeeByCityProvinceAndCourier($courier_id, $city_province_id);

        if (empty($shipping_fees)) {
            return response()->json([
                'success' => false,
                'msg' => "The shipping agent does not deliver to your place."
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $shipping_fees->price
        ]);
    }
}
