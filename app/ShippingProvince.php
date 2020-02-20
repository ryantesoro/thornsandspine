<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingProvince extends Model
{
    protected $table = "shipping_provinces";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    //Get province
    public function getProvince($province_id)
    {
        $province = ShippingProvince::where('id', $province_id)
            ->get()
            ->first();

        return $province;         
    }

    //Get Province by name
    public function getProvinceByName($province_name)
    {
        $province = ShippingProvince::where('name', $province_name)
            ->get()
            ->first();

        return $province;
    }

    //Get all provinces
    public function getProvinces()
    {
        $provinces = ShippingProvince::all()
            ->sortBy('name');

        return $provinces;
    }

    //Store province
    public function storeProvince($province_name)
    {
        $store_province = ShippingProvince::create(['name' => $province_name]);

        return $store_province;
    }

    //Check if province exist
    public function provinceExists($province_id)
    {
        $province = ShippingProvince::where('id', $province_id)->count();

        return $province != 0;
    }

    //Update Province
    public function updateProvince($province_id, $province_name)
    {
        $update_province = ShippingProvince::where('id', $province_id)
            ->update(['name' => $province_name]);
        
        return $update_province;
    }

    public function shipping_fee()
    {
        return $this->hasMany('App\ShippingFee', 'province_id', 'id');
    }
}
