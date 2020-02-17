<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\User;
use App\Product;
use App\Customer;
use App\Shipping;
use App\EmailVerification;
use App\ResetPassword;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        //Remove image size restriction
        ini_set('memory_limit','256M');
    }

    public function user()
    {
        $user = new User();
        return $user;
    }

    public function product()
    {
        $product = new Product();
        return $product;
    }

    public function customer()
    {
        $customer = new Customer();
        return $customer;
    }

    public function shipping()
    {
        $shipping = new Shipping();
        return $shipping;
    }

    public function verification()
    {
        $verification = new EmailVerification();
        return $verification;
    }

    public function reset_password()
    {
        $reset_password = new ResetPassword();
        return $reset_password;
    }
}
