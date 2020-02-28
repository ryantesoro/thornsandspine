<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = "city_province";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'city', 'province_id'
    ];

    public $timestamps = false;

    //Get City
    public function getCity($city_id)
    {
        $city_details = City::find($city_id)
            ->get()
            ->first();
        
        return $city_details;
    }

    //Get Cities
    public function getCities($city_name, $province_id)
    {
        $city_provinces = City::select('id', 'city', 'province_id');
        
        if ($city_name != '' || !empty($city_name)) {
            $search_query = '%'.$city_name.'%';
            $city_provinces->whereRaw('city LIKE ?', [
                $search_query
            ]);
        }
        
        if ($province_id != 0 && ($province_id != '' || !empty($province_id))) {
            $city_provinces->where('province_id', $province_id);
        }

        return $city_provinces->get()
            ->sortBy('created_at');

        return $city_provinces;
    }

    //Store City
    public function storeCity($city_name, $province_id)
    {
        $store_city = City::create(['city' => $city_name, 'province_id' => $province_id]);

        return $store_city;
    }

    //Update City
    public function updateCity($city_name, $province_id, $city_id)
    {
        $update_city = City::find($city_id)
            ->update(['city' => $city_name, 'province_id' => $province_id]);

        return $update_city;
    }

    //Check if city exists
    public function cityExists($city_name, $province_id, $city_id)
    {
        $check_duplicate = City::where(['city' => $city_name, 'province_id' => $province_id]);

        if ($city_id != null) {
            $check_duplicate = $check_duplicate->where('id', '<>', $city_id);
        }

        return $check_duplicate->get()->count() != 0;
    }
}
