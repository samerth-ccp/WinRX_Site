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
                <form method="post" action="{{ route('backend.bannersection') }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner_first_heading">Banner First Heading</label>
                                <input type="text" class="form-control required" name="banner_first_heading" id="banner_first_heading" placeholder="First Heading" value="{{ $bannerData->banner_first_heading ?? '' }}" maxlength="30" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner_second_heading">Banner Second Heading</label>
                                <input type="text" class="form-control required" name="banner_second_heading" id="banner_second_heading" placeholder="Second Heading" value="{{ $bannerData->banner_second_heading ?? '' }}" maxlength="30" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner_image_label">Banner Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($bannerData->banner_image))?'required':'' }}" id="banner_image" name="banner_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="banner_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($bannerData->banner_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:120px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$bannerData->banner_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner_background_image_label">Banner Background Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($bannerData->banner_background_image))?'required':'' }}" id="banner_background_image" name="banner_background_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="banner_background_image">Upload</label>
                                </div>
                                <p id="banner_bgimgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($bannerData->banner_background_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:120px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$bannerData->banner_background_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="banner_para">Banner Bottom Text</label>
                                <input type="text" class="form-control required" name="banner_para" id="banner_para" placeholder="Banner Bottom Text" value="{{ $bannerData->banner_para ?? '' }}" maxlength="255" >
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
