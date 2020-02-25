<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Carbon\Carbon;
use Image;


class OrderController extends Controller
{
    private $user_id;

    public function index(Request $request)
    {
        $this->setUserId(auth()->user()->id);
        $status = $request->get('status');

        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $order_model = $this->customer()->getCustomerOrders($customer);
        
        $orders = $this->order()->getOrdersByStatus($order_model, $status ?? 0);

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
        $shipping_fee = $this->shipping_fee()->getShippingFee($shipping_fees_id);

        $order_details = [
            'recipient_first' => $recipient_first,
            'recipient_last' => $recipient_last,
            'remarks' => $request->post('remarks'),
            'shipping_fees_id' => $shipping_fees_id,
            'payment_method' => $request->post('payment_method'),
            'total' => $cart_total+$shipping_fee->price
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

        $carts = $customer_cart->get();
        foreach ($carts as $cart) {
            $product_details = $this->product()->getProduct($cart->product_id);
            $order_product_details = [
                'order_id' => $order->id,
                'quantity' => $cart->quantity,
                'product_id' => $cart->product_id,
                'pot_id' => $cart->pot_id,
                'sub_total' => $cart->quantity * $product_details->price
            ];

            $store_order_product = $this->order_product()->storeOrderProduct($order_product_details);
        }

        $this->cart()->clearCart($customer_cart);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully ordered!'
        ]);
    }

    public function update(Request $request, $order_code)
    {
        $imgs = $request->file('img');

        $img_array = [];
        $options = [];
        $indx = 1;
        foreach ($imgs as $img) {
            $img_array['image'.strVal($indx)] = $img;
            $options['image'.strVal($indx)] = 'image|mimes:jpeg,png,jpg|max:5120';
            $indx++;
        }

        $validator = Validator::make($img_array, $options);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Input',
                'errors' => $validator->errors()
            ]);
        }
        
        $order = $this->order()->getOrderModel($order_code);
        $order_details = $order->get()->first();

        $indx = 0;
        foreach ($img_array as $img) {
            $file_name = $order_code.'_'.$indx;
            $this->saveImageFile($img, $file_name);

            $screenshot = $this->screenshot()->storeScreenshot($file_name);
            $order->screenshot()->save($screenshot);

            $indx++;
        }

        $update_order = $this->order()->updateOrder(['status' => 1], $order_details->id);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully uploaded screenshot(s)'
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

    private function saveImageFile($image_file, $file_name)
    {
        $path = $image_file->getRealPath().'.jpg';

        $whole_pic = Image::make($image_file)->encode('jpg')->save($path);
        Storage::putFileAs('product', new File($path), $file_name);

        $medium = Image::make($image_file)->resize(300,200)->encode('jpg')->save($path);
        Storage::putFileAs('product/medium', new File($path), $file_name);

        $thumbnail = Image::make($image_file)->resize(100, 100)->encode('jpg')->save($path);
        Storage::putFileAs('product/thumbnail', new File($path), $file_name);
    }
}
