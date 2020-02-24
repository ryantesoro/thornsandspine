<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $table = 'password_resets';

    const UPDATED_AT = null;

    protected $fillable = [
        'email', 'token'
    ];

    public $timestamps = true;

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_screenshot');
    }
}
