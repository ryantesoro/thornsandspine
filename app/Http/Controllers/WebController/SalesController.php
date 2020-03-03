<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Storage;


class SalesController extends Controller
{
    private $start_date;
    private $end_date;
    private $order_by;
    private $sort;
    private $group_by;
    private $sales;
    private $format = "m/d/Y";
    private $from_format = "Y-m-d";
    private $sales_array;
    private $date_array;
    private $sales_collection;
    private $total;

    public function __construct(Request $request)
    {
        $now = Carbon::now()->format('m/d/Y');
        $week_ago = Carbon::now()->subDays(7)->format('m/d/Y');
        $this->group_by = $request->get('group_by');
        $this->start_date = $request->get('start_date') ?? $week_ago;
        $this->end_date = $request->get('end_date') ?? $now;
        $this->order_by = $request->get('order_by');
        $this->sort = $request->get('sort');

        $this->sales = $this->order()->getOrderSales($this->group_by, $this->start_date, $this->end_date);
        $this->setFormat();
        $this->date_array = $this->arrayOfDates();
        foreach ($this->date_array as $date) {
            $this->sales_array[] = [
                'date' => $date,
                'total_orders' => 0,
                'total_sales' => 0,
                'total_loyalty_points' => 0,
                'codes' => []
            ];
        }

        $this->formatSalesCollection();
        $this->filterSalesCollection();
        $this->findTotal();
    }

    public function index(Request $request)
    {   
        return view('pages.sales.sales_index')
            ->with('total', $this->total)
            ->with('sales', $this->sales_collection);
    }

    public function print(Request $request)
    {
        $report_type = "Daily";
        if ($this->group_by == 'month') {
            $report_type = "Monthly";
        } else if ($this->group_by == 'year') {
            $report_type = "Yearly";
        }

        $configurations = $this->configuration()->getConfigurations();

        $data = [
            'sales' => $this->sales_collection,
            'total' => $this->total,
            'report_type' => $report_type,
            'logo_url' => route('image', ['logo', 'logo.jpg']),
            'configurations' => $configurations,
            'from' => $this->start_date,
            'to' => $this->end_date
        ];

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pages.sales.report', compact('data'));
        
        $path = "/app/reports";
        $now = Carbon::now()->format('m-d-Y_h-i-sA');
        $filename = "[".$now."]-".$report_type."_Sales_Report.pdf";
        $full_path = storage_path().$path."/".$filename;
        $pdf->save($full_path);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Printed '.$report_type.' Sales Report'
        ]);

        return Storage::disk('local')->download('reports/'.$filename);
    }

    private function findTotal()
    {
        $this->total = [
            'orders' => $this->sales_collection != null ? $this->sales_collection->sum('total_orders') : 0,
            'sales' => $this->sales_collection != null ? $this->sales_collection->sum('total_sales') : 0,
            'loyalty_points' => $this->sales_collection != null ? $this->sales_collection->sum('total_loyalty_points') : 0
        ];
    }

    private function setFormat()
    {
        if ($this->group_by == 'month') {
            $this->from_format = "F, Y";
            $this->format = "F, Y";
        } else if ($this->group_by == "year") {
            $this->from_format = "Y";
            $this->format = "Y";
        }
    }

    private function formatSalesCollection()
    {
        foreach ($this->sales as $sale) {
            $sales_date = $sale->date;
            $sale->date = Carbon::createFromFormat($this->from_format, $sales_date)->format($this->format);

            if (($key = array_search($sale->date, $this->date_array)) !== false) {
                unset($this->sales_array[$key]);
            }

            $codes = explode(',', $sale->codes);

            $total_sales = 0;
            $total_loyalty_points = 0;
            foreach ($codes as $code) {
                $order = $this->order()->getOrder($code);
                $loyalty_points = $order->loyalty_points;
                $order_total = $order->total;

                if ($loyalty_points > $order_total) {
                    $total_loyalty_points += $order_total;
                } else {
                    $total_loyalty_points += $loyalty_points;
                    $total_sales += ($order_total - $loyalty_points);
                }
            }

            $this->sales_array[] = [
                'date' => $sale->date,
                'total_orders' => $sale->total_orders,
                'total_sales' => $total_sales,
                'total_loyalty_points' => $total_loyalty_points,
                'codes' => $codes
            ];
        }
    }

    private function filterSalesCollection()
    {
        $this->sales_collection = collect($this->sales_array);
        $format = $this->format;
        if ($this->order_by != 'date')
            if ($this->sort == 'asc') {
                $this->sales_collection = $this->sales_collection->sortBy($this->order_by);
            } else {
                $this->sales_collection = $this->sales_collection->sortByDesc($this->order_by);
        } else {
            if ($this->sort == 'asc') {
                $this->sales_collection = $this->sales_collection->sortBy(function ($column) use ($format) {
                    return Carbon::createFromFormat($format, $column['date']);
                });
            } else {
                $this->sales_collection = $this->sales_collection->sortByDesc(function ($column) use ($format) {
                    return Carbon::createFromFormat($format, $column['date']);
                });
            }
        }
    }

    private function arrayOfDates()
    {
        $start = Carbon::createFromFormat('m/d/Y', $this->start_date);
        $end = Carbon::createFromFormat('m/d/Y', $this->end_date);

        $num_of_dates = $start->diffInDays($end);
        if ($this->group_by == "month") {
            $num_of_dates = $start->diffInMonths($end);
        } else if ($this->group_by == "year") {
            $num_of_dates = $start->diffInYears($end);
        }

        $dates = [];
        for ($date = 0; $date <= $num_of_dates; $date++) {
            if ($this->group_by == "month") {
                $dates[] = $start->format('F, Y');
                $start->addMonth(1);
            } else if ($this->group_by == "year") {
                $dates[] = $start->format('Y');
                $start->addYear(1);
            } else {
                $dates[] = $start->format('m/d/Y');
                $start->addDay(1);
            }
        }

        return $dates;
    }
}
