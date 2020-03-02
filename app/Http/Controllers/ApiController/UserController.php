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

    public function show(Request $request)
    {
        $customer = $this->customer()->getCustomerDetailsByUser(auth()->user())->get();
        $customer_details = $this->customer()->getCustomer($customer->id);

        return $customer_details;
    }

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
            'city_province_id' => $request->post('city_province_id'),
            'contact_number' => $request->post('contact_number')
        ];

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

        $city_id = $customer_details['city_province_id'];
        $city = $this->city()->getCity($city_id);
        $province = $this->province()->getProvince($city->province_id);
        
        unset($customer_details['city_province_id']);

        $customer_details['province'] = $province->name;
        $customer_details['city'] = $city->city;

        $customer = $this->customer()->registerCustomer($customer_details);
        $user->customer()->save($customer);

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
            'city_province_id' => 'required|exists:city_province,id',
            'contact_number' => 'required|min:7|max:11'
        );

        $validator = Validator::make($registration_details, $options);

        return $validator;
    }
}
