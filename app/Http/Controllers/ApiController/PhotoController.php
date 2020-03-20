<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function show(Request $request, $image_name)
    {
        $file_path = "product/";
        if ($request->has('size')) {
            $size = $request->get('size');
            
            if ($size == "medium") {
                $file_path = "product/medium/";
            } else if ($size == "thumbnail") {
                $file_path = "product/thumbnail/";
            }
        }

        $image = Storage::disk('local')->get($file_path.$image_name);
        return response()->make($image, 200, ['Content-Type' => 'Image']);
    }

    public function promotionImage(Request $request, $image_name)
    {
        $file_path = "promotion/";
        if ($request->has('size')) {
            $size = $request->get('size');
            
            if ($size == "medium") {
                $file_path = "promotion/medium/";
            }
        }

        $image = Storage::disk('local')->get($file_path.$image_name);
        return response()->make($image, 200, ['Content-Type' => 'Image']);
    }
}
