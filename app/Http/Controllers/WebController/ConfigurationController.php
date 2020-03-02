<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = $this->configuration()->getConfigurations();

        return view('configuration')->with('configuration', $configurations);
    }

    public function update(Request $request)
    {
        $configuration_details = [
            'contact_number' => $request->post('contact_number'),
            'email' => $request->post('email'),
            'address' => $request->post('address'),
            'bank_name' => $request->post('bank_name'),
            'card_number_1' => $request->post('card_number_1'),
            'card_number_2' => $request->post('card_number_2'),
            'card_number_3' => $request->post('card_number_3'),
            'card_number_4' => $request->post('card_number_4'),
            'gcash_number' => $request->post('gcash_number')
        ];

        $validator = Validator::make($configuration_details, [
            'address' => 'required',
            'contact_number' => 'required|min:8|max:10',
            'email' => 'required|email',
            'bank_name' => 'required',
            'card_number_1' => 'required|min:4|max:4',
            'card_number_2' => 'required|min:4|max:4',
            'card_number_3' => 'required|min:4|max:4',
            'card_number_4' => 'required|min:4|max:4',
            'gcash_number' => 'required|min:10|max:10'
        ]);

        if ($validator->fails()) {
            Alert::warning('Update Configuration Failed', 'Invalid Input');
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        $configuration_details['card_number'] = 
        $configuration_details['card_number_1'].
        $configuration_details['card_number_2'].
        $configuration_details['card_number_3'].
        $configuration_details['card_number_4'];

        unset($configuration_details['card_number_1']);
        unset($configuration_details['card_number_2']);
        unset($configuration_details['card_number_3']);
        unset($configuration_details['card_number_4']);

        foreach ($configuration_details as $name => $value) {
            $update_config = $this->configuration()->updateConfiguration($name, $value);
        }

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Updated Configuration'
        ]);

        Alert::success('Update Configuration Successful', 'Success!');
        return redirect()->route('admin.config.index');
    }
}
