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
        $customer_details = auth()->user()->customer->first()->toArray();

        $user_details = [
            'email' => auth()->user()->email,
            'first_name' => ucwords($customer_details['first_name']),
            'last_name' => ucwords($customer_details['last_name']),
            'contact_number' => $customer_details['contact_number'],
            'address' => ucwords($customer_details['address']),
            'province' => $customer_details['province'],
            'city' => $customer_details['city'],
            'loyalty_points' => $customer_details['loyalty_points']
        ];

        return response()->json([
            'success' => true,
            'data' => $user_details
        ]);
    }

    public function edit(Request $request)
    {
        $customer_details = auth()->user()->customer->first()->toArray();

        $province_id = $this->province()->getProvinceByName($customer_details['province'])->id;
        $city_province_id = $this->city()->getCityByNameAndProvince($customer_details['city'], $province_id)->id;

        $user_details = [
            'email' => auth()->user()->email,
            'first_name' => ucwords($customer_details['first_name']),
            'last_name' => ucwords($customer_details['last_name']),
            'contact_number' => $customer_details['contact_number'],
            'address' => ucwords($customer_details['address']),
            'province_id' => $province_id,
            'city_province_id' => $city_province_id,
            'loyalty_points' => $customer_details['loyalty_points']
        ];

        $provinces = $this->province()->getProvinces();
        $plucked_provinces = $provinces->pluck('name', 'id');

        return response()->json([
            'success' => true,
            'data' => [
                'user_details' => $user_details,
                'provinces' => $plucked_provinces
            ]
        ]);
    }

    public function update(Request $request)
    {
        $options = [
            'first_name' => 'required|min:3|max:30',
            'last_name' => 'required|min:3|max:30',
            'address' => 'required',
            'city_province_id' => 'required|exists:city_province,id',
            'contact_number' => 'required|min:7|max:11'
        ];

        if ($request->has('password') && $request->has('password1')) {
            $options['password'] = 'required|min:8|max:21|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/';
            $options['password1'] = 'required|min:8|max:21|same:password';
        }

        $validator = Validator::make($request->all(), $options);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Input',
                'errors' => $validator->errors()
            ]);
        }

        if ($request->has('password')) {
            $change_password = $this->user()->changePassword(['id' => auth()->user()->id],$request->post('password'));
        }

        $city_id = $request->post('city_province_id');
        $city = $this->city()->getCity($city_id);
        $province = $this->province()->getProvince($city->province_id);

        $customer_details = [
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'address' => $request->post('address'),
            'contact_number' => $request->post('contact_number'),
            'city' => $city->city,
            'province' => $province->name
        ];

        $update_customer = $this->customer()->updateCustomer($customer_details, auth()->user()->customer->first()->id);

        return response()->json([
            'success' => true,
            'msg' => 'You have successfully updated your account information'
        ]);
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
            'password' => 'required|min:8|max:21|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
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
