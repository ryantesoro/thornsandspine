@extends('layouts.master')
@section('header_name', 'Shipping Provinces')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Shipping Province Details</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.shipping_province.index') }}">Go Back</a>
                <a class="btn btn-warning font-weight-bold" href="{{ route('admin.shipping_province.edit', ['province_id' => $province_details['id']]) }}">Edit</a>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                    <tr>
                        <td class="font-weight-bold">
                            Name
                        </td>
                        <td>
                            {{ ucwords($province_details['name']) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
</div>
@endsection