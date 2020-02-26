<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = $this->configuration()->getConfigurations();
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
        return view('configuration')->with('configuration', $config);
    }
}
