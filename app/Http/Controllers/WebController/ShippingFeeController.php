<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ShippingFeeController extends Controller
{
    public function index()
    {
        $shipping_fees = $this->shipping_fee()->getShippingFees();

        foreach ($shipping_fees as $shipping_fee) {
            $shipping_fee['province'] = $this->getProvinceName($shipping_fee['province_id']);
        }

        return view('pages.shipping_fee.shipping_fee_index')
            ->with('shipping_fees', $shipping_fees);
    }

    public function show($shipping_fee_id)
    {
        if (!$this->shipping_fee()->shippingFeeExists($shipping_fee_id)) {
            Alert::error('View Shipping Fee Failed', 'Shipping Fee does not exist!');
            return redirect()->route('admin.shipping_fee.index');
        }

        $shipping_fee_details = $this->shipping_fee()->getShippingFee($shipping_fee_id);
        $shipping_fee_details['province'] = $this->getProvinceName($shipping_fee_details['province_id']);

        $provinces = $this->getPluckedProvinces();

        return view('pages.shipping_fee.shipping_fee_show')
            ->with('shipping_fee_details', $shipping_fee_details)
            ->with('provinces', $provinces);
    }

    public function create()
    {
        $provinces = $this->getPluckedProvinces();

        return view('pages.shipping_fee.shipping_fee_create')
            ->with('provinces', $provinces);
    }

    public function store(Request $request)
    {
        $shipping_fee_details = [
            'shipping_province' => $request->post('shipping_province'),
            'shipping_city' => $request->post('shipping_city'),
            'shipping_price' => $request->post('shipping_price')
        ];

        $validator = Validator::make($shipping_fee_details, [
            'shipping_province' => 'required|exists:shipping_provinces,id',
            'shipping_city' => 'required',
            'shipping_price' => 'required|numeric|digits_between:1,4|min:0|not_in:0'
        ]);

        if ($validator->fails()) {
            Alert::warning('Add Shipping Fee Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $store_shipping_fee = $this->shipping_fee()->storeShippingFee($shipping_fee_details);

        Alert::success('Add Shipping Fee Successful', 'Success!');
        return redirect()->route('admin.shipping_fee.index');
    }

    public function edit($shipping_fee_id)
    {
        if (!$this->shipping_fee()->shippingFeeExists($shipping_fee_id)) {
            Alert::error('Edit Shipping Fee Failed', 'Shipping Fee does not exist!');
            return redirect()->route('admin.shipping_fee.index');
        }

        $shipping_fee_details = $this->shipping_fee()->getShippingFee($shipping_fee_id);

        $provinces = $this->getPluckedProvinces();

        return view('pages.shipping_fee.shipping_fee_edit')
            ->with('shipping_fee_details', $shipping_fee_details)
            ->with('provinces', $provinces);
    }

    public function update(Request $request, $shipping_fee_id)
    {
        $shipping_fee_details = [
            'shipping_province' => $request->post('shipping_province'),
            'shipping_city' => $request->post('shipping_city'),
            'shipping_price' => $request->post('shipping_price')
        ];

        $validator = Validator::make($shipping_fee_details, [
            'shipping_province' => 'required|exists:shipping_provinces,id',
            'shipping_city' => 'required',
            'shipping_price' => 'required|numeric|digits_between:1,4|min:0|not_in:0'
        ]);

        if ($validator->fails()) {
            Alert::warning('Update Shipping Fee Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $update_shipping_fee = $this->shipping_fee()->updateShippingFee($shipping_fee_id, $shipping_fee_details);

        Alert::success('Update Shipping Fee Successful', 'Success!');
        return redirect()->route('admin.shipping_fee.index');
    }

    private function getPluckedProvinces()
    {
        $provinces = $this->shipping_province()
            ->getProvinces()
            ->pluck('name', 'id');

        return $provinces;
    }

    private function getProvinceName($province_id)
    {
        $province = $this->shipping_province()->getProvince($province_id);
        $province_name = $province->name;

        return $province_name;
    }
}
