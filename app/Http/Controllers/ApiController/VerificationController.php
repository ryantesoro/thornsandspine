<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Notifications\EmailVerification;


class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $token = $request->get('token');

        $validator = Validator::make(['token' => $token], [
            'token' => 'required|exists:email_verifications,token'
        ]);

        if ($validator->fails() || $this->verification()->isVerificationValid($token)) {
            return response()->json([
                'success' => false,
                'msg' => 'The verification token is invalid or expired!'
            ]);
        }

        $verification = $this->verification()->getVerification($token);
        
        $user_id = $verification->user_id;
        
        $user = $this->user()->getUser(['id' => $user_id]);
        $verify_user = $this->user()->verifyUser($user_id);

        return response()->json([
            'success' => true,
            'msg' => 'You have successfully verified your email address!'
        ]);
    }

    public function resend(Request $request)
    {
        $email = $request->get('email');

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|exists:users,email|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Email address is invalid'
            ]);
        }

        $user = $this->user()->getUser(['email' => $email]);

        $verification = $this->verification()->insertVerification($user->id);

        $user->notify(new EmailVerification($verification->token));

        return response()->json([
            'success' => true,
            'msg' => 'Email Verification sent!'
        ]);
    }
}
