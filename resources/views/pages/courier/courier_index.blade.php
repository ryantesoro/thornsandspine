@extends('layouts.master')
@section('header_name', 'Shipping Agents')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
  @include('layouts.sidebar')
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    @include('layouts.header')
    <div class="row">
      <div class="col text-left">
        <h4>List Of Shipping Agents</h4>
      </div>
      <div>
        <div>
          <a class="btn btn-success font-weight-bold" href="{{ route('admin.courier.create') }}">Add Shipping Agent</a>
        </div>
      </div>
    </div>
    <hr>
    <div class="table-responsive">
      <table class="table table-hover border">
        <thead>
          <tr>
            <th>Name</th>
            <th>Same Day Delivery?</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          @foreach($couriers as $courier)
          <tr>
            <td>{{ $courier->name }}</td>
            <td>
              @if ($courier->same_day == 1)
                Yes
              @else
                No
              @endif
            </td>
            <td>
              <a href="{{ route('admin.courier.edit', $courier->id) }}"
                class="btn btn-sm btn-warning font-weight-bold">Edit</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </main>
</div>
@endsection