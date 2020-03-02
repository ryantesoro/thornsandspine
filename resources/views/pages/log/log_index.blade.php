@extends('layouts.master')
@section('header_name', 'Logs')
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

<div class="container-fluid">
  @include('layouts.sidebar')
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    @include('layouts.header')
    <div class="table-responsive">
      <table class="table table-fixed border">
          <thead>
              <tr>
                  <th scope="col" class="col-3">Email</th>
                  <th scope="col" class="col-3">Access Level</th>
                  <th scope="col" class="col-3">Action</th>
                  <th scope="col" class="col-3">Date</th>
              </tr>
          </thead>
          <tbody>
            @foreach ($logs as $log)
            <tr>
                <td class="col-3">{{ $log->email }}</td>
                @if ($log->email == 1)
                    <td class="col-3">Admin</td>
                @else
                    <td class="col-3">Super Admin</td>
                @endif
                <td class="col-3">{{ $log->action }}</td>
                <td class="col-3">{{ $log->created_at }}</td>
            </tr>
            @endforeach
          </tbody>
      </table>
    </div>
  </main>
</div>
@endsection