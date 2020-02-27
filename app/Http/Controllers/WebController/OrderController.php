<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $this->order()->getOrders($request->get('code'));

        return view('pages.order.order_index')->with('orders', $orders);
    }
}
