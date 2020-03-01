@extends('layouts.master')
@section('header_name', 'Configuration')
@section('content')
@include('layouts.nav')

@section('js')
<script>
  $(function () {
  $('[data-toggle="popover"]').popover()
});

function isNumber(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;

    return true;
}
</script>
@endsection

<div class="container-fluid">
  @include('layouts.sidebar')
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    @include('layouts.header')
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
    <div class="row">
      <div class="col text-left">
        <h4>Business Details</h4>
      </div>
    </div>
    <hr>
    {!! Form::open(['route' => 'admin.config.update']) !!}
    <div class="row pl-3">
      <div class="col-5">
        <div class="form-group">
          <label class="font-weight-bold">Compelete Address</label>
          {!! Form::textarea('address', $configuration['address'] ?? '',
          [
          'class' => 'form-control',
          'tab_index' => '1',
          'data-toggle' => 'popover',
          'data-trigger' => 'focus',
          'title' => 'Complete Address',
          'rows' => 3,
          'data-content' => 'This address will be displayed to the customers',
          'required' => true
          ]) !!}
        </div>
      </div>
    </div>
    <div class="row pl-3">
      <div class="col-5">
        <div class="form-group">
          <label class="font-weight-bold">Contact number</label>
          {!! Form::text('contact_number', $configuration['contact_number'] ?? '',
          [
          'class' => 'form-control',
          'tab_index' => '1',
          'data-toggle' => 'popover',
          'data-trigger' => 'focus',
          'title' => 'Contact Number',
          'onkeypress' => 'return isNumber(event)',
          'maxlength' => '11',
          'min-length' => '8',
          'data-content' => 'This contact number will be displayed to the customers',
          'required' => true
          ]) !!}
        </div>
      </div>
    </div>
    <div class="row pl-3">
      <div class="col-5">
        <div class="form-group">
          <label class="font-weight-bold">Email Address</label>
          {!! Form::text('email', $configuration['email'] ?? '',
          [
          'class' => 'form-control',
          'tab_index' => '2',
          'data-toggle' => 'popover',
          'data-trigger' => 'focus',
          'title' => 'Email Address',
          'data-content' => 'This email address will be displayed to the customers',
          'required' => true
          ]) !!}
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col text-left">
        <h4>Bank Transfer Information</h4>
      </div>
    </div>
    <hr>
    <div class="row pl-3">
      <div class="col-5">
        <div class="form-group">
          <label class="font-weight-bold">Bank Name</label>
          {!! Form::text('bank_name', $configuration['bank_name'] ?? '',
          [
          'class' => 'form-control',
          'tab_index' => '3',
          'data-toggle' => 'popover',
          'data-trigger' => 'focus',
          'title' => 'Bank Name',
          'data-content' => 'This is the name of the bank you will use to make transactions with the customers',
          'required' => true
          ]) !!}
        </div>
      </div>
    </div>
    <div class="row pl-3">
      <div class="col-5" data-toggle="popover" data-trigger="focus" title="Card Number" data-content="Enter your bank number carefully to avoid customers from sending money to other bank
      account">
        <div class="form-group">
          <label class="font-weight-bold">Card Number</label>
          <div class="input-group">
            {!! Form::text('card_number_1', $configuration['card_number_1'] ?? '',
            [
            'class' => 'form-control',
            'tab_index' => '4',
            'onkeypress' => 'return isNumber(event)',
            'size' => '4',
            'maxlength' => '4',
            'minlength' => '4',
            'required' => true
            ]) !!}
            <div class="input-group-append">
              <span class="input-group-text">-</span>
            </div>
            {!! Form::text('card_number_2', $configuration['card_number_2'] ?? '',
            [
            'class' => 'form-control',
            'tab_index' => '5',
            'onkeypress' => 'return isNumber(event)',
            'size' => '4',
            'maxlength' => '4',
            'minlength' => '4',
            'required' => true
            ]) !!}
            <div class="input-group-append">
              <span class="input-group-text">-</span>
            </div>
            {!! Form::text('card_number_3', $configuration['card_number_3'] ?? '',
            [
            'class' => 'form-control',
            'tab_index' => '6',
            'onkeypress' => 'return isNumber(event)',
            'size' => '4',
            'maxlength' => '4',
            'minlength' => '4',
            'required' => true
            ]) !!}
            <div class="input-group-append">
              <span class="input-group-text">-</span>
            </div>
            {!! Form::text('card_number_4', $configuration['card_number_4'] ?? '',
            [
            'class' => 'form-control',
            'tab_index' => '6',
            'onkeypress' => 'return isNumber(event)',
            'size' => '4',
            'maxlength' => '4',
            'minlength' => '4',
            'required' => true
            ]) !!}
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col text-left">
        <h4>GCash Information</h4>
      </div>
    </div>
    <hr>
    <div class="row pl-3">
      <div class="col-5">
        <div class="form-group">
          <label class="font-weight-bold">Registered GCash Mobile Number</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">+63</span>
            </div>
            {!! Form::text('gcash_number', $configuration['gcash_number'] ?? '',
            [
            'class' => 'form-control',
            'tab_index' => '1',
            'data-toggle' => 'popover',
            'data-trigger' => 'focus',
            'onkeypress' => 'return isNumber(event)',
            'maxlength' => '10',
            'min-length' => '10',
            'title' => 'Contact Number',
            'data-content' => 'Enter a register GCash mobile number you will use to make transactions with the
            customers',
            'required' => true
            ]) !!}
          </div>
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