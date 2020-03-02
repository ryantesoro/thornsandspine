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
function changeCitySelect(city_id)
{
    $.each(checker[$("#province").val()], function (city_id, city_name) {
        $('#city').append($('<option>').val(city_id).text(city_name));
    });
    if (city_id != null) {
        $('#city option[value="'+city_id+'"]').attr("selected",true);
    }
}

$("#province").change(function() {
    $("#city option").each(function() {
        $(this).remove();
    });
    changeCitySelect(null);
});

$('#province option[value="{{ $province_id }}"]').attr("selected",true);
changeCitySelect({{$shipping_fee_details->city_province_id}});

</script>
@endsection

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Edit Shipping Fee</h4>
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
        {!! Form::open(['route' => ['admin.shipping_fee.update', $shipping_fee_details['id']]]) !!}
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Agent</label>
                    {!! Form::select('shipping_agent', $couriers, $current_courier ?? '',
                    [
                    'class' => 'form-control',
                    'tab_index' => '1',
                    'required' => true,
                    'disabled' => auth()->user()->access_level != 2
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Province</label>
                    {!! Form::select('shipping_province', $provinces, '' ?? '',
                    [
                    'id' => 'province',
                    'class' => 'form-control',
                    'tab_index' => '2',
                    'required' => true,
                    'disabled' => auth()->user()->access_level != 2
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping City</label>
                    {!! Form::select('shipping_city', [], '' ?? '',
                    [
                    'id' => 'city',
                    'class' => 'form-control',
                    'tab_index' => '3',
                    'required' => true,
                    'disabled' => auth()->user()->access_level != 2
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-2">
                <div class="form-group">
                    <label class="font-weight-bold">Shipping Price (PHP) ₱</label>
                    {!! Form::number('shipping_price', $shipping_fee_details['price'] ?? '',
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