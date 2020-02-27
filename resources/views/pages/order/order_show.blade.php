@extends('layouts.master')
@section('header_name', 'Orders')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Order Details</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.pot.index') }}">Go Back</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-auto">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <tbody>
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
                                    Contact Number
                                </td>
                                <td>
                                    {{ $customer->contact_number }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">
                                    Recipient Name
                                </td>
                                <td>
                                    @if ($order->recipient_first != null)
                                    {{ ucwords($customer->recipient_first.' '.$customer->recipient_last) }}
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
                                    @if ($order->recipient_address != null)
                                    {{ ucwords($customer->recipient_address).', '.$shipping_city_province }}
                                    @else
                                    {{ ucwords($customer->address).', '.$shipping_city_province }}
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
            </div>
        </div>
        <hr>
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
                        <th>Sub Total</th>
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
                        <td class="text-right font-weight-bold">Shipping Fee:</td>
                        <td class="text-left font-weight-bold border">₱
                            {{ number_format($shipping_price, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right font-weight-bold">Total:</td>
                        <td class="text-left font-weight-bold border">₱ {{ number_format($order->total, 2, '.', ',') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
    </main>
</div>
</div>
@endsection