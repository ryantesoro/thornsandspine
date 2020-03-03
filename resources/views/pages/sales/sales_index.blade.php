@extends('layouts.master')
@section('header_name', 'Sales')
@section('content')
@include('layouts.nav')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
<style>
  .table-fixed tbody {
    height: 300px;
    overflow-y: auto;
    width: 100%;
  }

  .table-fixed thead,
  .table-fixed tbody,
  .table-fixed tr,
  .table-fixed td,
  .table-fixed th {
    display: block;
  }

  .table-fixed tbody td,
  .table-fixed tbody th,
  .table-fixed thead>tr>th {
    float: left;
    position: relative;

    &::after {
      content: '';
      clear: both;
      display: block;
    }
  }
</style>
@endsection

@section('js')
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/knockout.js') }}"></script>
<script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
<script>
  var date_range_picker = $("#date_time_picker").daterangepicker({
    minDate: moment().subtract(2, 'years'),
    orientation: 'left',
    ranges: {},
    periods : ['day']
  }, function (startDate, endDate, period) {
    $('input[name=start_date]').val(startDate.format('L'));
    $('input[name=end_date]').val(endDate.format('L'));
    $(this).val(startDate.format('L') + ' - ' + endDate.format('L'))
  });
</script>
@endsection


<div class="container-fluid">
  @include('layouts.sidebar')
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    @include('layouts.header')
    {!! Form::open(['route' => 'admin.sales.index', 'method' => 'get', 'style' => 'margin-block-end: 0;']) !!}
    <input type="hidden" name="start_date" value="{{ Request::input('start_date') }}">
    <input type="hidden" name="end_date" value="{{ Request::input('end_date') }}">
    <div class="row">
      <div class="offset-7 col-2">
        <div class="form-group">
          <label class="font-weight-bold">Group By</label>
          {!! Form::select('group_by', ['day' => 'Day', 'month' => 'Month', 'year' => 'Year'],
          Request::input('group_by') ?? '',
          [
          'class' => 'form-control form-control-sm',
          'tab_index' => '1',
          'required' => true
          ]) !!}
        </div>
      </div>
      <div class="col-3">
        <div class="form-group">
          <label class="font-weight-bold">Date Range</label>
          <input type="text" class="form-control form-control-sm" id="date_time_picker" required="true" value="{{ Request::input('start_date') == null 
            ? \Carbon\Carbon::now()->subDays(7)->format('m/d/Y').' - '.\Carbon\Carbon::now()->format('m/d/Y') 
            : Request::input('start_date').' - '.Request::input('end_date') }}">
        </div>
      </div>
    </div>
    <div class="d-flex">
      <div class="ml-auto pb-2">
        <a href="{{ route('admin.sales.print', [
          "start_date" => Request::input('start_date'),
          "end_date" => Request::input('end_date'),
          "group_by" => Request::input('group_by'),
          "order_by" => Request::input('order_by'),
          "sort" => Request::input('sort') 
         ]) }}" class="btn btn-warning btn-sm" tab_index="1">Print</a>
        <button class="btn btn-primary btn-sm" tab_index="2" type="submit">Apply Filters</button>
      </div>
    </div>
    {!! Form::close() !!}
    <div class="table-responsive">
      <table class="table table-fixed border">
        <thead>
          <tr>
            <th scope="col" class="col-3"><a href="{{ route('admin.sales.index', [
              "start_date" => Request::input('start_date'),
              "end_date" => Request::input('end_date'),
              "group_by" => Request::input('group_by'),
              "order_by" => "date",
              "sort" => Request::input('order_by') == 'date' && Request::input('sort') == 'desc' ? 'asc' : 'desc' 
             ]) }}">Date</a></th>
            <th scope="col" class="col-2"><a href="{{ route('admin.sales.index', [
              "start_date" => Request::input('start_date'),
              "end_date" => Request::input('end_date'),
              "group_by" => Request::input('group_by'),
              "order_by" => "total_orders",
              "sort" => Request::input('order_by') == 'total_orders' && Request::input('sort') == 'desc' ? 'asc' : 'desc' 
             ]) }}">Total Orders</a></th>
             <th scope="col" class="col-2"><a href="{{ route('admin.sales.index', [
              "start_date" => Request::input('start_date'),
              "end_date" => Request::input('end_date'),
              "group_by" => Request::input('group_by'),
              "order_by" => "total_loyalty_points",
              "sort" => Request::input('order_by') == 'total_loyalty_points' && Request::input('sort') == 'desc' ? 'asc' : 'desc' 
             ]) }}">Loyalty Points</a></th>
            <th scope="col" class="col-3"><a href="{{ route('admin.sales.index', [
              "start_date" => Request::input('start_date'),
              "end_date" => Request::input('end_date'),
              "group_by" => Request::input('group_by'),
              "order_by" => "total_sales",
              "sort" => Request::input('order_by') == 'total_sales' && Request::input('sort') == 'desc' ? 'asc' : 'desc' 
             ]) }}">Total Sales</a></th>
            <th scope="col" class="col-2">Options</th>
          </tr>
        </thead>
        <tbody>
          @foreach($sales as $sale)
          <tr>
            <th scope="row" class="col-3">{{ $sale['date'] }}</th>
            <td class="col-2">{{ $sale['total_orders'] }}</td>
            <td class="col-2">{{ $sale['total_loyalty_points'] }}</td>
            <td class="col-3">₱ {{ number_format($sale['total_sales'], 2, '.', ',') }}</td>
            <td class="col-2">
              @if ($sale['total_orders'] == 0)
              <a href="#" class="btn btn-sm btn-outline-secondary font-weight-bold disabled">View Orders</a>
              @else
              <a href="{{ route('admin.order.index', [
                  'codes' => $sale['codes']
                ]) }}" class="btn btn-sm btn-primary font-weight-bold">View Orders</a>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
        <thead>
          <tr>
            <th scope="col" class="col-3">TOTALS</th>
            <th scope="col" class="col-2">{{ $total['orders'] }}</th>
            <th scope="col" class="col-2">{{ $total['loyalty_points'] }}</th>
            <th scope="col" class="col-3">₱ {{ number_format($total['sales'], 2, '.', ',') }}</th>
            <th scope="col" class="col-2"></th>
          </tr>
        </thead>
      </table>
  </main>
</div>
</div>
@endsection