@extends('layouts.master')
@section('header_name', 'Frequently Asked Questions (FAQ)')
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
                <h4>Edit FAQ</h4>
            </div>
            <div>
                <a class="btn btn-secondary font-weight-bold" href="{{ route('admin.faq.index') }}">Go Back</a>
                @if ($faq_details['active'] == 1)
                <button type="button" class="btn btn-danger" data-toggle="modal"
                    data-target="#hide_confirmation">
                    <b>Hide</b>
                </button>
                @else
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#restore_confirmation">
                    <b>Restore</b>
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
        {!! Form::open(['route' => ['admin.faq.update', $faq_details['id']]]) !!}
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Question</label>
                    {!! Form::textarea('question', $faq_details['question'] ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'tab_index' => '1',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'focus',
                    'title' => 'Question',
                    'data-content' => 'This is the most asked question by the customers.',
                    'required' => true,
                    'rows' => 3
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="row pl-3">
            <div class="col-5">
                <div class="form-group">
                    <label class="font-weight-bold">Answer</label>
                    {!! Form::textarea('answer', $faq_details['answer'] ?? '',
                    [
                    'class' => 'form-control',
                    'placeholder' => 'Description',
                    'tab_index' => '2',
                    'data-toggle' => 'popover',
                    'data-trigger' => 'focus',
                    'title' => 'Answer',
                    'data-content' => 'This is the answer for the question, Elaborate your answer.',
                    'required' => true,
                    'rows' => 5
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
@include('pages.faq.faq_modal')
@endsection