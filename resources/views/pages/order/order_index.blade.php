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
        <h4>List Of Orders</h4>
      </div>
    </div>
    <hr>
    {!! Form::open(['route' => 'admin.order.index', 'method' => 'get', 'style' => 'margin-block-end: 0;']) !!}
    <div class="row">
      <div class="col-3 offset-9">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <div class="input-group-prepend">
              {!! Form::select('status', [
                'all' => 'All',
                '0' => 'Pending',
                '1' => 'Processing',
                '2' => 'Completed',
                '3' => 'Cancelled',
                'expired' => 'Expired'
              ], Request::input('status') ?? '',
              [
              'class' => 'form-control form-control-sm',
              'tab_index' => '1',
              'required' => true
              ]) !!}
            </div>
          </div>
          {!! Form::text('code', Request::input('code') ?? '',
          [
          'class' => 'form-control form-control-sm',
          'placeholder' => 'Order Code',
          'tab_index' => '1'
          ]) !!}
          <div class="input-group-append">
            <button class="btn btn-primary btn-sm" tab_index="2" type="submit">Search</button>
          </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
    <div class="table-responsive">
      <table class="table table-hover border">
        <thead>
          <tr>
            <th>Code</th>
            <th>Recipient Last Name</th>
            <th>Recipient First Name</th>
            <th>Status</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $order)
          <tr>
            <td>{{ $order->code }}</td>
            <td>{{ !empty($order->r_lname) ? ucwords($order->r_lname) : ucwords($order->c_lname) }}</td>
            <td>{{ !empty($order->r_fname) ? ucwords($order->r_fname) : ucwords($order->c_fname) }}</td>
            <td class="pt-3">
              @if($order->status == 0 && \Carbon\Carbon::parse($order->expires_at)->isPast())
              <span class="badge badge-pill badge-danger">expired</span>
              @elseif ($order->status == 0)
              <span class="badge badge-pill badge-secondary">pending</span>
              @elseif ($order->status == 1)
              <span class="badge badge-pill badge-info">processing</span>
              @elseif ($order->status == 2)
              <span class="badge badge-pill badge-primary">delivered</span>
              @elseif ($order->status == 3)
              <span class="badge badge-pill badge-danger">cancelled</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.order.show', $order->code) }}"
                class="btn btn-sm btn-primary font-weight-bold">View</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </main>
</div>
@endsection