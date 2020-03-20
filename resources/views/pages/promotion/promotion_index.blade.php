@extends('layouts.master')
@section('header_name', 'Promotions')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
  @include('layouts.sidebar')
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    @include('layouts.header')
    <div class="row">
      <div class="col text-left">
        <h4>List Of Promotions</h4>
      </div>
      <div>
        <a class="btn btn-success font-weight-bold" href="{{ route('admin.promotion.create') }}">Create Promotion</a>
      </div>
    </div>
    <hr>
    <div class="table-responsive">
      <table class="table table-hover border">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          @foreach($promotions as $promotion)
          <tr>
            <td>{{$promotion->id}}</td>
            <td>{{$promotion->name}}</td>
            <td>
              <a href="{{ route('admin.promotion.show', $promotion->id) }}"
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