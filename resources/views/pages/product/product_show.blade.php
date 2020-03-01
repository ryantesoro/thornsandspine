@extends('layouts.master')
@section('header_name', 'Products')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>Products Details</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ url()->previous() }}">Go Back</a>
                <a class="btn btn-warning font-weight-bold"
                    href="{{ route('admin.product.edit', ['code' => $product['code']]) }}">Edit</a>
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
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                    <tr>
                        <td class="font-weight-bold">
                            Image
                        </td>
                        <td>
                            <img src="{{ route('image', ['product', 'image_name' => $product['img'].'?size=medium']) }}">
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Code
                        </td>
                        <td>
                            #{{ $product['code'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Name
                        </td>
                        <td>
                            {{ ucwords($product['name']) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Description
                        </td>
                        <td>
                            {{ $product['description'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Price
                        </td>
                        <td>
                            ₱ {{ $product['price'] }}.00
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Created At
                        </td>
                        <td>
                            {{ $product['created_at'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Updated At
                        </td>
                        <td>
                            {{ $product['updated_at'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">
                            Status
                        </td>
                        <td>
                            @if ($product['active'] == 1)
                            <span class="badge badge-pill badge-info">shown</span>
                            @else
                            <span class="badge badge-pill badge-secondary">hidden</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
</div>
@include('pages.product.product_modal')
@endsection