<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Customer extends Model
{
    protected $table = "customers";

    protected $fillable = [
        'first_name', 'last_name', 'address', 'city', 'contact_number'
    ];

    protected $hidden = ['pivot'];

    public $timestamps = false;

    //Register New Customer
    public function registerCustomer($customer_details)
    {
        $customer = Customer::create($customer_details);
        return $customer;
    }

    //Get customer by user id
    public function getCustomerDetailsByUser($user_id)
    {
        $user = User::find($user_id);
        $customer_id = $user->customer()->value('id');
        $customer = Customer::find($customer_id);

        return $customer;
    }

    //Get Customer Cart
    public function getCustomerCart($customer_model)
    {
        $cart = $customer_model->cart();

        return $cart;
    }

    //Get Customer Orders
    public function getCustomerOrders($customer_model)
    {
        $orders = $customer_model->order();

        return $orders;
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'user_customer');
    }

    public function shipping()
    {
        return $this->belongsToMany('App\Shipping', 'customer_shipping');
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'customer_order');
    }
    
    public function cart()
    {
        return $this->belongsToMany('App\Cart', 'customer_cart');
    }
}
