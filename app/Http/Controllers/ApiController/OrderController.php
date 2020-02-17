<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    private $user_id;

    public function index(Request $request)
    {
        $this->setUserId(auth()->user()->id);
        
        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $orders = $this->customer()->getCustomerOrders($customer)->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    private function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}
