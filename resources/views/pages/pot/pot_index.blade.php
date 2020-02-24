@extends('layouts.master')
@section('header_name', 'Pots')
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
        {!! Form::open(['route' => 'admin.pot.index', 'method' => 'get', 'style' => 'margin-block-end: 0;']) !!}
        <div class="row">
            <div class="col-3 offset-9">
                <div class="input-group mb-3">
                    {!! Form::text('name', Request::input('name') ?? '',
                    [
                    'class' => 'form-control form-control-sm',
                    'placeholder' => 'Pot name',
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
                        <th>Name</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pots as $pot)
                    <tr>
                        <td>{{ ucwords($pot['name']) }}</td>
                        <td class="pt-3">
                            @if ($pot['active'] == 1)
                            <span class="badge badge-pill badge-info">shown</span>
                            @else
                            <span class="badge badge-pill badge-secondary">hidden</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pot.show', $pot['id']) }}"
                                class="btn btn-sm btn-primary font-weight-bold">View</a>
                            <a href="{{ route('admin.pot.edit', $pot['id']) }}"
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