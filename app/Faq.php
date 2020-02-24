<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'title', 'body'
    ];

    public $timestamps = false;

    protected $hidden = ['pivot'];
}
