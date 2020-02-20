<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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

        $recipient_first = $request->post('recipient_first');
        $recipient_last = $request->post('recipient_last');
        $shipping_fees_id = $request->post('shipping_fees_id');

        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $customer_cart = $this->customer()->getCustomerCart($customer);
        $cart_total = $this->cart()->getCartTotal($customer_cart);

        $order_details = [
            'recipient_first' => $recipient_first,
            'recipient_last' => $recipient_last,
            'remarks' => $request->post('remarks'),
            'shipping_fees_id' => $shipping_fees_id,
            'total' => $cart_total
        ];

        if ((empty($recipient_first) || $recipient_first == null) &&
            (empty($recipient_last) || $recipient_last == null)) {
            $order_details['recipient_first'] = ucfirst($customer->first_name);
            $order_details['recipient_last'] = ucfirst($customer->last_name);
        }

        if (empty($shipping_fees_id) && $shipping_fees_id == null) {
            $province = $this->shipping_province()->getProvinceByName($customer->province);
            $province_id = $province->id;
            $city_name = $customer->city;
            
            $shipping_fee = $this->shipping_fee()->getShippingFeeByCityProvince($city_name, $province_id);
            $order_details['shipping_fees_id'] = $shipping_fee->id;
        }
        
        $order = $this->order()->storeOrder($order_details);
        $customer->order()->save($order);

        $order_code = $this->generateCode($order->id);
        $update_order = $this->order()->updateOrder(['code' => $order_code], $order->id);

        $this->cart()->clearCart($customer_cart);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully ordered!'
        ]);
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
