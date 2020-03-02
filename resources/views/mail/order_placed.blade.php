@component('mail::message')
# Order Placed

Your order **#{{ $data['order']['code'] }}** has been placed on **{{ $data['order']['created_at']->format('Y-m-d') }}**.
<br>
<br>
Before we deliver your order, make sure to pay us using **{{ strtoupper($data['order']['payment_method']) }}** before **{{ $data['order']['expires_at'] }}**.
<br>

@component('mail::panel')
##Your order will be delivered to:
**{{ ucwords($data['recipient']['first_name']).' '.ucwords($data['recipient']['last_name']) }}**
<br>
<small>**{{ ucwords($data['recipient']['address'].', '.$data['city'].', '.$data['province']) }}**</small>
<br>
<small>**Shipping Agent: {{ $data['shipping_agent'] }}**</small>
<br>
<small>**Delivery date: {{ $data['order']['delivery_date'] }}**</small>
@endcomponent
___

@component('mail::table')
| Product Name | Pot Type | Qty | Total | Amount |
| :-: | :-: | :-: | :-: |
@foreach ($data['products'] as $product)
| {{ ucwords($product['name']) }} | {{ ucwords($product['pot_type']) }} | x{{ $product['quantity'] }} | ₱ {{ number_format($product['price'], 2, '.', ',') }} | <div style="text-align:right;">₱ {{ number_format($product['sub_total'], 2, '.', ',') }}</div> |
@endforeach
|  |  |  |  |
|  |  |  |<div style="text-align:right; margin-right: 10px;">**Shipping Fee**</div> | <div style="text-align:right;">₱ {{ number_format($data['shipping_price'], 2, '.', ',') }}</div> |
|  |  |  |<div style="text-align:right; margin-right: 10px;">**Sub Total**</div> | <div style="text-align:right;">₱ {{ number_format($data['total']['sub_total'], 2, '.', ',') }}</div> |
@if ($data['order']['loyalty_points'] != 0)
|  |  |  |<div style="text-align:right; margin-right: 10px;">**Loyalty Points**</div> | <div style="text-align:right;">₱ -{{ number_format($data['order']['loyalty_points'], 2, '.', ',') }}</div> |
@endif
@endcomponent
___
@component('mail::panel')
<div style="text-align:right"><b>Grand Total : ₱ {{ number_format($data['total']['grand_total'], 2, '.', ',') }}</b></div>
@endcomponent
@endcomponent