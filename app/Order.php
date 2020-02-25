<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Order extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'code', 'recipient_first', 'recipient_last',
        'total', 'shipping_fees_id', 'remarks',
        'comment', 'payment_method', 'expires_at'
    ];

    public $timestamps = true;

    protected $hidden = ['pivot'];

    //Get Order (1 row)
    public function getOrder($order_code) {
        $order_details = Order::where('code', $order_code)
            ->get()
            ->first();

        return $order_details;
    }

    //Get Order Model
    public function getOrderModel($order_code)
    {
        $order_model = Order::where('code', $order_code);

        return $order_code;
    }

    //Get Orders by status
    public function getOrdersByStatus($order_model, $status)
    {
        $orders = $order_model->where('status', $status)
            ->get();
        
        return $orders;
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
