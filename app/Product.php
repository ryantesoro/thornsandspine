<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;


class Product extends Model
{
    protected $table = "products";

    protected $hidden = ['pivot'];

    protected $fillable = [
        'code', 'name', 'description',
        'price', 'img'
    ];

    public $timestamps = true;

    //Gets all products
    public function getProducts($product_name)
    {
        $list_of_products = Product::select('*');

        if ($product_name != '' || !empty($product_name)) {
            $product_name = '%'.$product_name.'%';
            $list_of_products->whereRaw('name LIKE ?', [
                $product_name
            ]);
        }

        return $list_of_products->get()
            ->sortByDesc('created_at');
    }

    //Get Newst Products
    public function getNewestProducts($limit)
    {
        $start_date = Carbon::now()->subMonth(1);
        $end_date = Carbon::now();

        $products = Product::select(['code', 'name', 'img']);

        if ($limit != null) {
            $products = $products->limit($limit);
        }
        
        return $products->get()->sortByDesc('created_at');
    }

    //Get Best Selling Products
    public function getBestSellingProducts($limit)
    {
        $start_date = Carbon::now()->subMonth(1);
        $end_date = Carbon::now();

        $products = DB::table('products')
            ->selectRaw('products.code, products.name, products.img, COUNT(orders.id) AS sales')
            ->leftJoin('order_product', function ($query) {
                $query->on('order_product.product_id', 'products.id');
            })
            ->leftJoin('orders', function ($query) {
                $query->on('orders.id', 'order_product.order_id');
            })
            ->where('status', 2)
            ->whereBetween('orders.created_at', [$start_date, $end_date])
            ->groupBy('products.code', 'products.name', 'products.img');

        if ($limit != null) {
            $products = $products->limit($limit);
        }
        
        return $products->get()->sortByDesc('sales');
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
    
        return $list_of_products->where('active', 1)->get();
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

    //Get product sales
    public function getProductSales($start_date, $end_date)
    {
        $products = DB::table('products')
            ->selectRaw('products.code, products.name, products.price, SUM(order_product.quantity) total_orders, SUM(orders.total) total_sales')
            ->leftJoin('order_product', function ($query) {
                $query->on('order_product.product_id', 'products.id');
            })
            ->leftJoin('orders', function ($query) {
                $query->on('orders.id', 'order_product.order_id');
            });

        if ($start_date != null && $end_date != null) {
            $start_range = Carbon::createFromFormat('m/d/Y', $start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end_range = Carbon::createFromFormat('m/d/Y', $end_date)->endOfDay()->format('Y-m-d H:i:s');
            $products->whereBetween('orders.created_at', [$start_range, $end_range]);
        }

        $products->groupBy('products.code', 'products.name', 'products.price')
            ->where('orders.status', 2);
        
        return $products->get();
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'order_product');
    }
}
