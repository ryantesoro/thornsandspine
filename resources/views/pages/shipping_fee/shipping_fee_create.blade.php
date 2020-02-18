@extends('layouts.master')
@section('header_name', 'Shipping Fees')
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
                <h4>Create Pot</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.shipping_fee.index') }}">Go Back</a>
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
        {!! Form::open(['route' => 'admin.pot.store']) !!}
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Province</label>
                    {!! Form::select('shipping_province', old('shipping_province') ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'tab_index' => '1',
                    'required' => true
                    ]) !!}
                    <div class="d-flex justify-content-end pt-2">
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                            data-target="#add_province">
                            <b>Add Province</b>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping City</label>
                    {!! Form::text('shipping_city', old('shipping_city') ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'tab_index' => '1',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'focus',
                    'title' => 'Shipping City',
                    'data-content' => 'The city must be in the philippines',
                    'required' => true
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-2">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Price (PHP) ₱</label>
                    {!! Form::number('product_price', $product['price'] ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Peso',
                    'tab_index' => '3',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'focus',
                    'title' => 'Shipping Price',
                    'data-content' => 'This must be in PHP(₱)',
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
@include('pages.shipping_fee.shipping_fee_modal')
@endsection