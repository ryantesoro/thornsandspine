<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $table = 'screenshots';

    const UPDATED_AT = null;

    protected $fillable = [
        'file_name'
    ];

    public $timestamps = true;

    //Store Screenshot
    public function storeScreenshot($screenshot_details)
    {
        $store_screenshot = Screenshot::create($screenshot_details);

        return $store_screenshot;
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_screenshot');
    }
}
