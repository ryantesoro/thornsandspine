<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = $this->configuration()->getConfigurations();

        unset($configurations['card_number_1']);
        unset($configurations['card_number_2']);
        unset($configurations['card_number_3']);
        unset($configurations['card_number_4']);
        unset($configurations['card_number']);

        return response()->json([
            'success' => true,
            'data' => $configurations
        ]);
    }

    public function bank()
    {
        $bank_name =  $this->configuration()->getConfiguration('bank_name');
        $card_number = $this->configuration()->getConfiguration('card_number');
        $bank_configuration = [
            $bank_name->name => $bank_name->value,
            'account_number' => $card_number->value
        ];

        return response()->json([
            'success' => true,
            'data' => $bank_configuration
        ]);
    }

    public function gcash()
    {
        $gcash_mobile_number =  $this->configuration()->getConfiguration('gcash_number');
        $gcash_configuration = [
            $gcash_mobile_number->name => $gcash_mobile_number->value
        ];

        return response()->json([
            'success' => true,
            'data' => $gcash_configuration
        ]);
    }

    public function contact()
    {
        $contact_number =  $this->configuration()->getConfiguration('contact_number');
        $email =  $this->configuration()->getConfiguration('email');
        $contact_configuration = [
            $contact_number->name => $contact_number->value,
            $email->name => $email->value
        ];

        return response()->json([
            'success' => true,
            'data' => $contact_configuration
        ]);
    }
}
