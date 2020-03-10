<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $fetched_best_sellers = $this->product()->getBestSellingProducts(8);
        $fetched_new_products = $this->product()->getNewestProducts(8);

        $best_seller_products = $this->addImageUrl($fetched_best_sellers);
        $newest_products = $this->addImageUrl($fetched_new_products);

        return response()->json([
            'success' => true,
            'data' => [
                'newest_products' => $newest_products,
                'best_seller_products' => $best_seller_products
            ]
        ]);
    }

    public function bestSeller()
    {
        $fetched_best_sellers = $this->product()->getBestSellingProducts(null);
        $best_seller_products = $this->addImageUrl($fetched_best_sellers);

        return response()->json([
            'success' => true,
            'data' => $best_seller_products
        ]);
    }

    public function newestProduct()
    {
        $fetched_new_products = $this->product()->getNewestProducts(null);
        $newest_products = $this->addImageUrl($fetched_new_products);

        return response()->json([
            'success' => true,
            'data' => $newest_products
        ]);
    }

    public function search(Request $request)
    {
        $products = $this->product()->browseProducts($request->get('search'));

        $searched_products = [];
        foreach ($products as $product) {
            $searched_products[] = [
                'code' => $product->code,
                'name' => ucwords($product->name),
                'img' => route('image.api', [$product->img, 'size' => 'thumbnail'])
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $searched_products
        ]);
    }

    public function show($code)
    {
        $product_details = $this->product()->showProduct($code);

        $product_details['img'] = route('image.api', [$product_details['img'], 'size' => 'medium']);
        $product_details['name'] = ucwords($product_details['name']);
        unset($product_details['active']);

        $with_trashed = false;
        $pots = $this->pot()->getPots(null, $with_trashed)
            ->pluck('name', 'id');

        $new_pots = [];

        foreach($pots as $id => $name) {
            $new_pots[$id] = ucwords($name);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'product_details' => $product_details,
                'pots' => $new_pots
            ]
        ]);
    }

    private function addImageUrl($product_collection)
    {
        $products = [];
        foreach ($product_collection as $product) {
            $products[] = [
                'code' => $product->code,
                'name' => ucwords($product->name),
                'img' => route('image.api', [$product->img, 'size' => 'thumbnail'])
            ];
        }

        return $products;
    }
}
