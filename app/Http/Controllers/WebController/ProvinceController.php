<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = $this->province()->getProvinces();
        return view('pages.province.province_index')
            ->with('provinces', $provinces);
    }

    public function show($province_id)
    {
        $province_details = $this->province()->getProvince($province_id);
        return view('pages.province.province_show')
            ->with('province_details', $province_details);
    }

    public function create()
    {
        return view('pages.province.province_create');
    }

    public function store(Request $request)
    {
        $province_name = strtolower($request->post('province'));

        $validator = Validator::make(['province' => $province_name], [
            'province' => 'required|unique:provinces,name'
        ]);

        if ($validator->fails()) {
            Alert::warning('Add Province Failed', 'Invalid Input');
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        $province = $this->province()->storeProvince($province_name);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Added New Province ID: '.$province->id
        ]);

        Alert::success('Add Province Successful', 'Success!');
        return redirect()->route('admin.province.index');
    }

    public function edit($province_id)
    {
        if (!$this->province()->provinceExists($province_id)) {
            Alert::error('Edit Province Failed', 'Province does not exist!');
            return redirect()->route('admin.province.index');
        }

        $province_details = $this->province()->getProvince($province_id);

        return view('pages.province.province_edit')
            ->with('province_details', $province_details);
    }

    public function update(Request $request, $province_id)
    {
        $province_name = strtolower($request->post('province'));

        $validator = Validator::make(['province' => $province_name], [
            'province' => 'required|unique:provinces,name,'.$province_id
        ]);

        if ($validator->fails()) {
            Alert::warning('Update Province Failed', 'Invalid Input');
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        $update_province = $this->province()->updateProvince($province_id, $province_name);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Updated Province ID: '.$province_id
        ]);

        Alert::success('Update Province Successful', 'Success!');
        return redirect()->route('admin.province.index');
    }
}
