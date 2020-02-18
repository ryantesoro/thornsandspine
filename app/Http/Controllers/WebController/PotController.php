<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class PotController extends Controller
{
    public function index(Request $request)
    {
        $pots = $this->pot()->getPots();

        return view('pages.pot.pot_index')->with('pots', $pots);
    }

    public function create(Request $request)
    {
        return view('pages.pot.pot_create');
    }

    public function store(Request $request)
    {
        $pot_details = [
            'pot_name' => $request->post('pot_name'),
            'pot_description' => $request->post('pot_name')
        ];

        $validator = Validator::make($pot_details, [
            'pot_name' => 'required|min:3',
            'pot_description' => 'required'
        ]);

        if ($validator->fails()) {
            Alert::warning('Add Pot Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $store_pot = $this->pot()->storePot($pot_details);
        Alert::success('Add Pot Successful', 'Successfully added pot');
        return redirect()->route('admin.pot.index');
    }

    public function show(Request $request, $pot_id)
    {
        if (!$this->pot()->potExists($pot_id)) {
            Alert::error('View Pot Failed', 'Pot does not exist!');
            return redirect()->route('admin.pot.index');
        }

        $pot_details = $this->pot()->getPot($pot_id);
        
        return view('pages.pot.pot_show')->with('pot_details', $pot_details);
    }

    public function edit(Request $request, $pot_id)
    {
        if (!$this->pot()->potExists($pot_id)) {
            Alert::error('Edit Pot Failed', 'Pot does not exist!');
            return redirect()->route('admin.pot.index');
        }

        $pot_details = $this->pot()->getPot($pot_id);

        return view('pages.pot.pot_edit')->with('pot_details', $pot_details);
    }

    public function update(Request $request, $pot_id)
    {
        $pot_details = [
            'pot_name' => $request->post('pot_name'),
            'pot_description' => $request->post('pot_name')
        ];

        $validator = Validator::make($pot_details, [
            'pot_name' => 'required|min:3',
            'pot_description' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator->errors());
        }

        $this->pot()->updatePot($pot_id, $pot_details);

        Alert::success('Update Pot Successful', 'Successfully updated pot');
        return redirect()->route('admin.pot.index');
    }

    public function destroy(Request $request, $pot_id)
    {
        $delete_pot = $this->pot()->changePotStatus($pot_id, 0);

        Alert::success('Delete Pot Successful', 'Successfully deleted pot');
        return redirect()->route('admin.pot.index');
    }

    public function restore(Request $request, $pot_id)
    {
        $restore_pot = $this->pot()->changePotStatus($pot_id, 1);

        Alert::success('Restore Pot Successful', 'Successfully restored pot');
        return redirect()->route('admin.pot.index');
    }
}