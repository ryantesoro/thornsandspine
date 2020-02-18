<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    protected $dates = ['deleted_at'];

    protected $table = "products";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'code', 'name', 'description',
        'price', 'img'
    ];

    public $timestamps = true;

    //Get product model
    public function getProductModel($code)
    {
        $product_details = Product::where('code', $code)
            ->get()
            ->first();
        $product_model = Product::find($product_details->id);
        
        return $product_model;
    }

    //Gets all products
    public function getProducts()
    {
        $list_of_products = Product::all()
            ->sortByDesc('created_at');

        return $list_of_products;
    }

    //Browse products
    public function browseProducts($where)
    {
        $list_of_products = Product::select('*');
        if ($where != null || !empty($where)) {
            $search = '%'.$where.'%';
            $list_of_products = Product::whereRaw('products.name LIKE ?', [
                $search
            ]);
        }
    
        return $list_of_products->get();
    }

    //Check Product
    public function productExists($code)
    {
        return Product::where('code', $code)->count() > 0;
    }

    //Get Product
    public function getProduct($product_id)
    {
        $product = Product::where('id', $product_id)
            ->get()
            ->first();

        return $product;
    }

    //Show product
    public function showProduct($code)
    {
        $product_details = Product::where('code', $code)
            ->first()
            ->toArray();

        $product_details['created_at'] = Carbon::parse($product_details['created_at'])->format('m-d-Y g:i A');
        $product_details['updated_at'] = Carbon::parse($product_details['updated_at'])->format('m-d-Y g:i A');
        
        return $product_details;
    }

    //Store Product
    public function storeProduct($product_details)
    {
        $insert_product = Product::create($product_details);
        return $insert_product;
    }

    //Update Product
    public function updateProduct($product_details, $product_id)
    {
        $update_product = Product::where('id', $product_id)
            ->update($product_details);
        return $update_product;
    }

    //Change Product Status
    public function changeProductStatus($product_id, $active)
    {
        $update_product = Product::where('id', $product_id)
            ->update(['active' => $active]);

        return $update_product;
    }

    public function cart()
    {
        return $this->belongsToMany('App\Cart', 'cart_product');
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_product');
    }
}