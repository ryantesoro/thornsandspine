<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\File;
use Carbon\Carbon;
use Image;


class PromotionController extends Controller
{
    public function index()
    {
        $promotions = $this->promotion()->getPromotions();

        return view('pages.promotion.promotion_index')->with('promotions', $promotions);
    }

    public function create()
    {
        return view('pages.promotion.promotion_create');
    }

    public function store(Request $request)
    {
        $promotion_details = [
            'name' => $request->post('name'),
            'promotion_image' => $request->file('promotion_image')
        ];

        $validator = Validator::make($promotion_details, [
            'name' => 'required',
            'promotion_image' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($validator->fails()) {
            Alert::warning('Store Promotion Failed', 'Invalid Input');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $file_name = Carbon::now()->timestamp;

        $store_promotion = $this->promotion()->storePromotion([
            'name' => $promotion_details['name'],
            'file_name' => $file_name
        ]);

        $this->saveImageFile($promotion_details['promotion_image'], $file_name);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Added New Promotion ID: '.$store_promotion->id
        ]);

        Alert::success('Store Promotion Successful', 'Success!');
        return redirect()->route('admin.promotion.index');
    }

    public function show($promotion_id)
    {
        $promotion = $this->promotion()->getPromotion($promotion_id);

        return view('pages.promotion.promotion_show')->with('promotion', $promotion);
    }

    public function destroy($promotion_id)
    {
        $delete_promotion = $this->promotion()->deletePromotion($promotion_id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Deleted Promotion ID: '.$promotion_id
        ]);

        Alert::success('Delete Promotion Successful', 'Success!');
        return redirect()->route('admin.promotion.index');
    }

    private function saveImageFile($image_file, $file_name)
    {
        $path = $image_file->getRealPath().'.jpg';

        $whole_pic = Image::make($image_file)->encode('jpg')->save($path);
        Storage::putFileAs('promotion', new File($path), $file_name);

        $medium = Image::make($image_file)->resize(300,200)->encode('jpg')->save($path);
        Storage::putFileAs('promotion/medium', new File($path), $file_name);
    }
}
