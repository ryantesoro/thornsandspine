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
            @if(auth()->user()->access_level == 2)
            <div>
                <a class="btn btn-success font-weight-bold" href="{{ route('admin.shipping_fee.create') }}">Create
                    Shipping Fee</a>
            </div>
            @endif
        </div>
        <hr>
        {!! Form::open(['route' => 'admin.shipping_fee.index', 'method' => 'get', 'style' => 'margin-block-end: 0;']) !!}
        <div class="row">
            <div class="col-4 offset-8">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        {!! Form::select('courier_id', $couriers, Request::input('courier_id') ?? '',
                        [
                        'class' => 'form-control form-control-sm',
                        'tab_index' => '1',
                        'required' => true
                        ]) !!}
                    </div>
                    {!! Form::select('province_id', $provinces, Request::input('province_id') ?? '',
                        [
                        'class' => 'form-control form-control-sm',
                        'tab_index' => '2',
                        'required' => true
                        ]) !!}
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" tab_index="3" type="submit">Search</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="table-responsive">
            <table class="table table-hover border">
                <thead>
                    <tr>
                        <th>Shipping Agent</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Price</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipping_fees as $shipping_fee)
                    <tr>
                        <td>{{ ucwords($shipping_fee->courier_name) }}</td>
                        <td>{{ ucwords($shipping_fee->province_name) }}</td>
                        <td>{{ ucwords($shipping_fee->city) }}</td>
                        <td>{{ $shipping_fee->price }}</td>
                        <td>
                            <a href="{{ route('admin.shipping_fee.show', $shipping_fee->id) }}"
                                class="btn btn-sm btn-primary font-weight-bold">View</a>
                            <a href="{{ route('admin.shipping_fee.edit', $shipping_fee->id) }}"
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