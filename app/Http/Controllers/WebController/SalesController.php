<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Storage;


class SalesController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now()->format('m/d/Y');

        $group_by = $request->get('group_by');
        $start_date = $request->get('start_date') ?? $now;
        $end_date = $request->get('end_date') ?? $now;
        $order_by = $request->get('order_by');
        $sort = $request->get('sort');
        $sales = $this->order()->getOrderSales($group_by, $start_date, $end_date);

        $format = "m/d/Y";

        $date_array = $this->arrayOfDates($group_by, $start_date, $end_date);
        foreach ($date_array as $date) {
            $sales_array[] = [
                'date' => $date,
                'total_orders' => 0,
                'total_sales' => 0
            ];
        }

        $from_format = "Y-m-d";
        if ($group_by == 'month') {
            $from_format = "F, Y";
            $format = "F, Y";
        } else if ($group_by == "year") {
            $from_format = "Y";
            $format = "Y";
        }

        $sales_array = $this->formatSalesCollection($sales_array, $sales, $date_array, $format, $from_format, $group_by);

        $sales_collection =  collect($this->filterSalesCollection($sales_array, $format, $order_by, $sort));

        $total = [
            'orders' => $sales_collection != null ? $sales_collection->sum('total_orders') : 0,
            'sales' => $sales_collection != null ? $sales_collection->sum('total_sales') : 0
        ];
        
        return view('pages.sales.sales_index')
            ->with('total', $total)
            ->with('sales', $sales_collection);
    }

    public function print(Request $request)
    {
        $now = Carbon::now()->format('m/d/Y');

        $group_by = $request->get('group_by');
        $start_date = $request->get('start_date') ?? $now;
        $end_date = $request->get('end_date') ?? $now;
        $order_by = $request->get('order_by');
        $sort = $request->get('sort');
        $sales = $this->order()->getOrderSales($group_by, $start_date, $end_date);

        $format = "m/d/Y";

        $date_array = $this->arrayOfDates($group_by, $start_date, $end_date);
        foreach ($date_array as $date) {
            $sales_array[] = [
                'date' => $date,
                'total_orders' => 0,
                'total_sales' => 0
            ];
        }

        $from_format = "Y-m-d";
        if ($group_by == 'month') {
            $from_format = "F, Y";
            $format = "F, Y";
        } else if ($group_by == "year") {
            $from_format = "Y";
            $format = "Y";
        }

        $sales_array = $this->formatSalesCollection($sales_array, $sales, $date_array, $format, $from_format, $group_by);
        $sales_collection =  collect($this->filterSalesCollection($sales_array, $format, $order_by, $sort));
        $total = [
            'orders' => $sales_collection != null ? $sales_collection->sum('total_orders') : 0,
            'sales' => $sales_collection != null ? $sales_collection->sum('total_sales') : 0
        ];

        $report_type = "Daily";
        if ($group_by == 'month') {
            $report_type = "Monthly";
        } else if ($group_by == 'year') {
            $report_type = "Yearly";
        }

        $start_range = Carbon::createFromFormat('m/d/Y', $start_date)->format('F d, Y');
        $end_range = Carbon::createFromFormat('m/d/Y', $end_date)->format('F d, Y');

        $data = [
            'sales' => $sales_collection,
            'total' => $total,
            'report_type' => $report_type,
            'date_range' => $start_range.' - '.$end_range
        ];

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pages.sales.report', compact('data'));
        
        $path = "/app/reports";
        $now = Carbon::now()->format('m-d-Y_h-i-sA');
        $filename = "[".$now."]-".$report_type."_Sales_Report.pdf";
        $full_path = storage_path().$path."/".$filename;
        $pdf->save($full_path);

        return Storage::disk('local')->download('reports/'.$filename);
    }

    private function formatSalesCollection($sales_array, $sales, $date_array, $format, $from_format, $group_by)
    {
        foreach ($sales as $sale) {
            $sales_date = $sale->date;
            $sale->date = Carbon::createFromFormat($from_format, $sales_date)->format($format);

            if (($key = array_search($sale->date, $date_array)) !== false) {
                unset($sales_array[$key]);
            }

            $sales_array[] = [
                'date' => $sale->date,
                'total_orders' => $sale->total_orders,
                'total_sales' => $sale->total_sales
            ];
        }

        return $sales_array;
    }

    private function filterSalesCollection($sales_array, $format, $order_by, $sort)
    {
        $sales_collection = collect($sales_array);
        if ($order_by == 'sales') {
            if ($sort == 'asc') {
                $sales_collection = $sales_collection->sortBy('total_sales');
            } else {
                $sales_collection = $sales_collection->sortByDesc('total_sales');
            }
        } else if ($order_by == 'orders') {
            if ($sort == 'asc') {
                $sales_collection = $sales_collection->sortBy('total_orders');
            } else {
                $sales_collection = $sales_collection->sortByDesc('total_orders');
            }
        } else {
            if ($sort == 'asc') {
                $sales_collection = $sales_collection->sortBy(function ($column) use ($format) {
                    return Carbon::createFromFormat($format, $column['date']);
                })->all();
            } else {
                $sales_collection = $sales_collection->sortByDesc(function ($column) use ($format) {
                    return Carbon::createFromFormat($format, $column['date']);
                })->all();
            }
        }

        return $sales_collection;
    }

    private function arrayOfDates($group_by, $start_date, $end_date)
    {
        $start = Carbon::createFromFormat('m/d/Y', $start_date);
        $end = Carbon::createFromFormat('m/d/Y', $end_date);

        $num_of_dates = $start->diffInDays($end);
        if ($group_by == "month") {
            $num_of_dates = $start->diffInMonths($end);
        } else if ($group_by == "year") {
            $num_of_dates = $start->diffInYears($end);
        }

        $dates = [];
        for ($date = 0; $date <= $num_of_dates; $date++) {
            if ($group_by == "month") {
                $dates[] = $start->format('F, Y');
                $start->addMonth(1);
            } else if ($group_by == "year") {
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
