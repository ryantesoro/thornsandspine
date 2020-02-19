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
}
