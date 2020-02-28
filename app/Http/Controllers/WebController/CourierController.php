<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class CourierController extends Controller
{
    public function index()
    {
        $couriers = $this->courier()->getCouriers();

        return view('pages.courier.courier_index')->with('couriers', $couriers);
    }

    public function create()
    {
        return view('pages.courier.courier_create');
    }

    public function store(Request $request)
    {
        $shipping_agent_details = [
            'shipping_agent_name' => $request->post('shipping_agent_name')
        ];

        $validator = Validator::make($shipping_agent_details,[
            'shipping_agent_name' => 'required|unique:couriers,name'
        ]);

        if ($validator->fails()) {
            Alert::warning('Add Shipping Agent Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $store_courier = $this->courier()->storeCourier([
            'name' => $shipping_agent_details['shipping_agent_name']
        ]);

        Alert::success('Add Shipping Agent Successful', 'Success!');
        return redirect()->route('admin.courier.index');
    }

    public function edit($courier_id)
    {
        $courier_details = $this->courier()->getCourier($courier_id);

        return view('pages.courier.courier_edit')
            ->with('courier_details', $courier_details);
    }

    public function update(Request $request, $courier_id)
    {
        $shipping_agent_details = [
            'shipping_agent_name' => $request->post('shipping_agent_name')
        ];

        $validator = Validator::make($shipping_agent_details,[
            'shipping_agent_name' => 'required|unique:couriers,name,'.$courier_id
        ]);

        if ($validator->fails()) {
            Alert::warning('Add Shipping Agent Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $update_courier = $this->courier()->updateCourier(['name' => $shipping_agent_details['shipping_agent_name']], $courier_id);
        
        Alert::success('Update Shipping Agent Successful', 'Success!');
        return redirect()->route('admin.courier.index');
    }
}
