<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingFeeController extends Controller
{
    public function index()
    {
        return view('pages.shipping_fee.shipping_fee_index');
    }
}
