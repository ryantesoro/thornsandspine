<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "products";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'code', 'name', 'description',
        'price', 'img'
    ];

    public $timestamps = true;

    //Gets all products
    public function getProducts()
    {
        $list_of_products = Product::withTrashed()
            ->get()
            ->sortByDesc('created_at');

        return $list_of_products;
    }

    //Check Product
    public function productExists($code)
    {
        return Product::withTrashed()->where('code', $code)->count() > 0;
    }

    //Show product
    public function showProduct($code)
    {
        $product_details = Product::withTrashed()
            ->where('code', $code)
            ->first()
            ->toArray();

        $product_details['created_at'] = Carbon::parse($product_details['created_at'])->format('m-d-Y g:i A');
        $product_details['updated_at'] = Carbon::parse($product_details['updated_at'])->format('m-d-Y g:i A');

        if (!empty($product_details['deleted_at'])) {
            $product_details['deleted_at'] = Carbon::parse($product_details['deleted_at'])->format('m-d-Y g:i A');
        }
        
        return $product_details;
    }

    //Get Latest Product
    public function getLatestProduct()
    {
        $product_details = Product::withTrashed()
            ->select('id')
            ->latest()
            ->first()
            ->toArray();

        return $product_details;
    }

    //Store Product
    public function storeProduct($product_details)
    {
        $insert_product = Product::create($product_details);
        return $insert_product;
    }

    //Update Product
    public function updateProduct($product_details, $code)
    {
        $update_product = Product::withTrashed()
            ->where('code', $code)
            ->update($product_details);
        return $update_product;
    }

    //Soft Delete Product
    public function softDeleteProduct($code)
    {
        $delete_product = Product::where('code', $code)
            ->delete();
        return $delete_product;
    }
    
    //Restore Deleted Product
    public function restoreProduct($code)
    {
        $restore_product = Product::withTrashed()
            ->where('code', $code)
            ->restore();

        return $restore_product;
    }
}
