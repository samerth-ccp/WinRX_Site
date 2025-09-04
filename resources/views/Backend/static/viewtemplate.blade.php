@extends('Backend.layouts.master')

@section('title') @lang('Starter_Page') @endsection

@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  <a href="{{ route('backend.emailtemplate') }}" > Emails </a>  @endslot
@endcomponent


<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body p-4">
                <h6 class="mt-4">Title : {{ $templateData->template_title }}</h6>
                <h6 class="mt-4">Subject : {{ $templateData->template_subject }} </h6>
                <h6 class="mt-4">Template</h6>
                <div class="card-body p-5" style="display: flex;justify-content: center;">
                    <?=$templateData->template_content?>
                </div>
                <a href="{{ route('backend.emailtemplate') }}" class="btn btn-secondary waves-effect mt-4"><i class="mdi mdi-reply me-1"></i> Back</a>
            </div>

        </div>
    </div>
    <!-- end Col -->

</div>

@endsection