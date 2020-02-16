<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use App\Notifications\EmailVerification;


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
                'success' => true,
                'msg' => 'Invalid Email/Password'
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
            'password' => $request->post('password')
        ];

        $customer_details = [
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'address' => $request->post('address'),
            'city' => $request->post('city'),
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

        $this->verification()->checkVerification($user->id);
        $verification = $this->verification()->insertVerification($user->id);
        $user->notify(new EmailVerification($verification->token));

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
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|max:21',
            'first_name' => 'required|min:3|max:30',
            'last_name' => 'required|min:3|max:30',
            'address' => 'required',
            'city' => 'required',
            'contact_number' => 'required|min:7|max:11'
            // 'shipping_address' => 'required',
            // 'shipping_city' => 'required',
            // 'shipping_region' => 'required',
            // 'shipping_location_type' => 'required'
        );

        $validator = Validator::make($registration_details, $options);

        return $validator;
    }
}
