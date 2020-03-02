<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;


class Customer extends Model
{
    protected $table = "customers";

    protected $fillable = [
        'first_name', 'last_name', 'address', 'city', 'contact_number', 'province'
    ];

    protected $hidden = ['pivot'];

    public $timestamps = false;

    public function getCustomers($column, $value)
    {
        $customers = DB::table('customers')
            ->selectRaw('customers.id, customers.first_name, customers.last_name, users.email, customers.province, customers.city, customers.address, customers.contact_number')
            ->leftJoin('user_customer', function ($query) {
                $query->on('user_customer.customer_id', 'customers.id');
            })
            ->leftJoin('users', function ($query) {
                $query->on('users.id', 'user_customer.user_id');
            });
        
        if ($column != null && $value != null) {
            $search = '%'.$value.'%';
            $customers = $customers->whereRaw('customers.'.$column.' LIKE ?', [
                $search
            ]);
        } else if ($column == 'email') {
            $search = '%'.$value.'%';
            $customers = $customers->whereRaw('users.email LIKE ?', [
                $search
            ]);

        }

        return $customers->get()->sortBy('customers.last_name');;
    }

    //Get Customer
    public function getCustomer($customer_id)
    {
        $customer_details = Customer::where('id', $customer_id)
            ->get()
            ->first();

        return $customer_details;
    }

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

    //Update Customer
    public function updateCustomer($customer_details, $customer_id)
    {
        $update_customer = Customer::where('id', $customer_id)
            ->update($customer_details);
        
        return $update_customer;
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
