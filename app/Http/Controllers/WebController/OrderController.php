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
            Alert::warning('Show Order Failed', 'Order does not exist!');
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
        $ss = $order->screenshot()->get();
        $screenshots = [];
        foreach ($ss as $screenshot) {
            $temp_array = [];
            $temp_array['thumbnail'] = route('image', ["screenshot", $screenshot['file_name'], 'size' => 'thumbnail']);
            $temp_array['medium'] = route('image', ["screenshot", $screenshot['file_name'], 'size' => 'medium']);
            $temp_array['original'] = route('image', ["screenshot", $screenshot['file_name']]);
            $date_uploaded = Carbon::parse($screenshot['created_at'])->format('m-d-Y');
            $screenshots[$date_uploaded][] = $temp_array;
        }

        return view('pages.order.order_show')
            ->with('order', $order)
            ->with('customer', $customer)
            ->with('products', $products)
            ->with('shipping_price', $shipping_price)
            ->with('shipping_city_province', $shipping_city_province)
            ->with('screenshots', $screenshots);
    }

    public function deliver(Request $request, $order_code)
    {
        if (!$this->order()->orderExists($order_code)) {
            Alert::warning('Show Order Failed', 'Order does not exist!');
            return redirect()->route('admin.order.index');
        }

        $order = $this->order()->getOrder($order_code);
        $update_order = $this->order()->updateOrder(['status' => 2], $order->id);
        
        Alert::success('Complete Order Successful', 'Success!');
        return redirect()->route('admin.order.index');
    }

    public function return(Request $request, $order_code)
    {
        $order = $this->order()->getOrder($order_code);
        $update_order = $this->order()->updateOrder(['status' => 0, 'comment' => $request->post('comment')], $order->id);
        
        Alert::success('Ask Customer Successful', 'Success!');
        return redirect()->route('admin.order.index');
    }
}
