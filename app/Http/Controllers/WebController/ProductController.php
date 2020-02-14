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
        $list_of_products = $this->product()->getProducts();

        return view('pages.product.product_index')
            ->with('products', $list_of_products);
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

        $code = $this->generateCode();
        $file_name = $code.".jpg";
        $this->saveImageFile($product_details['img'], $file_name);

        $product_details['img'] = $file_name;
        $product_details['code'] = $code;
        $this->product()->storeProduct($product_details);

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

    public function update(Request $request, $code)
    {
        $with_pic = !empty($request->file('product_image')) && $request->file('product_image') != null;
        $validator = $this->validateInput($request->all(), $with_pic);

        if ($validator->fails()) {
            Alert::warning('Update Product Failed', 'Invalid Input!');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $product_details = $this->inputToArray($request);

        if ($with_pic) {
            $file_name = $code.".jpg";
            $this->saveImageFile($product_details['img'], $file_name);
        }

        unset($product_details['img']);
        $this->product()->updateProduct($product_details, $code);

        Alert::success('Update Product Successful', 'Success!');
        return redirect()->route('admin.product.index');
    }

    public function destroy($code)
    {
        $this->product()->softDeleteProduct($code);

        Alert::success('Hide Product Successful', 'Success!');
        return redirect()->route('admin.product.index');
    }

    public function restore($code)
    {
        $this->product()->restoreProduct($code);

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
            'product_price' => 'required|numeric|digits_between:1,4|min:0|not_in:0'
        );

        if ($with_pic) {
            $options['product_image'] = 'image|mimes:jpeg,png,jpg|max:5120';
        }

        $validator = Validator::make($product_details, $options);

        return $validator;
    }

    private function generateCode()
    {
        $latest_product = $this->product()->getLatestProduct();
        $year = Carbon::now()->format('Y');
        $code = sprintf('%s%04s', $year, strVal($latest_product['id']+1));

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
