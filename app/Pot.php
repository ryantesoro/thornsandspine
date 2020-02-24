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
    public function getPots($pot_name, $with_trashed)
    {
        $list_of_pots = Pot::select(['id', 'name', 'active']);
        if ($pot_name != '' || !empty($product_name)) {
            $pot_name = '%'.$pot_name.'%';
            $list_of_pots->whereRaw('name LIKE ?', [
                $pot_name
            ]);
        }

        if (!$with_trashed) {
            $list_of_pots = $list_of_pots->where('active', '1');
        }

        return $list_of_pots->get()->sortBy('created_at');
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

    //Update pot
    public function updatePot($pot_id, $pot_details)
    {
        $pot_new_details = [
            'name' => $pot_details['pot_name'],
            'description' => $pot_details['pot_description']
        ];

        $pot = Pot::where('id', $pot_id)
            ->update($pot_new_details);
        
        return $pot;
    }

    //Change status of pot
    public function changePotStatus($pot_id, $active)
    {
        $pot = Pot::where('id', $pot_id)
            ->update(['active' => $active]);

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
