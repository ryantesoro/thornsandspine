@extends('layouts.master')
@section('header_name', 'Pots')
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
                <h4>Edit Pot</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.pot.index') }}">Go Back</a>
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
        {!! Form::open(['route' => ['admin.pot.update', $pot_details['id']]]) !!}
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Pot Name</label>
                    {!! Form::text('pot_name', $pot_details['name'] ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'tab_index' => '2',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'focus',
                    'title' => 'Pot Name',
                    'data-content' => 'This is the name of the pot you are selling. (Minimum of 3 characters in
                    length)',
                    'required' => true
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Product Description</label>
                    {!! Form::textarea('pot_description', $pot_details['description'] ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Description',
                    'tab_index' => '1',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'focus',
                    'title' => 'Pot Description',
                    'data-content' => 'Describe the pot you are selling',
                    'required' => true,
                    'rows' => 3
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