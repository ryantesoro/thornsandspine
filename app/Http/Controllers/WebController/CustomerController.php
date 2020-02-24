<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = $this->customer()->getCustomers($request->get('filter'), $request->get('search'));

        $filters = [
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'email' => 'Email',
            'province' => 'Province',
            'city' => 'City'
        ];

        return view('pages.customer.customer_index')
            ->with('filters', $filters)
            ->with('customers', $customers);
    }

    public function show($customer_id)
    {

    }
}
