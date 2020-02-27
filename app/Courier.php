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

<<<<<<< HEAD
=======
    //Get Couriers
    public function getCouriers()
    {
        $couriers = Courier::select('*')
            ->get();
            
        return $couriers;
    }
    
    //Get Courier
    public function getCourier($courier_id)
    {
        $courier_details = Courier::find($courier_id)
            ->get()
            ->first();

        return $courier_details;
    }

    //Store Courier
    public function storeCourier($courier_details)
    {
        $store_courier = Courier::create($courier_details);

        return $store_courier;
    }

    //Update Courier
    public function updateCourier($courier_details, $courier_id)
    {
        $update_courier = Courier::find($courier_id)
            ->update($courier_details);

        return $update_courier;
    }
    
>>>>>>> develop
    public function shipping_fee()
    {
        return $this->belongsToMany('App\ShippingFee', 'courier_shipping_fee');
    }
}
