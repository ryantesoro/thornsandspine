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
                <h4>Pot Details</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.pot.index') }}">Go Back</a>
                <a class="btn btn-warning font-weight-bold" href="{{ route('admin.pot.edit', ['pot_id' => $pot_details['id']]) }}">Edit</a>
                @if ($pot_details['active'] == 1)
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hide_confirmation">
                    <b>Hide Pot</b>
                </button>
                @else
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#restore_confirmation">
                    <b>Restore Pot</b>
                </button>
                @endif
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                    <tr>
                        <td class="font-weight-bold">
                            Name
                        </td>
                        <td>
                            {{ ucwords($pot_details['name']) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Description
                        </td>
                        <td>
                            {{ $pot_details['description'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Created At
                        </td>
                        <td>
                            {{ $pot_details['created_at'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Updated At
                        </td>
                        <td>
                            {{ $pot_details['updated_at'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
</div>
@include('pages.pot.pot_modal')
@endsection