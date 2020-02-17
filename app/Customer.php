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

    public function getCustomerDetailsByUser($user_id)
    {
        $user = User::find($user_id);
        $customer_id = $user->customer()->value('id');
        $customer = Customer::find($customer_id);

        return $customer;
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'user_customer');
    }

    public function shipping()
    {
        return $this->belongsToMany('App\Shipping', 'customer_shipping');
    }

    public function cart()
    {
        return $this->belongsToMany('App\Cart', 'customer_cart');
    }
}
