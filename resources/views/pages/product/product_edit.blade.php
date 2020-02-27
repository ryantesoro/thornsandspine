@extends('layouts.master')
@section('header_name', 'Products')
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
                <h4>Edit Product</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.product.index') }}">Go Back</a>
                @if ($product['active'] == 1)
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hide_confirmation">
                    <b>Hide Product</b>
                </button>
                @else
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#restore_confirmation">
                    <b>Restore Product</b>
                </button>
                @endif
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
        {!! Form::open(['route' => ['admin.product.update', $product['id']], 'files' => true]) !!}
        <div class="row pl-3">
            <div class="col-5">
                <img class="border" src="{{ route('image', ['product', 'image_name' => $product['img'].'?size=medium']) }}">
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Product Image</label>
                    <input type="file" class="form-control-file" name="product_image" tabindex="1"
                        accept=".png, .jpeg, .jpg">
                </div>
                <div class="alert alert-secondary" role="alert">
                    Image must be a PNG, JPEG, or JPG in format, and it must be less than 5MB.
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Product Name</label>
                    {!! Form::text('product_name', $product['name'] ?? '',
                    [
                        'class' => 'form-control',
                        'placeholder' => 'Name',
                        'tab_index' => '2',
                        'data-toggle' => 'popover',
                        'data-trigger' => 'focus',
                        'title' => 'Product Name',
                        'data-content' => 'This is the name of the product you are selling. (Minimum of 3 characters in length)',
                        'required' => true
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Product Description</label>
                    {!! Form::textarea('product_description', $product['description'] ?? '',
                    [
                        'class' => 'form-control',
                        'placeholder' => 'Description',
                        'tab_index' => '3',
                        'data-toggle' => 'popover',
                        'data-trigger' => 'focus',
                        'title' => 'Product Description',
                        'data-content' => 'Describe the product you are selling',
                        'required' => true,
                        'rows' => 3
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-2">
                <div class="form-group">
                    <label class="font-weight-bold">Product Price (PHP) ₱</label>
                    {!! Form::number('product_price', $product['price'] ?? '',
                    [
                        'class' => 'form-control',
                        'placeholder' => 'Peso',
                        'tab_index' => '4',
                        'data-toggle' => 'popover',
                        'data-trigger' => 'focus',
                        'title' => 'Product Price',
                        'data-content' => 'This is the price of the product you are selling. It must be in PHP(₱)',
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
@include('pages.product.product_modal')
@endsection