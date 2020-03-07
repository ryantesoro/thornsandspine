<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;


class Order extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'code', 'recipient_id', 'loyalty_points', 'status',
        'total', 'shipping_fees_id', 'remarks',
        'comment', 'payment_method', 'expires_at',
        'delivery_date', 'tracking_number'
    ];

    public $timestamps = true;

    protected $hidden = ['pivot'];

    //Get All Orders
    public function getOrders($code, $status)
    {
        $orders = DB::table('orders')
            ->selectRaw('orders.code, recipients.first_name r_fname, recipients.last_name r_lname, customers.first_name c_fname, customers.last_name c_lname, orders.status, orders.expires_at')
            ->leftJoin('customer_order', function ($query) {
                $query->on('customer_order.order_id', 'orders.id');
            })
            ->leftJoin('customers', function ($query) {
                $query->on('customers.id', 'customer_order.customer_id');
            })
            ->leftJoin('recipients', function ($query) {
                $query->on('recipients.id', 'orders.recipient_id');
            });

        if ($code != null && !empty($code)) {
            $search = '%'.$code.'%';
            $orders = $orders->whereRaw('orders.code LIKE ?', [
                $search
            ]);
        }

        if ($status != 'all' && $status != null) {
            $now = Carbon::now();
            if ($status == 'expired') {
                $orders = $orders->where('orders.expires_at', '<', $now);
            } else if ($status == 0) {
                $orders = $orders->where('orders.expires_at', '>', $now);
            } else {
                $orders = $orders->where('orders.status', $status);
            }
        }

        return $orders->orderBy('code', 'DESC')->get();
    }

    //Get Order (1 row)
    public function getOrder($order_code) {
        $order_details = Order::where('code', $order_code)
            ->get()
            ->first();

        return $order_details;
    }

    //Get Order Model
    public function getOrderModel($order_id)
    {
        $order_model = Order::find($order_id);

        return $order_model;
    }

    //Get Orders by status
    public function getOrdersByStatus($order_model, $status)
    {
        $orders = $order_model;

        if ($status != null && $status != 5 && $status != 'all') {
            $orders = $orders->where('status', $status);
        }
        
        return $orders->get()->sortByDesc('created_at');
    }

    //Store Order
    public function storeOrder($order_details)
    {
        $order_details['expires_at'] = Carbon::now()->addDays(2);
        $store_order = Order::create($order_details);

        return $store_order;
    }

    //Update Order
    public function updateOrder($order_details, $order_id)
    {
        $update_order = Order::where('id', $order_id)
            ->update($order_details);

        return $update_order;
    }

    //Check if order exists
    public function orderExists($order_code)
    {
        $order = Order::where('code', $order_code)->get()->count();

        return $order != 0;
    }

    //Get Order Sales
    public function getOrderSales($order_by, $start_date, $end_date)
    {
        $start_range = Carbon::createFromFormat('m/d/Y', $start_date)->startOfDay()->format('Y-m-d H:i:s');
        $end_range = Carbon::createFromFormat('m/d/Y', $end_date)->endOfDay()->format('Y-m-d H:i:s');
        
        $orders = DB::table('orders');

        $select_query = ", COUNT(orders.id) total_orders, SUM(total) total_sales, GROUP_CONCAT(orders.code) as codes";
        if ($order_by == "week") {
            $orders->selectRaw('WEEK(orders.created_at) date'.$select_query);
        } else if ($order_by == "month") {
            $orders->selectRaw('DATE_FORMAT(orders.created_at, "%M, %Y") date'.$select_query);
        } else if ($order_by == "year") {
            $orders->selectRaw('YEAR(orders.created_at) date'.$select_query);
        } else {
            $orders->selectRaw('DATE(orders.created_at) date'.$select_query);
        }

        $orders->whereBetween('orders.created_at', [$start_range, $end_range]);

        $orders->where('status', 2)
            ->whereBetween('orders.created_at', [$start_range, $end_range])
            ->groupBy('date');

        return $orders->get();
    }

    public function customer()
    {
        return $this->belongsToMany('App\Customer', 'customer_order');
    }

    public function screenshot()
    {
        return $this->belongsToMany('App\Screenshot', 'order_screenshot');
    }

    public function order_product()
    {
        return $this->hasMany('App\OrderProduct', 'order_product');
    }
}
