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

    public function getProvinces()
    {
        $provinces = ShippingProvince::all();

        return $provinces;
    }

    public function storeProvince($province_name)
    {
        $store_province = ShippingProvince::create(['name' => $province_name]);

        return $store_province;
    }

    public function shipping_fee()
    {
        return $this->hasMany('App\ShippingFee', 'province_id', 'id');
    }
}
