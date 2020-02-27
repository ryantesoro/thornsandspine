<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function show(Request $request, $directory, $image_name)
    {
        $file_path = $directory."/";
        if ($request->has('size')) {
            $size = $request->get('size');
            
            if ($size == "medium") {
                $file_path = $file_path."medium/";
            } else if ($size == "thumbnail") {
                $file_path = $file_path."thumbnail/";
            }
        }

        $image = Storage::disk('local')->get($file_path.$image_name);
        return response()->make($image, 200, ['Content-Type' => 'Image']);
    }
}
