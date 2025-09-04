@extends('Backend.layouts.master')

@section('title') @lang('Starter_Page') @endsection

@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  <a href="{{ route('backend.pages') }}" > Pages </a>  @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body p-4">
                <h6 class="mt-4">Meta Title : {{ $pageData->meta_title_en }}</h6>
                <h6 class="mt-4">Meta Keywords : {{ $pageData->meta_keywords_en }} </h6>
                <h6 class="mt-4">Meta Description : {{ $pageData->meta_desc_en }} </h6>
                <h6 class="mt-4">Page Title : {{ $pageData->title_en  }} </h6>
                <h6 class="mt-4">Page Content</h6>
                <div class="card-body p-5" >
                    <?=$pageData->page_content?>
                </div>
                <a href="{{ route('backend.pages') }}" class="btn btn-secondary waves-effect mt-4"><i class="mdi mdi-reply me-1"></i> Back</a>
            </div>

        </div>
    </div>
    <!-- end Col -->

</div>

@endsection