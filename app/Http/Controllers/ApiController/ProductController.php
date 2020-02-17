<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $products = $this->product()->browseProducts($search);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($code)
    {
        $product_details = $this->product()->showProduct($code);
        
        return response()->json([
            'success' => true,
            'data' => $product_details
        ]);
    }
}
