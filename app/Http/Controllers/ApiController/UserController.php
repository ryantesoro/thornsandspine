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
            'email' => $request->get('email'),
            'password' => $request->get('password')
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

    public function test()
    {
        $customer_id = auth()->user()->customer()->value('id');
        $shipping_details = [
            'address' => 'asd',
            'city' => 'asd',
            'region' => 'asd',
            'location_type' => 'asd'
        ];

        $details = $this->customer()->find($customer_id)->shipping()->create($shipping_details);
        return response()->json([
            "success" => true,
            "data" => $details
        ]);
    }

    public function register(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

        $customer_details = [
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'contact_number' => $request->get('contact_number')
        ];

        $shipping_details = [
            'shipping_address' => $request->get('shipping_address'),
            'shipping_city' => $request->get('shipping_city'),
            'shipping_region' => $request->get('shipping_region'),
            'shipping_location_type' => $request->get('shipping_location_type')
        ];

        $registration_details = array_merge($credentials, $customer_details, $shipping_details);

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
        
        $shipping = $this->shipping()->addShipping($shipping_details);
        $customer->shipping()->save($shipping);

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
            'contact_number' => 'required|min:7|max:11',
            'shipping_address' => 'required',
            'shipping_city' => 'required',
            'shipping_region' => 'required',
            'shipping_location_type' => 'required'
        );

        $validator = Validator::make($registration_details, $options);

        return $validator;
    }
}
