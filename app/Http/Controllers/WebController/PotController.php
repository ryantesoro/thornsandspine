<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PotController extends Controller
{
    public function index(Request $request)
    {
        $pots = $this->pot()->getPots();
        return view('pages.pot.pot_index');
    }
}
