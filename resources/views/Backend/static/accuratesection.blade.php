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
                <form method="post" action="{{ route('backend.accuratesection') }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_heading">Accurate Section Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_heading" id="accurate_section_heading" placeholder="Section Heading" value="{{ $accurateContent->accurate_section_heading ?? '' }}" maxlength="15" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_sub_heading">Accurate Section Sub Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_sub_heading" id="accurate_section_sub_heading" placeholder="Section Sub Heading" value="{{ $accurateContent->accurate_section_sub_heading ?? '' }}" maxlength="30" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_para">Accurate Section Para</label>
                                <textarea id="accurate_section_para" name="accurate_section_para" class="form-control required" placeholder="Section Para" maxlength="100">{{ $accurateContent->accurate_section_para ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_background_image_label">Accurate Section Background Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($accurateContent->accurate_section_background_image))?'required':'' }}" id="accurate_section_background_image" name="accurate_section_background_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="accurate_section_background_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($accurateContent->accurate_section_background_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$accurateContent->accurate_section_background_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_first_heading">Accurate Section Block First Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_first_heading" id="accurate_section_first_heading" placeholder="Section First Heading" value="{{ $accurateContent->accurate_section_first_heading ?? '' }}" maxlength="10" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_first_sub_heading">Accurate Section Block First Sub Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_first_sub_heading" id="accurate_section_first_sub_heading" placeholder="Section First Sub Heading" value="{{ $accurateContent->accurate_section_first_sub_heading ?? '' }}" maxlength="32" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_first_para">Accurate Section Block First Para</label>
                                <input type="text" class="form-control required" name="accurate_section_first_para" id="accurate_section_first_para" placeholder="Section First Para" value="{{ $accurateContent->accurate_section_first_para ?? '' }}" maxlength="200" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_second_heading">Accurate Section Block Second Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_second_heading" id="accurate_section_second_heading" placeholder="Section First Heading" value="{{ $accurateContent->accurate_section_second_heading ?? '' }}" maxlength="10" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_second_sub_heading">Accurate Section Block Second Sub Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_second_sub_heading" id="accurate_section_second_sub_heading" placeholder="Section Second Sub Heading" value="{{ $accurateContent->accurate_section_second_sub_heading ?? '' }}" maxlength="32" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_second_para">Accurate Section Block Second Para</label>
                                <input type="text" class="form-control required" name="accurate_section_second_para" id="accurate_section_second_para" placeholder="Section Second Para" value="{{ $accurateContent->accurate_section_second_para ?? '' }}" maxlength="200" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_third_heading">Accurate Section Block Third Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_third_heading" id="accurate_section_third_heading" placeholder="Section Third Heading" value="{{ $accurateContent->accurate_section_third_heading ?? '' }}" maxlength="10" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_third_sub_heading">Accurate Section Block Third Sub Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_third_sub_heading" id="accurate_section_third_sub_heading" placeholder="Section Third Sub Heading" value="{{ $accurateContent->accurate_section_third_sub_heading ?? '' }}" maxlength="32" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_third_para">Accurate Section Block Third Para</label>
                                <input type="text" class="form-control required" name="accurate_section_third_para" id="accurate_section_third_para" placeholder="Section Third Para" value="{{ $accurateContent->accurate_section_third_para ?? '' }}" maxlength="200" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_fourth_heading">Accurate Section Block Fourth Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_fourth_heading" id="accurate_section_fourth_heading" placeholder="Section Fourth Heading" value="{{ $accurateContent->accurate_section_fourth_heading ?? '' }}" maxlength="10" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_fourth_sub_heading">Accurate Section Block Fourth Sub Heading</label>
                                <input type="text" class="form-control required" name="accurate_section_fourth_sub_heading" id="accurate_section_fourth_sub_heading" placeholder="Section Fourth Sub Heading" value="{{ $accurateContent->accurate_section_fourth_sub_heading ?? '' }}" maxlength="32" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="accurate_section_fourth_para">Accurate Section Block Fourth Para</label>
                                <input type="text" class="form-control required" name="accurate_section_fourth_para" id="accurate_section_fourth_para" placeholder="Section Fourth Para" value="{{ $accurateContent->accurate_section_fourth_para ?? '' }}" maxlength="200" >
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
