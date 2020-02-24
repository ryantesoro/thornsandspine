@extends('layouts.master')
@section('header_name', 'Frequently Asked Questions (FAQ)')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>List Of Pots</h4>
            </div>
            <div>
                <a class="btn btn-success font-weight-bold" href="{{ route('admin.pot.create') }}">Create Pot</a>
            </div>
        </div>
        <hr>
    </main>
</div>
</div>
@endsection