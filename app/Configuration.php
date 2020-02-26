<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configurations';

    protected $fillable = [
        'name', 'value'
    ];

    public $timestamps = false;

    //Get All Configurations
    public function getConfigurations()
    {
        $configurations = Configuration::select('*')
            ->get();

        return $configurations;
    }
}
