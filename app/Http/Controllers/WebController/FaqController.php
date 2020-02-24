<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        return view('pages.faq.faq_index');
    }
}
