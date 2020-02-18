@extends('layouts.master')
@section('header_name', 'Shipping Fees')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>List Of Shipping Fees</h4>
            </div>
            <div>
                <a class="btn btn-success font-weight-bold" href="{{ route('admin.shipping_fee.create') }}">Create Shipping Fee</a>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover border">
                <thead>
                    <tr>
                        <th>Province</th>
                        <th>City</th>
                        <th>Price</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipping_fees as $shipping_fee)
                    <tr>
                        <td>{{ $shipping_fee['province'] }}</td>
                        <td>{{ $shipping_fee['city'] }}</td>
                        <td>{{ $shipping_fee['price'] }}</td>
                        <td>
                            <a href="{{ route('admin.shipping_fee.show', $shipping_fee['id']) }}"
                                class="btn btn-sm btn-primary font-weight-bold">View</a>
                            <a href="{{ route('admin.shipping_fee.edit', $shipping_fee['id']) }}"
                                class="btn btn-sm btn-warning font-weight-bold">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </main>
</div>
</div>
@endsection