<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = "shippings";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'address', 'city', 'region', 'location_type'
    ];

    public $timestamps = false;

    public function addShipping($shipping_details)
    {
        $new_shipping_details = [
            'address' => $shipping_details['shipping_address'],
            'city' => $shipping_details['shipping_city'],
            'region' => $shipping_details['shipping_region'],
            'location_type' => $shipping_details['shipping_location_type']
        ];

        $shipping = Shipping::create($new_shipping_details);
        return $shipping;
    }

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'user_customer');
    }
}
