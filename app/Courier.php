<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $table = "couriers";

    protected $fillable = [
        'name'
    ];

    protected $hidden = ['pivot'];

    public $timestamps = false;

    //Get Couriers
    public function getCouriers()
    {
        $couriers = Courier::select('*')
            ->get();
            
        return $couriers;
    }

    //Store Courier
    public function storeCourier($courier_details)
    {
        $store_courier = Courier::create($courier_details);

        return $store_courier;
    }
    
    public function shipping_fee()
    {
        return $this->belongsToMany('App\ShippingFee', 'courier_shipping_fee');
    }
}
