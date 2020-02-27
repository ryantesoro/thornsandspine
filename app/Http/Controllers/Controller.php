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
use App\Order;
use App\Cart;
use App\Configuration;
use App\Faq;
use App\OrderProduct;
use App\Pot;
use App\Recipient;
use App\Screenshot;
use App\ShippingFee;
use App\ShippingProvince;

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

    public function order()
    {
        $order = new Order();
        return $order;
    }
    
    public function cart()
    {
        $cart = new Cart();
        return $cart;
    }

    public function pot()
    {
        $pot = new Pot();
        return $pot;
    }

    public function shipping_fee()
    {
        $shipping_fee = new ShippingFee();
        return $shipping_fee;
    }

    public function shipping_province()
    {
        $shipping_province = new ShippingProvince();
        return $shipping_province;
    }

    public function faq()
    {
        $faq = new Faq();
        return $faq;
    }

    public function screenshot()
    {
        $screenshot = new Screenshot();
        return $screenshot;
    }

    public function order_product()
    {
        $order_product = new OrderProduct();
        return $order_product;
    }

    public function configuration()
    {
        $configuration = new Configuration();
        return $configuration;
    }

    public function recipient()
    {
        $recipient = new Recipient();
        return $recipient;
    }
}
