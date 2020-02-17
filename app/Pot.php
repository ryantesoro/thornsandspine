<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pot extends Model
{
    protected $table = "pots";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name', 'description'
    ];

    public $timestamps = true;

    //Fetch all pots
    public function getPots()
    {
        $pots = Pot::all();
        return $pots;
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_product');
    }
}
