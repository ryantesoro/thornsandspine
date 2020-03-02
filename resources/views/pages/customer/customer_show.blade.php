@extends('layouts.master')
@section('header_name', 'Customers')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Customer Details</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.customer.index') }}">Go Back</a>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                    <tr>
                        <td class="font-weight-bold">
                            First Name
                        </td>
                        <td>
                            {{ ucwords($customer_details->first_name) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Last Name
                        </td>
                        <td>
                            {{ ucwords($customer_details->last_name) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Email Address
                        </td>
                        <td>
                            {{ $user_details->email }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Contact Number
                        </td>
                        <td>
                            {{ $customer_details->contact_number }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Address
                        </td>
                        <td>
                            {{ ucwords($customer_details->address) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            City
                        </td>
                        <td>
                            {{ ucwords($customer_details->city) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Province
                        </td>
                        <td>
                            {{ ucwords($customer_details->province) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Loyalty Points
                        </td>
                        <td>
                            {{ $customer_details->loyaty_points ?? 0 }} pts.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
</div>
@endsection