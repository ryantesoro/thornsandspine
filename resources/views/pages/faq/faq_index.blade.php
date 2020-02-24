@extends('layouts.master')
@section('header_name', 'Frequently Asked Questions (FAQ)')
@section('content')
@include('layouts.nav')

<div class="container-fluid">
    @include('layouts.sidebar')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.header')
        <div class="row">
            <div class="col text-left">
                <h4>List Of FAQs</h4>
            </div>
            <div>
                <a class="btn btn-success font-weight-bold" href="{{ route('admin.faq.create') }}">Create Pot</a>
            </div>
        </div>
        <hr>
        <div id="accordion" role="tablist">
            @foreach($faqs as $faq)
            <div class="card">
                <div class="card-header" role="tab">
                    <h5 class="mb-0">
                        <div class="d-flex">
                            <div class="mr-auto">
                                <a data-toggle="collapse" href="#question_{{ $faq['id'] }}">
                                    {{ $faq['question'] }}
                                </a>
                            </div>

                            <div class="ml-auto">
                                <a class="btn btn-sm btn-warning font-weight-bold" href="">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </h5>
                </div>

                <div id="question_{{ $faq['id'] }}" class="collapse" role="tabpanel">
                    <div class="card-body">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>
</div>
</div>
@endsection