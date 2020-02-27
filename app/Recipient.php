<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $table = "products";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'first_name', 'last_name', 'address',
        'contact_number', 'email'
    ];

    public $timestamps = false;

    //Get Recipient
    public function getRecipient($order_id)
    {
        $recipient = Recipient::where('order_id', $order_id)
            ->get()
            ->first();

        return $recipient;
    }

    //Store Recipient
    public function storeRecipient($recipient_details)
    {
        $store_recipient = Recipient::create($recipient_details);

        return $store_recipient;
    }
}
