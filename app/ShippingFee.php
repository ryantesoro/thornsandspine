<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    protected $table = "shipping_fees";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'province_id', 'city', 'price'
    ];

    public $timestamps = false;
}
