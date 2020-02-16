<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    protected $table = 'password_resets';

    const UPDATED_AT = null;

    protected $fillable = [
        'email', 'token'
    ];

    //Inserts token
    public function insertToken($email, $token)
    {
        $reset_password_details = [
            'email' => $email
        ];

        $reset_password = ResetPassword::create($reset_password_details);

        return $reset_password;
    }

    //Updates token
    public function updateToken($email, $token)
    {
        $reset_password = ResetPassword::where('email', $email)
                ->update(['token' => $token]);
        
        return $reset_password;
    }

    //Get 1 row
    public function getResetPassword($where)
    {
        $reset_password = ResetPassword::where($where)
                ->get()
                ->first();

        return $reset_password;
    }

    //Delete Token
    public function deleteToken($token)
    {
        $delete_token = ResetPassword::where('token', $token)
            ->delete();
        return $delete_token;
    }
}
