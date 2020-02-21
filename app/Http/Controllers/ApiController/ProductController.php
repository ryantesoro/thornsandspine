<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->product()->browseProducts(null);

        $newest_products = $products;
        $best_seller_products = $products;

        return response()->json([
            'success' => true,
            'data' => [
                'newest_products' => $newest_products,
                'best_seller_products' => $best_seller_products
            ]
        ]);
    }

    public function search(Request $request)
    {
        $products = $this->product()->browseProducts($request->get('search'));

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
