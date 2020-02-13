<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
