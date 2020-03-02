<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ShippingFeeController extends Controller
{
    public function index(Request $request)
    {
        $courier_id = $request->get('courier_id');
        $province_id = $request->get('province_id');

        $shipping_fees = $this->shipping_fee()->getShippingFees($courier_id, $province_id);
        
        $fetched_couriers = $this->courier()->getCouriers()->pluck('name', 'id')->toArray();
        $couriers = [0 => 'All Couriers'] + $fetched_couriers;

        $fetched_provinces = $this->province()->getProvinces()->pluck('name', 'id')->toArray();
        $provinces = [0 => 'All Provinces'] + $fetched_provinces;

        return view('pages.shipping_fee.shipping_fee_index')
            ->with('shipping_fees', $shipping_fees)
            ->with('couriers', $couriers)
            ->with('provinces', $provinces);
    }

    public function show($shipping_fee_id)
    {
        if (!$this->shipping_fee()->shippingFeeExists($shipping_fee_id)) {
            Alert::error('View Shipping Fee Failed', 'Shipping Fee does not exist!');
            return redirect()->route('admin.shipping_fee.index');
        }

        $shipping_fee_details = $this->shipping_fee()->getShippingFee($shipping_fee_id);
        $courier_name = $shipping_fee_details->courier()->get()->first()->name;
        $city_province = $this->city()->getCity($shipping_fee_details->city_province_id);
        $city_name = $city_province->city;
        $province_name = $this->province()->getProvince($city_province->province_id)->name;

        return view('pages.shipping_fee.shipping_fee_show')
            ->with('courier_name', $courier_name)
            ->with('province_name', $province_name)
            ->with('city_name', $city_name)
            ->with('shipping_fee_details', $shipping_fee_details);
    }

    public function create()
    {
        $provinces = $this->province()->getProvinces()->pluck('name', 'id')->toArray();
        $fetched_cities = $this->city()->getCities(null, null);

        $checker = [];
        foreach($fetched_cities as $city) {
            $province_id = strVal($city->province_id);
            $checker[$province_id][$city->id] = ucwords($city->city);
        }

        foreach($provinces as $id => $name) {
            if (!array_key_exists($id, $checker)) {
                unset($provinces[$id]);
            }
        }

        $couriers = $this->courier()->getCouriers()->pluck('name', 'id')->toArray();

        return view('pages.shipping_fee.shipping_fee_create')
            ->with('checker', $checker)
            ->with('provinces', $provinces)
            ->with('couriers', $couriers);
    }

    public function store(Request $request)
    {
        $courier_id = $request->post('shipping_agent');
        $city_province_id = $request->post('shipping_city');
        $shipping_price = $request->post('shipping_price');

        $shipping_fee_details = [
            'shipping_agent' => $courier_id,
            'shipping_city' => $city_province_id,
            'shipping_price' => $shipping_price
        ];

        $validator = Validator::make($shipping_fee_details, [
            'shipping_agent' => 'required|exists:couriers,id',
            'shipping_city' => 'required|exists:city_province,id',
            'shipping_price' => 'required|numeric|digits_between:1,4|min:0|not_in:0'
        ]);

        if ($validator->fails()) {
            Alert::warning('Add Shipping Fee Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        if ($this->shipping_fee()->duplicateExists($courier_id, $city_province_id, null)) {
            Alert::warning('Add Shipping Fee Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors([['This shipping fee already exists!']]);
        }

        $store_shipping_fee = $this->shipping_fee()->storeShippingFee([
            'city_province_id' => $city_province_id,
            'price' => $shipping_price
        ]);
        
        $this->courier()->getCourier($courier_id)->shipping_fee()->save($store_shipping_fee);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Added New Shipping Fee ID: '.$store_shipping_fee->id
        ]);

        Alert::success('Add Shipping Fee Successful', 'Success!');
        return redirect()->route('admin.shipping_fee.index');
    }

    public function edit($shipping_fee_id)
    {
        $provinces = $this->province()->getProvinces()->pluck('name', 'id')->toArray();
        $fetched_cities = $this->city()->getCities(null, null);

        $checker = [];
        foreach($fetched_cities as $city) {
            $province_id = strVal($city->province_id);
            $checker[$province_id][$city->id] = ucwords($city->city);
        }

        foreach($provinces as $id => $name) {
            if (!array_key_exists($id, $checker)) {
                unset($provinces[$id]);
            }
        }

        $couriers = $this->courier()->getCouriers()->pluck('name', 'id')->toArray();

        $shipping_fee_details = $this->shipping_fee()->getShippingFee($shipping_fee_id);
        $province_id = $this->city()->getCity($shipping_fee_details->city_province_id)->province_id;
        $current_courier = $shipping_fee_details->courier()->get()->first()->id;

        return view('pages.shipping_fee.shipping_fee_edit')
            ->with('shipping_fee_details', $shipping_fee_details)
            ->with('current_courier', $current_courier)
            ->with('couriers', $couriers)
            ->with('province_id', $province_id)
            ->with('checker', $checker)
            ->with('provinces', $provinces);
    }

    public function update(Request $request, $shipping_fee_id)
    {
        $shipping_fee_details = [
            'shipping_price' => $request->post('shipping_price')
        ];

        $validator_options = [
            'shipping_price' => 'required|numeric|digits_between:1,4|min:0|not_in:0'
        ];

        $new_shipping_fee_details = [
            'price' => $shipping_fee_details['shipping_price']
        ];

        if (auth()->user()->access_level == 2) {
            $courier_id = $request->post('shipping_agent');
            $city_province_id = $request->post('shipping_city');

            if ($this->shipping_fee()->duplicateExists($courier_id, $city_province_id, $shipping_fee_id)) {
                Alert::warning('Update Shipping Fee Failed', 'Invalid Input');
                return redirect()->back()
                    ->withInput($request->all())
                    ->withErrors([['This shipping fee already exists!']]);
            }

            $validator_options['shipping_location'] = 'required|exists:city_province,id';
            $shipping_fee_details['shipping_location'] = $city_province_id;
            
            $new_shipping_fee_details['city_province_id'] = $shipping_fee_details['shipping_location'];
        }

        $validator = Validator::make($shipping_fee_details, $validator_options);
        
        if ($validator->fails()) {
            Alert::warning('Update Shipping Fee Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $update_shipping_fee = $this->shipping_fee()->updateShippingFee($shipping_fee_id, $new_shipping_fee_details);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Updated Shipping Fee ID: '.$shipping_fee_id
        ]);

        Alert::success('Update Shipping Fee Successful', 'Success!');
        return redirect()->route('admin.shipping_fee.index');
    }
}
