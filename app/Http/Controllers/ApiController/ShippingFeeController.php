<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
