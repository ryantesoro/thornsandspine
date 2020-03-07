@extends('layouts.master')
@section('header_name', 'Orders')
@section('content')
@include('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/fancybox/dist/jquery.fancybox.min.css') }}">
@endsection

@section('js')
<script src="{{ asset('vendor/fancybox/dist/jquery.fancybox.min.js') }}"></script>
@endsection

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Order Code #{{$order->code}}</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.order.index') }}">Go Back</a>
                <a class="btn btn-warning font-weight-bold" href="{{ route('admin.order.print', $order->code) }}">Print</a>
                @if ($order->status == 1)
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#deliver_confirmation">
                    <b>Complete Order</b>
                </button>
                @endif
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                <div class="d-flex">
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">
                                        Order Status
                                    </td>
                                    <td>
                                        @if($order->status == 0 && \Carbon\Carbon::parse($order->expires_at)->isPast())
                                        Order is expired
                                        @elseif($order->status == 0)
                                        Waiting for the customer's proof of payment
                                        @elseif ($order->status == 1)
                                        Waiting for your confirmation
                                        @elseif ($order->status == 2)
                                        Order Delivered
                                        @elseif ($order->status == 3)
                                        Order has been cancelled by the customer
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        Ordered By
                                    </td>
                                    <td>
                                        {{ ucwords($customer->first_name.' '.$customer->last_name) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        Order Date
                                    </td>
                                    <td>
                                        {{ $order->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        Delivery Date
                                    </td>
                                    <td>
                                        {{ $order->delivery_date }}
                                    </td>
                                </tr>
                                @if ($order->recipient_id != null)
                                <tr>
                                    <td class="font-weight-bold">
                                        Contact Number
                                    </td>
                                    <td>
                                        {{ $customer->contact_number }}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">
                                            Recipient Name
                                        </td>
                                        <td>
                                            @if ($order->recipient_id != null)
                                            {{ ucwords($recipient->first_name.' '.$recipient->last_name) }}
                                            @else
                                            {{ ucwords($customer->first_name.' '.$customer->last_name) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">
                                            Recipient Address
                                        </td>
                                        <td>
                                            @if ($order->recipient_id != null)
                                            {{ ucwords($recipient->address).', '.$shipping_city_province }}
                                            @else
                                            {{ ucwords($customer->address).', '.$shipping_city_province }}
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($order->recipient_id != null)
                                    <tr>
                                        <td class="font-weight-bold">
                                            Recipient Email Address
                                        </td>
                                        <td>
                                            {{ $recipient->email }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="font-weight-bold">
                                            Recipient Contact Number
                                        </td>
                                        <td>
                                            @if ($order->recipient_id != null)
                                            {{ $recipient->contact_number }}
                                            @else
                                            {{ $customer->contact_number }}
                                            @endif
                                        </td>
                                    </tr>
                                    @if (!empty($order->remarks))
                                    <tr>
                                        <td class="font-weight-bold">
                                            Remarks
                                        </td>
                                        <td>
                                            {{ $order->remarks }}
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">
                                            Shipping Agent
                                        </td>
                                        <td>
                                            {{ strtoupper($shipping_agent) }}
                                        </td>
                                    </tr>
                                    @if ($order->status == 2)
                                    <tr>
                                        <td class="font-weight-bold">
                                            Tracking Number
                                        </td>
                                        <td>
                                            {{ $order->tracking_number }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="font-weight-bold">
                                            Payment Method
                                        </td>
                                        <td>
                                            {{ strtoupper($order->payment_method) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!empty($screenshots))
        <div class="card bh">
            <div class="card-header" role="tab">
                <h5 class="mb-0">
                    <div class="d-flex">
                        <div class="mr-auto">
                            <a data-toggle="collapse" href="#screenshots">Proof of payment</a>
                        </div>
                        <div class="ml-auto">
                            @if ($order->status == 1)
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#return_confirmation">
                                <b>Ask Customer To Resend Images</b>
                            </button>
                            @endif
                        </div>
                    </div>
                </h5>
            </div>
            <div id="screenshots" class="collapse show" role="tabpanel">
                <div class="card-body">
                    @foreach($screenshots as $date => $screenshot)
                    <p class="font-weight-bold pl-3">{{ $date }}</p>
                    <div class="d-flex pl-4">
                        @foreach($screenshot as $ss)
                        <a data-fancybox="gallery" href="{{ $ss['original'] }}">
                            <img class="border mb-3" src="{{ $ss['thumbnail'] }}">
                        </a>
                        @endforeach
                    </div>
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
        <hr>
        @endif
        <div class="row">
            <div class="col text-left">
                <h4>Ordered Products</h4>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table border">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Pot Type</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ ucwords($product['product']['name']) }}</td>
                        <td>{{ ucwords($product['pot']['name']) }}</td>
                        <td>x{{ $product['quantity'] }}</td>
                        <td>₱ {{ number_format($product['sub_total'], 2, '.', ',') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right font-weight-bold">Sub Total:</td>
                        <td class="text-left font-weight-bold border">₱
                            {{ number_format($order->total, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right font-weight-bold">Shipping Fee:</td>
                        <td class="text-left font-weight-bold border">₱
                            {{ number_format($shipping_price, 2, '.', ',') }}</td>
                    </tr>
                    @if ($order->loyalty_points != 0)
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right font-weight-bold">Loyalty Points Used:</td>
                        <td class="text-left font-weight-bold border">₱ -{{ number_format($order->loyalty_points, 2, '.', ',') }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right font-weight-bold">Total:</td>
                        <td class="text-left font-weight-bold border">₱ {{ number_format(($order->total+$shipping_price)-$order->loyalty_points, 2, '.', ',') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
    </main>
</div>
</div>
@include('pages.order.order_modal')
@endsection