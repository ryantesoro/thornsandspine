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
                        <td>{{ $pot['name'] }}</td>
                        <td class="pt-3">
                            @if (empty($pot['deleted_at']))
                            <span class="badge badge-pill badge-info">shown</span>
                            @else
                            <span class="badge badge-pill badge-secondary">hidden</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.product.show', $pot['id']) }}"
                                class="btn btn-sm btn-primary font-weight-bold">View</a>
                            <a href="{{ route('admin.product.edit', $pot['id']) }}"
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