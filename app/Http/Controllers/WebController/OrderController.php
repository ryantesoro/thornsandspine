<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $this->order()->getOrders($request->get('code'));

        return view('pages.order.order_index')->with('orders', $orders);
    }

    public function show(Request $request, $order_code)
    {
        if (!$this->order()->orderExists($order_code)) {
            Alert::error('Show Order Failed', 'Order does not exist!');
            return redirect()->route('admin.order.index');
        }

        $order = $this->order()->getOrder($order_code);
        $customer = $order->customer()->get()->first();
        $order['date'] = Carbon::parse($order['created_at'])->format('m-d-Y g:i A');
        $order['expiry'] = Carbon::parse($order['expires_at'])->format('m-d-Y g:i A');

        $order_products = $this->order_product()->getOrderProducts($order->id);
        $products = [];
        foreach ($order_products as $product) {
            $temp_array = [];
            $temp_array['product'] = $this->product()->getProduct($product->product_id);
            $temp_array['pot'] = $this->pot()->getPot($product->pot_id);
            $temp_array['quantity'] = $product->quantity;
            $temp_array['sub_total'] = $product->sub_total;
            $products[] = $temp_array;
        }

        $shipping_fee = $this->shipping_fee()->getShippingFee($order->shipping_fees_id);
        $city = $shipping_fee->city;
        $province = $this->shipping_province()->getProvince($shipping_fee->province_id)->name;
        $shipping_price = $shipping_fee->price;
        $shipping_city_province = ucwords($city.', '.$province);

        return view('pages.order.order_show')
            ->with('order', $order)
            ->with('customer', $customer)
            ->with('products', $products)
            ->with('shipping_price', $shipping_price)
            ->with('shipping_city_province', $shipping_city_province);
    }
}
