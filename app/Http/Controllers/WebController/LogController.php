<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = $this->logs()->getLogs();

        return view('pages.log.log_index')
            ->with('logs', $logs);
    }
}
