@extends('layouts.master')
@section('header_name', 'Cities')
@section('content')
@include('layouts.nav')

@section('js')
<script>
    $(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
@endsection

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Edit City</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.city.index') }}">Go Back</a>
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
        {!! Form::open(['route' => ['admin.city.update', $city_details['id']]]) !!}
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Province</label>
                    {!! Form::select('province', $provinces, $city_details['province_id'] ?? '',
                    [
                    'class' => 'form-control',
                    'tab_index' => '1',
                    'required' => true,
                    'disabled' => auth()->user()->access_level != 2
                    ]) !!}
                    @if (auth()->user()->access_level == 2)
                    <div class="d-flex justify-content-end pt-2">
                        <a href="{{ route('admin.province.create') }}" class="btn btn-sm btn-info">
                            <b>Add Province</b>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">City</label>
                    {!! Form::text('city', $city_details['city'] ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'tab_index' => '2',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'focus',
                    'title' => 'City',
                    'data-content' => 'The city must be in the philippines',
                    'required' => true,
                    'disabled' => auth()->user()->access_level != 2
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