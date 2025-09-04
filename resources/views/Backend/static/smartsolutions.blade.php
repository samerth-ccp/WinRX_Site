@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  Home Page   @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" action="{{ route('backend.smartsolutions') }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_heading">Smart Solutions Section Heading</label>
                                <input type="text" class="form-control required" name="smart_section_heading" id="smart_section_heading" placeholder="Section Heading" value="{{ $smartContent->smart_section_heading ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_subheading">Smart Solutions Section Sub Heading</label>
                                <input type="text" class="form-control required" name="smart_section_subheading" id="smart_section_subheading" placeholder="Section Sub Heading" value="{{ $smartContent->smart_section_subheading ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_para">Smart Solutions Section Para</label>
                                <textarea id="smart_section_para" name="smart_section_para" class="form-control required" placeholder="Section Para" maxlength="400">{{ $smartContent->smart_section_para ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_first_heading">Smart Solutions Section First Heading</label>
                                <input type="text" class="form-control required" name="smart_section_first_heading" id="smart_section_first_heading" placeholder="Section First Heading" value="{{ $smartContent->smart_section_first_heading ?? '' }}" maxlength="60" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_first_para">Smart Solutions Section First Para</label>
                                <input type="text" class="form-control required" name="smart_section_first_para" id="smart_section_first_para" placeholder="Section First Para" value="{{ $smartContent->smart_section_first_para ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_second_heading">Smart Solutions Section Second Heading</label>
                                <input type="text" class="form-control required" name="smart_section_second_heading" id="smart_section_second_heading" placeholder="Section Second Heading" value="{{ $smartContent->smart_section_second_heading ?? '' }}" maxlength="60" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_second_para">Smart Solutions Section Second Para</label>
                                <input type="text" class="form-control required" name="smart_section_second_para" id="smart_section_second_para" placeholder="Section Second Para" value="{{ $smartContent->smart_section_second_para ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_third_heading">Smart Solutions Section Third Heading</label>
                                <input type="text" class="form-control required" name="smart_section_third_heading" id="smart_section_third_heading" placeholder="Section Third Heading" value="{{ $smartContent->smart_section_third_heading ?? '' }}" maxlength="60" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="smart_section_third_para">Smart Solutions Section Third Para</label>
                                <input type="text" class="form-control required" name="smart_section_third_para" id="smart_section_third_para" placeholder="Section Third Para" value="{{ $smartContent->smart_section_third_para ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $('#pages').validate();
        });
    </script>
@endsection
