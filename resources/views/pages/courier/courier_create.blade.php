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
                <h4>Create Shipping Agent</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.courier.index') }}">Go Back</a>
            </div>
        </div>
        <hr>
        @if (session()->has('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
            <button type="button" class="close mt-1" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        {!! Form::open(['route' => 'admin.courier.store']) !!}
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Agent Name</label>
                    {!! Form::text('shipping_agent_name', old('shipping_agent_name') ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'tab_index' => '1',
                    'required' => true
                    ]) !!}
                </div>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-center">
            <button type="reset" class="btn btn-danger">
                Reset
            </button>
            <button type="submit" class="btn btn-primary ml-2">
                Submit
            </button>
        </div>
        {!! Form::close() !!}
    </main>
</div>
</div>
@endsection