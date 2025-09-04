@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
.time-holders{display: flex;}
.time-slots{max-width: 75px;}
.time-holders label{padding-top: 9px;padding-left: 5px;padding-right: 5px;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  <a href="{{ route('backend.slidersection') }}" > Slider Section </a>  @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                    @if(!empty($sliderData->slider_section_id))
                    <form method="post" action="{{ route('backend.manageslidersection',['pid'=>encrypt($sliderData->slider_section_id)]) }}" class="needs-validation" id="slider_section"  enctype='multipart/form-data' novalidate>
                    @else
                    <form method="post" action="{{ route('backend.manageslidersection') }}" class="needs-validation" id="slider_section"  enctype='multipart/form-data' novalidate>
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_heading">Slider Section Heading</label>
                                <input type="text" class="form-control required" name="slider_section_heading" id="slider_section_heading" placeholder="Heading" value="{{ $sliderData->slider_section_heading ?? '' }}" maxlength="40" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_sub_heading">Slider Section Sub Heading</label>
                                <input type="text" class="form-control required" name="slider_section_sub_heading" id="slider_section_sub_heading" placeholder="Sub Heading" value="{{ $sliderData->slider_section_sub_heading ?? '' }}" maxlength="60" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_tagline">Slider Section Tagline</label>
                                <input type="text" class="form-control required" name="slider_section_tagline" id="slider_section_tagline" placeholder="Tagline" value="{{ $sliderData->slider_section_tagline ?? '' }}" maxlength="40" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_tagline_image_label">Slider Section Tagline Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($sliderData->slider_section_tagline_image))?'required':'' }}" id="slider_section_tagline_image" name="slider_section_tagline_image" accept="image/png,image/jpeg,image/svg+xml" autofocus>
                                    <label class="input-group-text" for="slider_section_tagline_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($sliderData->slider_section_tagline_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$sliderData->slider_section_tagline_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_para">Slider Section Description</label>
                                <textarea rows="3" class="form-control required" name="slider_section_para" id="slider_section_para" maxlength="255" >{{ $sliderData->slider_section_para ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_background_image_label">Slider Section Background Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($sliderData->slider_section_background_image))?'required':'' }}" id="slider_section_background_image" name="slider_section_background_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="slider_section_background_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($sliderData->slider_section_background_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$sliderData->slider_section_background_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_reviewer_name">Reviewer Name</label>
                                <input type="text" class="form-control" name="slider_section_reviewer_name" id="slider_section_reviewer_name" placeholder="Reviewer Name" value="{{ $sliderData->slider_section_reviewer_name ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_reviewer_info">Reviewer Info</label>
                                <input type="text" class="form-control" name="slider_section_reviewer_info" id="slider_section_reviewer_info" placeholder="Reviewer Info" value="{{ $sliderData->slider_section_reviewer_info ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_review">Provided Review</label>
                                <textarea rows="2" class="form-control" name="slider_section_review" id="slider_section_review" placeholder="Provided Review" maxlength="200" >{{ $sliderData->slider_section_review ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_reviewer_image_label">Slider Section Reviewer Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner" id="slider_section_reviewer_image" name="slider_section_reviewer_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="slider_section_reviewer_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($sliderData->slider_section_reviewer_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$sliderData->slider_section_reviewer_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="slider_section_image_label">Slider Section Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner" id="slider_section_image" name="slider_section_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="slider_section_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($sliderData->slider_section_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$sliderData->slider_section_image }}" data-holder-rendered="true">
                                @endif
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
            $('#slider_section').validate();
            /*CKEDITOR.config.allowedContent = true;
		    CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
            CKEDITOR.replace('slider_section_para',{
                    toolbar:[
                        [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
                        [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ],
                        [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ],
                        [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],
                        [ 'Styles', 'Format', 'Font', 'FontSize' ]
                    ],
                    height: 350
                });*/
        });

        $(document).on('change', '.upl-banner', function () {
            const file = this.files[0];
            $(this).parents(".input-group").next(".pic-errlbl").text(''); // Clear previous messages

            if (!file) return;
            const validTypes = ['image/png', 'image/jpeg', 'image/svg+xml'];

            if (!validTypes.includes(file.type)) {
                $(this).parents(".input-group").next(".pic-errlbl").text('Only PNG and JPG files are allowed.');
                $(this).val(''); // Clear the input
            }
        });
    </script>
@endsection
