<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;



class CityController extends Controller
{
    public function index(Request $request)
    {
        $city_name = $request->get('city');
        $province_id = $request->get('province_id');

        $fetched_provinces = $this->province()->getProvinces()->pluck('name', 'id')->toArray();
        $provinces = [0 => 'All Provinces'] + $fetched_provinces;
        
        $cities = $this->city()->getCities($city_name, $province_id);

        return view('pages.city.city_index')
            ->with('cities', $cities)
            ->with('provinces', $provinces);
    }

    public function create()
    {
        $provinces = $this->province()->getProvinces()
            ->pluck('name', 'id');

        return view('pages.city.city_create')
            ->with('provinces', $provinces);
    }

    public function store(Request $request)
    {
        $city_name = $request->post('city');
        $province_id = $request->post('province');
        if ($this->city()->cityExists($city_name, $province_id, null)) {
            Alert::warning('Add City Failed', 'Invalid Input');
            return redirect()->back()
                ->withErrors('The city and province already exists!')
                ->withInput($request->all());
        }

        $store_city = $this->city()->storeCity($city_name, $province_id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Added New City ID: '.$store_city->id
        ]);

        Alert::success('Add City Successful', 'Success!');
        return redirect()->route('admin.city.index');
    }

    public function edit($city_id)
    {
        if ($this->city()->where('id', $city_id)->count() == 0) {
            Alert::error('Edit City Failed', 'City does not exist!');
            return redirect()->route('admin.city.index');
        }

        $city_details = $this->city()->getCity($city_id);

        $provinces = $this->province()->getProvinces()
            ->pluck('name', 'id');

        return view('pages.city.city_edit')
            ->with('city_details', $city_details)
            ->with('provinces', $provinces);
    }

    public function update(Request $request, $city_id)
    {
        $city_name = $request->post('city');
        $province_id = $request->post('province');

        if ($this->city()->cityExists($city_name, $province_id, $city_id)) {
            Alert::warning('Add City Failed', 'Invalid Input');
            return redirect()->back()
                ->withErrors('The city and province already exists!')
                ->withInput($request->all());
        }

        $update_city = $this->city()->updateCity($city_name, $province_id, $city_id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Updated City ID: '.$city_id
        ]);

        Alert::success('Update City Successful', 'Success!');
        return redirect()->route('admin.city.index'); 
    }
}
