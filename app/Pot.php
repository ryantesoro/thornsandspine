<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pot extends Model
{
    protected $table = "pots";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'name', 'description', 'active'
    ];

    public $timestamps = true;

    //Get Pot
    public function getPot($pot_id)
    {
        $pot = Pot::where('id', $pot_id)
            ->get()
            ->first();
        
        return $pot;
    }

    //Fetch all pots
    public function getPots()
    {
        $pots = Pot::all();

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

    //Check if pot exists
    public function potExists($pot_id)
    {
        $pot = Pot::find($pot_id)->count();

        return $pot != 0;
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_product');
    }
}
