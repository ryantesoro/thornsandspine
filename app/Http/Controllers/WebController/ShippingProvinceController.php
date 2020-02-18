<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingProvinceController extends Controller
{
    public function index()
    {
        return view('pages.shipping_province.shipping_province_index');
    }
}
