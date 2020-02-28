@extends('layouts.master')
@section('header_name', 'Cities')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>List Of Cities</h4>
            </div>
            @if(auth()->user()->access_level == 2)
            <div>
                <a class="btn btn-success font-weight-bold" href="{{ route('admin.city.create') }}">Add
                    City</a>
            </div>
            @endif
        </div>
        <hr>
        {!! Form::open(['route' => 'admin.city.index', 'method' => 'get', 'style' => 'margin-block-end: 0;']) !!}
        <div class="row">
            <div class="col-4 offset-8">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        {!! Form::select('province_id', $provinces, Request::input('province_id') ?? '',
                        [
                        'class' => 'form-control form-control-sm',
                        'tab_index' => '1',
                        'required' => true
                        ]) !!}
                    </div>
                    {!! Form::text('city', Request::input('city') ?? '',
                    [
                    'class' => 'form-control form-control-sm',
                    'placeholder' => 'City name',
                    'tab_index' => '2'
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
                        <th>City</th>
                        <th>Province</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cities as $city)
                    <tr>
                        <td>{{ ucwords($city->city) }}</td>
                        <td>{{ ucwords($city->name) }}</td>
                        <td>
                            <a href="{{ route('admin.city.edit', $city->id) }}"
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