<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pot extends Model
{
    use SoftDeletes;

    protected $table = "pots";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name', 'description'
    ];

    public $timestamps = true;

    //Fetch all pots
    public function getPots()
    {
        $pots = Pot::withTrashed()->get();

        return $pots;
    }

    //Store pot
    public function storePot($pot_details)
    {
        $pot_new_details = [
            'name' => $pot_details['pot_name'],
            'description' => $pot_details['pot_description']
        ];
        
        $pot = Pot::create($pot_new_details);
        return $pot;
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_product');
    }
}
