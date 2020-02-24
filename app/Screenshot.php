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

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_screenshot');
    }
}
