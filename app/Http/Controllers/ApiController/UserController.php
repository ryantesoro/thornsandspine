<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use App\Notifications\EmailVerification;

use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\File;
use Carbon\Carbon;
use Image;



class UserController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->post('email'),
            'password' => $request->post('password')
        ];

        $login_attempt = auth()->attempt($credentials);

        if (!$login_attempt) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Email/Password'
            ]);
        }

        if (auth()->user()->access_level != 0) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Email/Password'
            ]);
        }

        $user_id = auth()->user()->id;

        if (!$this->user()->userVerified($user_id)) {
            return response()->json([
                'success' => false,
                'msg' => 'You must verify your email first!',
                'verification' => true
            ]);
        }

        auth()->user()->setNewApiToken();
        $api_token = auth()->user()->api_token;

        return response()->json([
            "success" => true,
            "api_token" => $api_token
        ]);
    }

    public function register(Request $request)
    {
        $credentials = [
            'email' => $request->post('email'),
            'password' => $request->post('password'),
            'password1' => $request->post('password1')
        ];

        $customer_details = [
            'first_name' => strtolower($request->post('first_name')),
            'last_name' => strtolower($request->post('last_name')),
            'address' => strtolower($request->post('address')),
            'shipping_fees_id' => $request->post('shipping_fees_id'),
            'contact_number' => $request->post('contact_number')
        ];

        // $shipping_details = [
        //     'shipping_address' => $request->post('shipping_address'),
        //     'shipping_city' => $request->post('shipping_city'),
        //     'shipping_region' => $request->post('shipping_region'),
        //     'shipping_location_type' => $request->post('shipping_location_type')
        // ];

        $registration_details = array_merge($credentials, $customer_details);

        $registration_details_validator = $this->validateRegistrationDetails($registration_details);

        if ($registration_details_validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $registration_details_validator->errors()
            ]);
        }

        $user = $this->user()->registerUser($credentials);
        $verification = $this->verification()->insertVerification($user->id);
        $user->notify(new EmailVerification($verification->token));

        $shipping_fees_id = $customer_details['shipping_fees_id'];
        $shipping_fee = $this->shipping_fee()->getShippingFee($shipping_fees_id);
        
        unset($customer_details['shipping_fees_id']);
        $province_id = $shipping_fee->province_id;
        $customer_details['province'] = $this->shipping_province()->getProvince($province_id)->name;
        $customer_details['city'] = $shipping_fee->city;

        $customer = $this->customer()->registerCustomer($customer_details);
        $user->customer()->save($customer);
        
        // $shipping = $this->shipping()->addShipping($shipping_details);
        // $customer->shipping()->save($shipping);

        return response()->json([
            'success' => true,
            'msg' => 'You have successfully registered!'
        ]);
    }

    private function validateRegistrationDetails($registration_details)
    {
        $options = array(
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:21',
            'password1' => 'required|min:8|max:21|same:password',
            'first_name' => 'required|min:3|max:30',
            'last_name' => 'required|min:3|max:30',
            'address' => 'required',
            'shipping_fees_id' => 'required|exists:shipping_fees,id',
            'contact_number' => 'required|min:7|max:11'
            // 'shipping_address' => 'required',
            // 'shipping_city' => 'required',
            // 'shipping_region' => 'required',
            // 'shipping_location_type' => 'required'
        );

        $validator = Validator::make($registration_details, $options);

        return $validator;
    }

    public function tester(Request $request)
    {
        $imgs = $request->file('img');
        $indx = 0;
        foreach($imgs as $img) {
            $this->saveImageFile($img, strVal($indx).'.jpg');
            $indx++;
        }

        return response()->json([
            'success' => true
        ]);
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
