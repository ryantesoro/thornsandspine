<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Carbon\Carbon;


class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email', 'password', 'access_level',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Setting up new api token
    public function setNewApiToken()
    {
        $this->api_token = Str::uuid();
        $this->save();
    }

    //Register New User
    public function registerUser($credentials)
    {
        $credentials['password'] = bcrypt($credentials['password']);
        return User::create($credentials);
    }

    //Get User
    public function getUser($user_details)
    {
        $user = User::where($user_details)
            ->get()
            ->first();
            
        return $user;
    }

    //Verify User Email
    public function verifyUser($user_id)
    {
        $user = User::where('id', $user_id)
            ->update(['email_verified_at' => Carbon::now()]);
        return $user;
    }

    //Change User Password
    public function changePassword($where, $password)
    {
        $user = User::where($where)
            ->update(['password' => bcrypt($password)]);
        
        return $user;
    }

    //Check if user is verified
    public function userVerified($user_id)
    {
        $user = User::where('id', $user_id)
            ->get()
            ->first();

        return ($user->email_verified_at != null || !empty($user->email_verified_at));
    }

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'user_customer');
    }
}
