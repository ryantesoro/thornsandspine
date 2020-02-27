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

    public function shipping_fee()
    {
        return $this->belongsToMany('App\ShippingFee', 'courier_shipping_fee');
    }
}
