<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingProvince extends Model
{
    protected $table = "shipping_fees";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function shipping_fee()
    {
        return $this->hasMany('App\ShippingFee', 'province_id', 'id');
    }
}
