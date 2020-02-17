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
        $pots = Pot::withTrashed();
        return $pots;
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_product');
    }
}
