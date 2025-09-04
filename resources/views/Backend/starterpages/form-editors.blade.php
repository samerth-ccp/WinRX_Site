@extends('Backend.layouts.master')

@section('title') @lang('Editors') @endsection

@section('content')

@component('Backend.components.breadcrumb')
@slot('li_1') Forms @endslot
@slot('title') Editors @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Ckeditor Classic editor</h4>
                <p class="card-title-desc">Example of Ckeditor Classic editor</p>
            </div>
            <div class="card-body">
                <div id="ckeditor-classic"></div>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')

<!-- ckeditor -->
<script src="{{ URL::asset('/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

<!-- init js -->
<script src="{{ URL::asset('/assets/js/pages/form-editor.init.js') }}"></script>

@endsection