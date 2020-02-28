@extends('layouts.master')
@section('header_name', 'Shipping Fees')
@section('content')
@include('layouts.nav')

@section('js')
<script>
    $(function () {
  $('[data-toggle="popover"]').popover()
})

var checker = {!! json_encode($checker) !!}

changeCitySelect();
function changeCitySelect()
{
    $.each(checker[$("#province").val()], function (city_id, city_name) {
        $(city).append($('<option>').val(city_id).text(city_name));
    });
}

$("#province").change(function() {
    $("#city option").each(function() {
        $(this).remove();
    });
    changeCitySelect();
});

</script>
@endsection

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Create Shipping Fee</h4>
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
        {!! Form::open(['route' => 'admin.shipping_fee.store']) !!}
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Province</label>
                    {!! Form::select('shipping_agent', $couriers, old('shipping_agent') ?? '',
                    [
                    'class' => 'form-control',
                    'tab_index' => '1',
                    'required' => true
                    ]) !!}
                    <div class="d-flex justify-content-end pt-2">
                        <a href="{{ route('admin.courier.create') }}" class="btn btn-sm btn-info">
                            <b>Add Shipping Agent</b>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Province</label>
                    {!! Form::select('shipping_province', $provinces, old('shipping_province') ?? '',
                    [
                    'id' => 'province',
                    'class' => 'form-control',
                    'tab_index' => '1',
                    'required' => true
                    ]) !!}
                    <div class="d-flex justify-content-end pt-2">
                        <a href="{{ route('admin.province.create') }}" class="btn btn-sm btn-info">
                            <b>Add Province</b>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping City</label>
                    {!! Form::select('shipping_city', [], old('shipping_city') ?? '',
                    [
                    'id' => 'city',
                    'class' => 'form-control',
                    'tab_index' => '2',
                    'required' => true
                    ]) !!}
                    <div class="d-flex justify-content-end pt-2">
                        <a href="{{ route('admin.city.create') }}" class="btn btn-sm btn-info">
                            <b>Add City</b>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-2">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Price (PHP) ₱</label>
                    {!! Form::number('shipping_price', old('shipping_price') ?? '',
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
@endsection