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

    public function create()
    {
        return view('pages.shipping_province.shipping_province_create');
    }

    public function store(Request $request)
    {
        $province_name = $request->post('shipping_province');

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
}
