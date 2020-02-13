<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use RealRashid\SweetAlert\Facades\Alert;


class UsersController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request) 
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $email = $request->get('login_email');
        $password = $request->get('login_password');

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $login_attempt = auth()->attempt($credentials);

        if (!$login_attempt) {
            $this->incrementLoginAttempts($request);
            Alert::error('Login Failed', 'Invalid Email/Password');
            return redirect()->back()->withInput();
        }

        Alert::success('Login Success', 'Successfully Logged In!');
        return redirect()->back();
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
