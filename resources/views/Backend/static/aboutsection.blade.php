@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  <a href="javascript:void(0)" > Home Page </a>  @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" action="{{ route('backend.aboutsection') }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_main_heading">About Section Main Heading</label>
                                <input type="text" class="form-control required" name="about_section_main_heading" id="about_section_main_heading" placeholder="Main Heading" value="{{ $aboutContent->about_section_main_heading ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_heading">About Section Heading</label>
                                <input type="text" class="form-control required" name="about_section_heading" id="about_section_heading" placeholder="Heading" value="{{ $aboutContent->about_section_heading ?? '' }}" maxlength="50" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_sub_heading">About Section Sub Heading</label>
                                <input type="text" class="form-control required" name="about_section_sub_heading" id="about_section_sub_heading" placeholder="Sub Heading" value="{{ $aboutContent->about_section_sub_heading ?? '' }}" maxlength="50" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_para">About Section Content</label>
                                <input type="text" class="form-control required" name="about_section_para" id="about_section_para" placeholder="Content" value="{{ $aboutContent->about_section_para ?? '' }}" maxlength="200" >
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

        $(document).on('change', '.upl-banner', function () {
            const file = this.files[0];
            $(this).parents(".input-group").next(".pic-errlbl").text(''); // Clear previous messages

            if (!file) return;

            const validTypes = ['image/png', 'image/jpeg'];

            if (!validTypes.includes(file.type)) {
                $(this).parents(".input-group").next(".pic-errlbl").text('Only PNG and JPG files are allowed.');
                $(this).val(''); // Clear the input
            }
        });
    </script>
@endsection
