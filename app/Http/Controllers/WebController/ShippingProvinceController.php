<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ShippingProvinceController extends Controller
{
    public function index()
    {
        $provinces = $this->shipping_province()->getProvinces();
        return view('pages.shipping_province.shipping_province_index')
            ->with('provinces', $provinces);
    }

    public function show($province_id)
    {
        $province_details = $this->shipping_province()->getProvince($province_id);
        return view('pages.shipping_province.shipping_province_show')
            ->with('province_details', $province_details);
    }

    public function create()
    {
        return view('pages.shipping_province.shipping_province_create');
    }

    public function store(Request $request)
    {
        $province_name = strtolower($request->post('shipping_province'));

        $validator = Validator::make(['shipping_province' => $province_name], [
            'shipping_province' => 'required|unique:shipping_provinces,name'
        ]);

        if ($validator->fails()) {
            Alert::warning('Add Province Failed', 'Invalid Input');
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        $province = $this->shipping_province()->storeProvince($province_name);

        Alert::success('Add Province Successful', 'Success!');
        return redirect()->route('admin.shipping_province.index');
    }

    public function edit($province_id)
    {
        if (!$this->shipping_province()->provinceExists($province_id)) {
            Alert::error('Edit Province Failed', 'Province does not exist!');
            return redirect()->route('admin.shipping_province.index');
        }

        $province_details = $this->shipping_province()->getProvince($province_id);

        return view('pages.shipping_province.shipping_province_edit')
            ->with('province_details', $province_details);
    }

    public function update(Request $request, $province_id)
    {
        $province_name = strtolower($request->post('shipping_province'));

        $validator = Validator::make(['shipping_province' => $province_name], [
            'shipping_province' => 'required|unique:shipping_provinces,name'
        ]);

        if ($validator->fails()) {
            Alert::warning('Update Province Failed', 'Invalid Input');
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        $update_province = $this->shipping_province()->updateProvince($province_id, $province_name);
        Alert::success('Update Province Successful', 'Success!');
        return redirect()->route('admin.shipping_province.index');
    }
}
