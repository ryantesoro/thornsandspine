@extends('layouts.master')
@section('header_name', 'Reports')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
  @include('layouts.sidebar')
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    @include('layouts.header')
  </main>
</div>
</div>
@endsection