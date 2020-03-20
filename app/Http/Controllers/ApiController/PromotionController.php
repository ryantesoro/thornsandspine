<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = $this->promotion()->getPromotions();

        $promotion_images = [];

        foreach ($promotions as $promotion) {
            $promotion_images[] = route('image.promotion', [$promotion->file_name, 'size' => 'medium']);
        }

        return response()->json([
            'success' => true,
            'data' => $promotion_images
        ]);
    }
}
