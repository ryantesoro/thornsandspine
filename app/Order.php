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
        'expires_at'
    ];

    public $timestamps = true;

    protected $hidden = ['pivot'];

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

    public function product()
    {
        return $this->belongsToMany('App\Product', 'order_product');
    }

    public function screenshot()
    {
        return $this->belongsToMany('App\Screenshot', 'order_screenshot');
    }
}
