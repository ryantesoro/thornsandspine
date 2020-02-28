<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Province extends Model
{
    protected $table = "provinces";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    //Get province
    public function getProvince($province_id)
    {
        $province = Province::where('id', $province_id)
            ->get()
            ->first();

        return $province;         
    }

    //Get Province by name
    public function getProvinceByName($province_name)
    {
        $province = Province::where('name', $province_name)
            ->get()
            ->first();

        return $province;
    }

    //Get all provinces
    public function getProvinces()
    {
        $provinces = Province::all()
            ->sortBy('name');

        return $provinces;
    }

    //Store province
    public function storeProvince($province_name)
    {
        $store_province = Province::create(['name' => $province_name]);

        return $store_province;
    }

    //Check if province exist
    public function provinceExists($province_id)
    {
        $province = Province::where('id', $province_id)->count();

        return $province != 0;
    }

    //Update Province
    public function updateProvince($province_id, $province_name)
    {
        $update_province = Province::where('id', $province_id)
            ->update(['name' => $province_name]);
        
        return $update_province;
    }

    public function city()
    {
        return $this->hasMany('App\City', 'province_id', 'id');
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}
