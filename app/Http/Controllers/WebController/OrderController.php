<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Storage;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $code = $request->get('code');
        $status = $request->get('status');
        $codes = $request->get('codes');

        $orders = $this->order()->getOrders($code, $status);

        if (!empty($codes) && $codes != null) {
            $orders = $orders->whereIn('code', $codes);
        } 

        return view('pages.order.order_index')->with('orders', $orders);
    }

    public function show(Request $request, $order_code)
    {
        if (!$this->order()->orderExists($order_code)) {
            Alert::warning('Show Order Failed', 'Order does not exist!');
            return redirect()->route('admin.order.index');
        }

        //Gets orders
        $order = $this->order()->getOrder($order_code);
        $customer = $order->customer()->get()->first();
        $order['date'] = Carbon::parse($order['created_at'])->format('m-d-Y g:i A');
        $order['expiry'] = Carbon::parse($order['expires_at'])->format('m-d-Y g:i A');
        $order['delivery_date'] = Carbon::parse($order['delivery_date'])->format('m-d-Y');

        //Gets order products
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

        //Gets shipping fee
        $shipping_fee = $this->shipping_fee()->getShippingFee($order->shipping_fees_id);
        $courier = $shipping_fee->courier()->get()->first();
        $shipping_agent = $courier->name;
        $shipping_price = $shipping_fee->price;

        //Gets city province
        $city_province_id = $shipping_fee->city_province_id;
        $city_province = $this->city()->getCity($city_province_id);
        $city = $city_province->city;
        $province = $this->province()->getProvince($city_province->province_id)->name;
        $shipping_city_province = ucwords($city.', '.$province);

        //Gets screenshots
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

        //Gets recipient
        $recipient = $recipient = $this->recipient()->getRecipient($order->recipient_id);

        return view('pages.order.order_show')
            ->with('order', $order)
            ->with('recipient', $recipient)
            ->with('customer', $customer)
            ->with('products', $products)
            ->with('shipping_price', $shipping_price)
            ->with('shipping_agent', $shipping_agent)
            ->with('shipping_city_province', $shipping_city_province)
            ->with('screenshots', $screenshots);
    }

    public function deliver(Request $request, $order_code)
    {
        if (!$this->order()->orderExists($order_code)) {
            Alert::warning('Show Order Failed', 'Order does not exist!');
            return redirect()->route('admin.order.index');
        }

        $tracking_number = $request->post('tracking_number');
        if ($tracking_number == null) {
            Alert::warning('Complete Order Failed', 'Tracking Number is required');
            return redirect()->back();
        }

        $order = $this->order()->getOrder($order_code);
        $update_order = $this->order()->updateOrder(['status' => 2, 'expires_at' => null, 'tracking_number' => $tracking_number], $order->id);

        $customer = $order->customer()->get()->first();
        $loyalty_points = floor($order->total/100);
        $customer_update = $this->customer()->updateCustomer(['loyalty_points' => $loyalty_points], $customer->id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Completed Order #'.$order_code
        ]);
        
        Alert::success('Complete Order Successful', 'Success!');
        return redirect()->route('admin.order.index');
    }

    public function return(Request $request, $order_code)
    {
        $order = $this->order()->getOrder($order_code);
        $update_order = $this->order()->updateOrder(['status' => 0, 'comment' => $request->post('comment'), 'expires_at' => Carbon::now()->addDays(2)], $order->id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Returned Order #'.$order_code
        ]);
        
        Alert::success('Ask Customer Successful', 'Success!');
        return redirect()->route('admin.order.index');
    }

    public function print(Request $request, $order_code)
    {
        $order_details = $this->order()->getOrder($order_code);
        $customer_details = $order_details->customer()->get()->first();
        
        //Get Recipient
        $recipient = [];
        if ($order_details->recipient_id != null) {
            $recipient = $this->recipient()->getRecipient($order_details->recipient_id);
        } else {
            $recipient = $customer_details;
        }

        //Get Order Products
        $order_products = $this->order_product()->getOrderProducts($order_details->id);
        $products = [];
        foreach ($order_products as $product) {
            $product_details = $this->product()->getProduct($product->product_id);
            $pot_details = $this->pot()->getPot($product->pot_id);
            $products[] = [
                'code' => $product_details->code,
                'name' => $product_details->name,
                'pot_type' => $pot_details->name,
                'price' => $product->sub_total/$product->quantity,
                'quantity' => $product->quantity,
                'sub_total' => $product->sub_total
            ];
        }

        //Get Shipping Fee
        $shipping_fee_details = $this->shipping_fee()->getShippingFee($order_details->shipping_fees_id);
        
        //Get Shipping Agent
        $shipping_agent_details = $shipping_fee_details->courier()->get()->first();

        //Get City Province
        $city_province_details = $this->city()->getCity($shipping_fee_details->city_province_id);
        
        //Get Province
        $province_details = $this->province()->getProvince($city_province_details->province_id);

        //Get Total
        $total_product_price = collect($products)->sum('sub_total');
        $grand_total = ($total_product_price + $shipping_fee_details->price) - $order_details->loyalty_points;
        $total = [
            'total_product_price' => $total_product_price,
            'grand_total' => $grand_total
        ];

        $data = [
            'order' => $order_details,
            'customer' => $customer_details,
            'recipient' => $recipient,
            'products' => $products,
            'logo_url' => route('image', ['logo', 'logo.jpg']),
            'shipping_agent' => $shipping_agent_details,
            'city' => $city_province_details->city,
            'province' => $province_details->name,
            'shipping_fee' => $shipping_fee_details,
            'total' => $total
        ];

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pages.order.order_print', compact('data'));

        $path = "/app/order";
        $now = Carbon::now()->format('m-d-Y_h-i-sA');
        $filename = "[".$now."]-$order_code._Order.pdf";
        $full_path = storage_path().$path."/".$filename;
        $pdf->save($full_path);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Printed Order #'.$order_code
        ]);

        return Storage::disk('local')->download('order/'.$filename);
    }
}
