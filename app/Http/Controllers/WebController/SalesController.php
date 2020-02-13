<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.sales.sales_index');
    }
}
