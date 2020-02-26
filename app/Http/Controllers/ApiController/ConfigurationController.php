<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = $this->configuration()->getConfigurations();

        return response()->json([
            'success' => true,
            'data' => $configurations
        ]);
    }
}
