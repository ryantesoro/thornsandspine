@extends('layouts.master')
@section('header_name', 'Customer')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
  @include('layouts.sidebar')
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    @include('layouts.header')
    <div class="row">
      <div class="col text-left">
        <h4>List Of Customers</h4>
      </div>
    </div>
    <hr>
    {!! Form::open(['route' => 'admin.customer.index', 'method' => 'get', 'style' => 'margin-block-end: 0;']) !!}
    <div class="row">
      <div class="col-4 offset-8">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            {!! Form::select('filter', $filters, Request::input('filter') ?? '',
            [
            'class' => 'form-control form-control-sm',
            'tab_index' => '1',
            'required' => true
            ]) !!}
          </div>
          {!! Form::text('search', Request::input('search') ?? '',
          [
          'class' => 'form-control form-control-sm',
          'placeholder' => 'Search here..',
          'tab_index' => '2'
          ]) !!}
          <div class="input-group-append">
            <button class="btn btn-primary btn-sm" tab_index="3" type="submit">Search</button>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex">
      <div class="ml-auto pb-2">
        <a href="{{ route('admin.customer.print', [
          "filter" => Request::input('filter'),
          "search" => Request::input('search')
         ]) }}" class="btn btn-warning btn-sm" tab_index="1">Print</a>
      </div>
    </div>
    {!! Form::close() !!}
    <div class="table-responsive">
      <table class="table table-hover border">
        <thead>
          <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Email</th>
            <th>Province</th>
            <th>City</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          @foreach($customers as $customer)
          <tr>
            <td>{{ucwords($customer->last_name)}}</td>
            <td>{{ucwords($customer->first_name)}}</td>
            <td>{{$customer->email}}</td>
            <td>{{ucwords($customer->province)}}</td>
            <td>{{ucwords($customer->city)}}</td>
            <td>
              <a href="{{ route('admin.customer.show', $customer->id) }}"
                class="btn btn-sm btn-primary font-weight-bold">View</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
  </main>
</div>
</div>
@endsection