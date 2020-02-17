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

    public function store(Request $request)
    {
        $this->setUserId(auth()->user()->id);

        $recipient_first = $request->post('recipient_first_name');
        $recipient_last = $request->post('recipient_last_name');

        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $cart_total = $this->cart()->getCartTotal($customer);

        $order_details = [
            'recipient_first' => $recipient_first,
            'recipient_last' => $recipient_last,
            'remarks' => $request->post('remarks'),
            'total' => $cart_total
        ];


        if (!empty($recipient_first) && $recipient_first != null &&
            !empty($recipient_last) && $recipient_last != null) {
            $order_details['recipient_first'] = ucfirst($customer->first_name);
            $order_details['recipient_last'] = ucfirst($customer->last_name);
        }
        
        $order = $this->order()->storeOrder($order_details);

        $order_code = $this->generateCode($order->id);
        $update_order = $this->order()->updateOrder(['code' => $order_code], $order->id);
        
        dd($order);
    }

    private function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    
    private function generateCode($order_id)
    {
        $year = Carbon::now()->format('Y');
        $code = sprintf('%s%04s', $year, strVal($order_id));

        return $code;
    }
}
