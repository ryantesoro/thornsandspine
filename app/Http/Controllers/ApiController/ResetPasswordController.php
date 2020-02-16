<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Validator;


class ResetPasswordController extends Controller
{
    public function req(Request $request)
    {
        $email = $request->post('email');

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Email Address is invalid'
            ]);
        }

        $user = $this->user()->getUser(['email' => $email]);

        $reset_password = $this->reset_password()->getResetPassword(['email' => $email]);
        $token = str_random(8);

        if (empty($reset_password) || $reset_password == null) {
            $reset_password = $this->reset_password()->insertToken($email, $token);
        } else {
            $reset_password = $this->reset_password()->updateToken($email, $token);
        }

        $user->notify(new ResetPassword($token));

        return response()->json([
            'success' => true,
            'msg' => 'Reset password code has been sent to your email!'
        ]);
    }

    public function verify(Request $request)
    {
        $token = $request->post('token');

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|exists:password_resets,token'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'The token is invalid!'
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Token accepted, proceed to change your password.'
        ]);
    }

    public function reset(Request $request)
    {
        $new_credentials = [
            'email' => $request->post('email'),
            'password' => $request->post('password'),
            'password1' => $request->post('password1'),
            'token' => $request->post('token')
        ];

        $validator = Validator::make($new_credentials, [
            'email' => 'required|exists:users,email',
            'password' => 'required|min:8|max:21',
            'password1' => 'required|min:8|max:21|same:password',
            'token' => 'required|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Input',
                'errors' => $validator->errors()
            ]);
        }

        $token = $request->post('token');        
        $delete_token = $this->reset_password()->deleteToken($new_credentials['token']);

        $update_password = $this->user()->changePassword(['email' => $new_credentials['email']], $new_credentials['password']);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully changed password!'
        ]);
    }
}
