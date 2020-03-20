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

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product_name = $request->get('name');
        $list_of_products = $this->product()->getProducts($product_name);

        return view('pages.product.product_index')
            ->with('products', $list_of_products)
            ->withInput($request->all());
    }

    public function show($code)
    {
        if (!$this->product()->productExists($code)) {
            Alert::error('View Product Failed', 'Product does not exist!');
            return redirect()->route('admin.product.index');
        }

        $product_details = $this->product()->showProduct($code);

        return view('pages.product.product_show')
            ->with('product', $product_details);
    }

    public function create()
    {
        return view('pages.product.product_create');
    }

    public function store(Request $request)
    {
        $validator = $this->validateInput($request->all(), true);

        if ($validator->fails()) {
            Alert::warning('Add Product Failed', 'Invalid Input!');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $product_details = $this->inputToArray($request);
        $product = $this->product()->storeProduct($product_details);

        $code = $this->generateCode($product->id);
        $file_name = $code.".jpg";
        $this->saveImageFile($product_details['img'], $file_name);

        $store_product = $this->product()->updateProduct(['code' => $code, 'img' => $file_name], $product->id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Added New Product #'.$code
        ]);

        Alert::success('Add Product Successful', 'Success!');
        return redirect()->route('admin.product.index');
    }

    public function edit($code)
    {
        if (!$this->product()->productExists($code)) {
            Alert::error('Edit Product Failed', 'Product does not exist!');
            return redirect()->route('admin.product.index');
        }
        $product_details = $this->product()->showProduct($code);
        return view('pages.product.product_edit')->with('product', $product_details);
    }

    public function update(Request $request, $product_id)
    {
        $with_pic = !empty($request->file('product_image')) && $request->file('product_image') != null;
        $validator = $this->validateInput($request->all(), $with_pic);

        if ($validator->fails()) {
            Alert::warning('Update Product Failed', 'Invalid Input!');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $product = $this->product()->getProduct($product_id);

        $product_details = $this->inputToArray($request);

        if ($with_pic) {
            $file_name = $product->code.".jpg";
            $this->saveImageFile($product_details['img'], $file_name);
        }

        unset($product_details['img']);
        $this->product()->updateProduct($product_details, $product_id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Updated Product #'.$product->code
        ]);

        Alert::success('Update Product Successful', 'Success!');
        return redirect()->route('admin.product.index');
    }

    public function destroy($product_id)
    {
        $delete_product = $this->product()->changeProductStatus($product_id, 0);
        $product = $this->product()->getProduct($product_id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Hide Product #'.$product->code
        ]);

        Alert::success('Hide Product Successful', 'Success!');
        return redirect()->route('admin.product.index');
    }

    public function restore($product_id)
    {
        $restore_product = $this->product()->changeProductStatus($product_id, 1);
        $product = $this->product()->getProduct($product_id);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Restore Product #'.$product->code
        ]);

        Alert::success('Restore Product Successful', 'Success!');
        return redirect()->route('admin.product.index');
    }

    private function inputToArray(Request $request)
    {
        $product_details = [
            'name' => strtolower($request->get('product_name')),
            'description' => $request->get('product_description'),
            'price' => $request->get('product_price'),
            'img' => $request->file('product_image')
        ];

        return $product_details;
    }

    private function validateInput($product_details, $with_pic)
    {
        $options = array(
            'product_name' => 'required|min:3|max:50',
            'product_description' => 'required',
            'product_price' => 'required|numeric|digits_between:1,5|min:0|not_in:0'
        );

        if ($with_pic) {
            $options['product_image'] = 'image|mimes:jpeg,png,jpg|max:5120';
        }

        $validator = Validator::make($product_details, $options);

        return $validator;
    }

    private function generateCode($product_id)
    {
        $year = Carbon::now()->format('Y');
        $code = sprintf('%s%04s', $year, strVal($product_id));

        return $code;
    }

    private function saveImageFile($image_file, $file_name)
    {
        $path = $image_file->getRealPath().'.jpg';

        $whole_pic = Image::make($image_file)->encode('jpg')->save($path);
        Storage::putFileAs('product', new File($path), $file_name);

        $medium = Image::make($image_file)->resize(300,200)->encode('jpg')->save($path);
        Storage::putFileAs('product/medium', new File($path), $file_name);

        $thumbnail = Image::make($image_file)->resize(100, 100)->encode('jpg')->save($path);
        Storage::putFileAs('product/thumbnail', new File($path), $file_name);
    }
}
