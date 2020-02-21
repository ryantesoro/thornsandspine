<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->product()->browseProducts(null);

        $new_products = [];

        foreach ($products as $product) {
            $new_products[] = [
                'code' => $product->code,
                'img' => route('image.api', [$product->img, 'size' => 'thumbnail'])
            ];
        }

        $newest_products = $new_products;
        $best_seller_products = $new_products;

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

        $new_products = [];

        foreach ($products as $product) {
            $new_products[] = [
                'code' => $product->code,
                'img' => route('image.api', [$product->img, 'size' => 'thumbnail'])
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $new_products
        ]);
    }

    public function show($code)
    {
        $product_details = $this->product()->showProduct($code);

        $product_details['img'] = route('image.api', [$product_details['img'], 'size' => 'medium']);

        $with_trashed = false;
        $pots = $this->pot()->getPots(null, $with_trashed);
        
        return response()->json([
            'success' => true,
            'data' => [
                'product_details' => $product_details,
                'pots' => $pots
            ]
        ]);
    }
}
