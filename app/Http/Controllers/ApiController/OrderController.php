<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Carbon\Carbon;
use Image;
use App\Mail\OrderPlaced;
use Mail;


class OrderController extends Controller
{
    private $user_id;

    public function index(Request $request)
    {
        $this->setUserId(auth()->user()->id);
        $status = $request->get('status');

        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $order_model = $this->customer()->getCustomerOrders($customer);
        
        $orders = $this->order()->getOrdersByStatus($order_model, $status);

        $order_list = [];
        foreach ($orders as $order) {
            $should_add = false;

            if ($order->expires_at != null) {
                $expiry_date = Carbon::createFromFormat('Y-m-d H:i:s', $order->expires_at)->endOfDay();
                if ($status == 5 && $expiry_date->isPast()) {
                    $should_add = true;
                } else if (!$expiry_date->isPast()) {
                    $should_add = true;
                }
            }

            if ($order->status == $status) {
                $should_add = true;
            }

            if ($status == 'all') {
                $should_add = true;
            }

            if ($should_add) {
                $order_list[] = [
                    'code' => $order->code,
                    'created_at' => $order->created_at->format('l, F d, Y h:i A')
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $order_list
        ]);
    }

    public function show($order_code)
    {
        $this->setUserId(auth()->user()->id);

        $order = $this->order()->getOrder($order_code);

        //Changing status of order if expired
        if ($order->expires_at != null) {
            $expiry_date = Carbon::createFromFormat('Y-m-d H:i:s', $order->expires_at)->endOfDay();
            if ($expiry_date->isPast()) {
                $order->status = 5;
            }
        }

        //Fetching all products of the order
        $order_id = $order->id;
        $order_product = $this->order_product()->getOrderProducts($order_id);
        $products = [];
        foreach ($order_product as $product) {
            $pot_name = $this->pot()->getPot($product->pot_id)->name;
            $product_details = $this->product()->getProduct($product->product_id);
            $product_name = $product_details->name;
            $quantity = $product->quantity;
            $sub_total = $product->sub_total;
            $price = $sub_total/$quantity;
            $product_img = route('image.api', [$product_details->img, 'size' => 'thumbnail']);
            $products[] = [
                'img' => $product_img,
                'name' => ucwords($product_name),
                'pot_name' => ucwords($pot_name),
                'price' => $price,
                'quantity' => $quantity
            ];
        }
        $order['products'] = $products;

        //Fetch the recipient of the order
        $recipient_details = [];
        $recipient_address = "";
        if ($order->recipient_id !== null) {
            $recipient = $this->recipient()->getRecipient($order->recipient_id);
            $recipient_address = ucwords($recipient->address);
            $recipient_details = [
                'first_name' => ucwords($recipient->first_name),
                'last_name' => ucwords($recipient->last_name),
                'contact_number' => $recipient->contact_number,
                'email' => $recipient->email
            ];
        } else {
            $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
            $customer_details = $this->customer()->getCustomer($customer->id);
            $user_email = auth()->user()->email;
            $recipient_address = ucwords($customer_details->address);
            $recipient_details = [
                'first_name' => ucwords($customer_details->first_name),
                'last_name' => ucwords($customer_details->last_name),
                'contact_number' => $customer_details->contact_number,
                'email' => $user_email
            ];
        }
        unset($order['recipient_id']); 

        //Fetch Shipping Agent
        $shipping_fee = $this->shipping_fee()->getShippingFee($order->shipping_fees_id);
        $courier_name = $shipping_fee->courier()->get()->first()->name;
        $order['shipping_agent'] = $courier_name;
        $order['shipping_fee'] = $shipping_fee->price;
        unset($order['shipping_fees_id']); 

        //Fetch City and Province
        $city_province = $this->city()->getCity($shipping_fee->city_province_id);
        $city_name = $city_province->city;
        $province_id = $city_province->province_id;
        
        $province_name = $this->province()->getProvince($province_id)->name;
        $new_address = $recipient_address.', '.ucwords($city_name).', '.ucwords($province_name);
        $recipient_details['address'] = $new_address;

        $order['recipient'] = $recipient_details;
        $order['grand_total'] = ($order->total + $shipping_fee->price) - $order->loyalty_points;

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        $this->setUserId(auth()->user()->id);

        $recipient_first = $request->post('recipient_first');
        $courier_id = $request->post('courier_id');
        $delivery_date = Carbon::createFromFormat('m-d-Y', $request->post('delivery_date'))->endOfDay();
        $use_loyalty_points = $request->post('use_loyalty_points');

        //Getting Customer's Cart
        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $customer_cart = $this->customer()->getCustomerCart($customer);

        if ($customer_cart->count() == 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Your cart is empty!'
            ]);
        }

        if ($delivery_date->isPast()) {
            return response()->json([
                'success' => false,
                'msg' => 'Delivery date must not be from the past!'
            ]);
        }

        $shipping_agent = $this->courier()->getCourier($courier_id);
        $now = Carbon::now();
        if ($shipping_agent->same_day == 0) {
            if ($now->isSameDay($delivery_date)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Shipping Agent does not do same day delivery. Please pick another shipping agent.'
                ]);
            }
        }

        $cart_total = $this->cart()->getCartTotal($customer_cart);

        $order_details = [
            'remarks' => $request->post('remarks'),
            'payment_method' => $request->post('payment_method'),
            'delivery_date' => $delivery_date->format('Y-m-d'),
            'total' => $cart_total
        ];

        //Inserts recipient
        $insertedRecipient = false;
        $recipient_details = [];
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
        $customer_details = $this->customer()->getCustomer($customer->id);
        if (!$insertedRecipient) {
            //Inserting Customer Shipping address instead
            $province = $this->province()->getProvinceByName($customer_details->province);
            $province_id = strtolower($province->id);
            $city_name = strtolower($customer_details->city);

            $city = $this->city()->getCityByNameAndProvince($city_name, $province_id);
            $city_province_id = $city->id;

            $shipping_fee = $this->shipping_fee()->getShippingFeeByCityProvinceAndCourier($courier_id, $city_province_id);

            $order_details['shipping_fees_id'] = $shipping_fee->id;
            $recipient_details = $customer_details;
        }

        //If loyalty points is checked
        if ($use_loyalty_points) {
            $loyalty_points = $customer_details->loyalty_points;
            $customer_id = $customer_details->id;
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
        $temp_products = [];
        foreach ($carts as $cart) {
            $product_details = $this->product()->getProduct($cart->product_id);
            $order_product_details = [
                'order_id' => $order->id,
                'quantity' => $cart->quantity,
                'product_id' => $cart->product_id,
                'pot_id' => $cart->pot_id,
                'sub_total' => $cart->quantity * $product_details->price
            ];

            $temp_products[] = $order_product_details; 

            $store_order_product = $this->order_product()->storeOrderProduct($order_product_details);
        }

        $this->cart()->clearCart($customer_cart);

        //Mail Order Details
        $shipping_fees = $this->shipping_fee()->getShippingFee($shipping_fee->id);
        $city_province = $this->city()->getCity($shipping_fees->city_province_id);
        $order = $this->order()->getOrder($order_code);
        $order_product = $this->order_product()->getOrderProducts($order->id);

        $products = [];
        $sub_total = 0;
        foreach ($order_product as $product) {
            $temp_array = [];
            $temp_array['name'] = $this->product()->getProduct($product->product_id)->name;
            $temp_array['price'] = $product->sub_total/$product->quantity;
            $temp_array['pot_type'] = $this->pot()->getPot($product->pot_id)->name;
            $temp_array['quantity'] = $product->quantity;
            $temp_array['sub_total'] = $product->sub_total;
            $products[] = $temp_array;
            $sub_total += $product->sub_total;
        }

        $total = [
            'sub_total' => $sub_total,
            'grand_total' => ($sub_total + $shipping_fees->price) - $order->loyalty_points
        ];

        $data = [
            'order' => $order,
            'products' => $products,
            'recipient' => $recipient_details,
            'shipping_agent' => $this->courier()->getCourier($courier_id)->name,
            'city' => $city_province->city,
            'province' => $this->province()->getProvince($city_province->province_id)->name,
            'shipping_price' => $shipping_fees->price,
            'total' => $total
        ];

        $user = $this->user()->getUser(['id' => $this->user_id]);

        Mail::to($user)->queue(new OrderPlaced($data));

        $payment_method = $order->payment_method;

        if ($payment_method == 'GCash') {
            $gcash_number = $this->configuration()->getConfiguration('gcash_number')->value;
            $msg = 'You can send us the money in our GCASH number: 0'.$gcash_number;
        } else {
            $bank_name = $this->configuration()->getConfiguration('bank_name')->value;
            $bank_number = $this->configuration()->getConfiguration('card_number')->value;

            $msg = 'You can send us the money in our Bank Account.\nBank Name: '.$bank_name.'\nAccount Number: '.$bank_number;
        }
        return response()->json([
            'success' => true,
            'msg' => 'You have successfully ordered!\nBefore we deliver your order, make sure to pay us using '.strToUpper($order->payment_method).
            ' before '.$order->expires_at.'\n'.$msg.'\n'.
            "If you didn't know how to send money using ".strToUpper($order->payment_method).'\n'.
            "You check out our the FAQs"
        ]);
    }

    public function create(Request $request)
    {
        //city province courier loyalty points
        $couriers = $this->courier()->getCouriers()->pluck('name', 'id');

        $provinces = $this->province()->getProvinces();
        $plucked_provinces = $provinces->pluck('name', 'id');

        $this->setUserId(auth()->user()->id);
        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $customer_details = $this->customer()->getCustomer($customer->id);

        $loyalty_points = $customer_details->loyalty_points;

        return response()->json([
            'success' => true,
            'data' => [
                'shipping_agents' => $couriers,
                'provinces' => $plucked_provinces,
                'loyalty_points' => $loyalty_points
            ]
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

        $update_order = $this->order()->updateOrder(['status' => 1, 'expires_at' => null, 'comment' => null], $order_details->id);

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

        if ($order->loyalty_points != 0) {
            $this->setUserId(auth()->user()->id);
            $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
            $customer_details = $this->customer()->getCustomer($customer->id);
            $customer_id = $customer_details->id;
            $total_loyalty_points = $customer_details->loyalty_points + $order->loyalty_points;
            $update_customer = $this->customer()->updateCustomer(['loyalty_points' => $total_loyalty_points], $customer_id);
        }

        $cancel_order = $this->order()->updateOrder(['status' => 3, 'expires_at' => null], $order->id);

        return response()->json([
            'success' => true,
            'msg' => 'Your order has been cancelled'
        ]);
    }

    public function summary(Request $request)
    {
        $this->setUserId(auth()->user()->id);

        //Getting Customer's Cart
        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $customer_cart = $this->customer()->getCustomerCart($customer);

        if ($request->post('delivery_date') == null) {
            return response()->json([
                'success' => false,
                'msg' => 'Please specify the delivery date!'
            ]);
        }

        $now = Carbon::now();

        $delivery_date = Carbon::createFromFormat('m-d-Y', $request->post('delivery_date'))->endOfDay();

        if ($customer_cart->count() == 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Your cart is empty!'
            ]);
        }

        if ($delivery_date->isPast()) {
            return response()->json([
                'success' => false,
                'msg' => 'Delivery date must not be from the past!'
            ]);
        }

        $city_province_id = $request->post('city_province_id');
        $courier_id = $request->post('courier_id');
        $use_loyalty_points = $request->post('use_loyalty_points');
        $use_mine = $request->post('use_mine');

        $validate_input = [
            'courier_id' => $courier_id
        ];
        $options = [
            'courier_id' => 'required|exists:couriers,id'
        ];

        if (!$use_mine) {
            $validate_input['city_province_id'] = $city_province_id;
            $options['city_province_id'] = 'required|exists:city_province,id';
        }

        $validator = Validator::make($validate_input, $options);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Input'
            ]);
        }

        $shipping_agent = $this->courier()->getCourier($courier_id);

        if ($shipping_agent->same_day == 0) {
            if ($now->isSameDay($delivery_date)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Shipping Agent does not do same day delivery. Please pick another shipping agent.'
                ]);
            }
        }

        $customer_details = $this->customer()->getCustomer($customer->id);
    
        $customer_details->first_name = ucwords($customer_details->first_name);
        $customer_details->last_name = ucwords($customer_details->last_name);
        $customer_details->address = ucwords($customer_details->address);

        if ($use_mine) {
            $province = $this->province()->getProvinceByName($customer_details->province);
            $city_province = $this->city()->getCityByNameAndProvince($customer_details->city, $province->id);
            
            $city_province_id = $city_province->id;
        }

        $shipping_fees = $this->shipping_fee()->getShippingFeeByCityProvinceAndCourier($courier_id, $city_province_id);

        if (empty($shipping_fees)) {
            return response()->json([
                'success' => false,
                'msg' => "The shipping agent does not deliver to your place."
            ]);
        }

        $cart_total = $this->cart()->getCartTotal($customer_cart);

        $grand_total = ($cart_total + $shipping_fees->price);
        $discount = 0;
        $loyalty_points_left = 0;
        if ($use_loyalty_points) {
            $loyalty_points = $customer_details->loyalty_points;
            if ($grand_total < $loyalty_points) {
                $loyalty_points_left = $loyalty_points - $grand_total;
                $discount = $grand_total;
                $grand_total = 0;
            } else {
                $grand_total -= $customer_details->loyalty_points;
                $discount = $loyalty_points;
            }
        }    

        $customer_details['email'] = auth()->user()->email;    

        return response()->json([
            'success' => true,
            'data' => [
                'customer_details' => $customer_details,
                'total_items' => count($customer_cart->get()),
                'cart_total' => $cart_total,
                'shipping_fee' => $shipping_fees->price,
                'grand_total' => $grand_total,
                'discount' => $discount,
                'loyalty_points_left' => $loyalty_points_left
            ]
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
