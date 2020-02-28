<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $table = "recipients";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'first_name', 'last_name', 'address',
        'contact_number', 'email'
    ];

    public $timestamps = false;

    //Get Recipient
    public function getRecipient($recipient_id)
    {
        $recipient = Recipient::where('id', $recipient_id)
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
