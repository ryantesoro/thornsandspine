<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
        $recipient_address = $request->post('recipient_address');
        $courier_id = $request->post('courier_id');
        $use_loyalty_points = $request->post('use_loyalty_points');

        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $customer_cart = $this->customer()->getCustomerCart($customer);

        if ($customer_cart->count() == 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Your cart is empty!'
            ]);
        }

        $cart_total = $this->cart()->getCartTotal($customer_cart);

        $customer_details = $this->customer()->getCustomer($customer->id);

        $loyalty_points = $customer_details->loyalty_points;
        $customer_id = $customer_details->id;

        $order_details = [
            'remarks' => $request->post('remarks'),
            'payment_method' => $request->post('payment_method'),
            'total' => $cart_total
        ];

        //Inserts recipient
        $insertedRecipient = false;
        if (!empty($recipient_first) && $recipient_first != null) {
            $city_province_id = $request->post('city_province_id');
            $recipient_details = [
                'first_name' => strtolower($request->post('recipient_first')),
                'last_name' => strtolower($request->post('recipient_last')),
                'address' => strtolower($request->post('recipient_address')),
                'email' => $request->post('recipient_email'),
                'contact_number' =>$request->post('recipient_contact_number')
            ];
    

            $store_recipient = $this->recipient()->storeRecipient($recipient_details);
            $order_details['recipient_id'] = $store_recipient->id;

            $shipping_fee = $this->shipping_fee()->getShippingFeeByCityProvinceAndCourier($courier_id, $city_province_id);
            $order_details['shipping_fees_id'] = $shipping_fee->id;
            $insertedRecipient = true;
        }

        //Check if recipient is not inserted
        if (!$insertedRecipient) {
            //Inserting Customer Shipping address instead
            $province = $this->province()->getProvinceByName($customer->province);
            $province_id = $province->id;
            $city_name = $customer->city;

            $city = $this->city()->getCityByNameAndProvince($city_name, $province_id);
            $city_province_id = $city->id;

            $shipping_fee = $this->shipping_fee()->getShippingFeeByCityProvinceAndCourier($courier_id, $city_province_id);

            $order_details['shipping_fees_id'] = $shipping_fee->id;
        }

        //If loyalty points is checked
        if ($use_loyalty_points) {
            $total = $cart_total + $shipping_fee->price;
            $is_free = false;

            $deducted_loyalty_points = 0;
            if ($loyalty_points != 0) {
                if ($loyalty_points > $total) {
                    $deducted_loyalty_points = $loyalty_points-$total;
                    $is_free = true;
                    $order_details['loyalty_points'] = $total;
                } else {
                    $deducted_loyalty_points = 0;
                    $order_details['loyalty_points'] = $loyalty_points;
                }
                
                $update_customer = $this->customer()->updateCustomer(['loyalty_points' => $deducted_loyalty_points], $customer_id);
            }

            if ($is_free) {
                $order_details['status'] = 1;
            }
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
        if (!$this->order()->orderExists($order_code)) {
            return response()->json([
                'success' => false,
                'msg' => 'Order does not exist!'
            ]);
        }
        
        $imgs = $request->file('img');

        $img_array = [];
        $options = [];

        $order_details = $this->order()->getOrder($order_code);
        $order = $this->order()->getOrderModel($order_details->id);

        $indx = 0;
        foreach ($imgs as $img) {
            $img_array['image_'.strVal($indx)] = $img;
            $options['image_'.strVal($indx)] = 'image|mimes:jpeg,png,jpg|max:5120';
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
        
        $order_details = $this->order()->getOrder($order_code);
        $order = $this->order()->find($order_details->id);
        $screenshots = $this->screenshot()->getOrderScreenshots($order);
        
        $indx = 0;
        if($screenshots->count() != 0) {
            $latest_file_name = $screenshots->max('file_name');

            $file_name_length = strlen($latest_file_name);
            $under_score_position = strpos($latest_file_name, '_');

            $indx = intval(substr($latest_file_name, $under_score_position+1, $file_name_length-$under_score_position))+1;
        }

        foreach ($imgs as $img) {
            $file_name = $order_code.'_'.$indx;
            $this->saveImageFile($img, $file_name);
            
            $screenshot = $this->screenshot()->storeScreenshot(['file_name' => $file_name]);
            $order->screenshot()->save($screenshot);

            $indx++;
        }

        $update_order = $this->order()->updateOrder(['status' => 1], $order_details->id);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully uploaded screenshot(s)'
        ]);
    }

    public function cancel(Request $request, $order_code)
    {
        if (!$this->order()->orderExists($order_code)) {
            return response()->json([
                'success' => false,
                'msg' => 'Order does not exist!'
            ]);
        }

        $order = $this->order()->getOrder($order_code);

        if ($order->status != 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Your order is already being processed. You cannot cancel it.'
            ]);
        }

        $cancel_order = $this->order()->updateOrder(['status' => 3], $order->id);

        return response()->json([
            'success' => true,
            'msg' => 'Your order has been cancelled'
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
        Storage::putFileAs('screenshot', new File($path), $file_name);

        $medium = Image::make($image_file)->resize(300,200)->encode('jpg')->save($path);
        Storage::putFileAs('screenshot/medium', new File($path), $file_name);

        $thumbnail = Image::make($image_file)->resize(100, 100)->encode('jpg')->save($path);
        Storage::putFileAs('screenshot/thumbnail', new File($path), $file_name);
    }
}
