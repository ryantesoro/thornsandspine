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
                <h4>Promotion Details</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.promotion.index') }}">Go Back</a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_confirmation">
                    <b>Delete</b>
                </button>
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
                            {{ $promotion->name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Image
                        </td>
                        <td>
                            <img src="{{ route('image', ['promotion', 'image_name' => $promotion->file_name.'?size=medium']) }}">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
</div>
{!! Form::open(['route' => ['admin.promotion.destroy', $promotion->id]]) !!}
<div class="modal fade" id="delete_confirmation" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this promotion?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection