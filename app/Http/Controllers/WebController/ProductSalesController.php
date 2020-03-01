<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Storage;


class ProductSalesController extends Controller
{
    private $start_date;
    private $end_date;
    private $order_by;
    private $sort;
    private $products;
    private $array_of_products;

    public function __construct(Request $request)
    {
        $now = Carbon::now()->format('m/d/Y');
        $week_ago = Carbon::now()->subDays(7)->format('m/d/Y');
        $this->start_date = $request->get('start_date') ?? $week_ago;
        $this->end_date = $request->get('end_date') ?? $now;
        $this->order_by = $request->get('order_by');
        $this->sort = $request->get('sort');
        $this->products = $this->product()->getProductSales($this->start_date, $this->end_date);
        $this->array_of_products = $this->product()->getProducts(null)->pluck('name', 'code');
    }

    public function index(Request $request)
    {
        $sales_array = $this->removeDuplicates();
        $sales_collection = $this->sortProducts($sales_array);
        $total = $this->getTotals($sales_collection);

        return view('pages.product.product_sales')
            ->with('total', $total)
            ->with('sales', $sales_collection);
    }
    
    public function print(Request $request)
    {
        $sales_array = $this->removeDuplicates();
        $sales_collection = $this->sortProducts($sales_array);
        $total = $this->getTotals($sales_collection);

        $start_range = Carbon::createFromFormat('m/d/Y', $this->start_date)->format('F d, Y');
        $end_range = Carbon::createFromFormat('m/d/Y', $this->end_date)->format('F d, Y');

        $data = [
            'sales' => $sales_collection,
            'total' => $total,
            'date_range' => $start_range.' - '.$end_range
        ];
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pages.product.product_report', compact('data'));
        
        $path = "/app/reports";
        $now = Carbon::now()->format('m-d-Y_h-i-sA');
        $filename = "[".$now."]-Product_Sales_Report.pdf";
        $full_path = storage_path().$path."/".$filename;
        $pdf->save($full_path);

        return Storage::disk('local')->download('reports/'.$filename);
    }

    private function removeDuplicates()
    {
        $sales_array = [];
        foreach ($this->products as $product) {
            unset($this->array_of_products[$product->code]);
            $sales_array[] = [
                'code' => $product->code,
                'name' => $product->name,
                'total_orders' => $product->total_orders,
                'total_sales' => $product->total_sales
            ];
        }

        foreach ($this->array_of_products as $code => $name) {
            $sales_array[] = [
                'code' => $code,
                'name' => $name,
                'total_orders' => 0,
                'total_sales' => 0
            ];
        }

        return $sales_array;
    }

    private function sortProducts($sales_array)
    {
        $sales_collection = collect($sales_array);

        if ($this->sort == 'asc') {
            $sales_collection = $sales_collection->sortBy($this->order_by);
        } else {
            $sales_collection = $sales_collection->sortByDesc($this->order_by);
        }

        return $sales_collection;
    }

    private function getTotals($sales_collection)
    {
        $total = [
            'orders' => $sales_collection->sum('total_orders'),
            'sales' => $sales_collection->sum('total_sales')
        ];

        return $total;
    }
}
