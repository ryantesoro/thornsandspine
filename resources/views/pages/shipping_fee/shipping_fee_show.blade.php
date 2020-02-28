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
                <h4>Shipping Fee Details</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.shipping_fee.index') }}">Go Back</a>
                <a class="btn btn-warning font-weight-bold" href="{{ route('admin.shipping_fee.edit', ['shipping_fee_id' => $shipping_fee_details['id']]) }}">Edit</a>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                    <tr>
                        <td class="font-weight-bold">
                            Courier Name
                        </td>
                        <td>
                            {{ $courier_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Province
                        </td>
                        <td>
                            {{ $province_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            City
                        </td>
                        <td>
                            {{ ucwords($city_name) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Price
                        </td>
                        <td>
                            ₱ {{ $shipping_fee_details->price }}.00
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
</div>
@endsection