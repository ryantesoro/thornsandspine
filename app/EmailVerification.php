<?php

namespace App;

use App\Notifications\EmailVerification as AppEmailVerification;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailVerification extends Model
{
    protected $table = 'email_verifications';

    protected $fillable = [
        'user_id', 'token', 'expires_at',
    ];

    public $timestamps = true;

    //Insert new verification token
    public function insertVerification($user_id)
    {
        $verification_details = [
            'user_id' => $user_id,
            'token' => str_random(8),
            'expires_at' => Carbon::now()->addMinutes(30)
        ];

        $verification = EmailVerification::create($verification_details);

        return $verification;
    }

    //Checks if verification token is valid/exists
    public function isVerificationValid($token)
    {
        $verification = EmailVerification::where('token', $token)
            ->whereDate('expires_at', '<', Carbon::now())
            ->count();

        return $verification > 0;
    }

    public function getVerification($token)
    {
        $verification = EmailVerification::where('token', $token)
            ->get()
            ->first();

        return $verification;
    }

    //Checks and deletes duplicate tokens for an account
    public function checkVerification($user_id)
    {
        if ($this->countVerification($user_id) > 0) {
            $this->deleteVerification($user_id);
        }
    }

    private function countVerification($user_id)
    {
        $verification_count = EmailVerification::where('user_id', $user_id)->count();
        return $verification_count;
    }

    private function deleteVerification($user_id)
    {
        EmailVerification::where('user_id')->delete();
    }
}
