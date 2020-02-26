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

        $config = [];
        foreach ($configurations as $configuration) {
            $config[$configuration->name] = $configuration->value;
        }

        $card_number = $config['card_number'];
        $config['card_number_1'] = substr($card_number, 0, 4);
        $config['card_number_2'] = substr($card_number, 4, 4);
        $config['card_number_3'] = substr($card_number, 8, 4);
        $config['card_number_4'] = substr($card_number, 12, 4);

        unset($config['card_number']);

        return $config;
    }

    //Update Configuration
    public function updateConfiguration($name, $value)
    {
        $update_configuration = Configuration::where('name', $name)
            ->update(['value' => $value]);

        return $update_configuration;
    }
}
