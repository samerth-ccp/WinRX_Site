@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  Shop Page   @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" action="{{ route('backend.shopsmartsection') }}" class="needs-validation" id="smart_form"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_heading">Smart Section Heading</label>
                                <input type="text" class="form-control required" name="shop_smart_heading" id="shop_smart_heading" placeholder="Smart Section Heading" value="{{ $smartContent->shop_smart_heading ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_sub_heading">Smart Section Sub Heading</label>
                                <input type="text" class="form-control required" name="shop_smart_sub_heading" id="shop_smart_sub_heading" placeholder="Smart Section Sub Heading" value="{{ $smartContent->shop_smart_sub_heading ?? '' }}" maxlength="150" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_description">Smart Section Description</label>
                                <textarea id="shop_smart_description" name="shop_smart_description" class="form-control required" placeholder="Smart Section Description" maxlength="500">{{ $smartContent->shop_smart_description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_video_heading">Smart Section Video Heading</label>
                                <input type="text" class="form-control required" name="shop_smart_video_heading" id="shop_smart_video_heading" placeholder="Smart Section Video Heading" value="{{ $smartContent->shop_smart_video_heading ?? '' }}" maxlength="50" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_video_sub_heading">Smart Section Video Sub Heading</label>
                                <input type="text" class="form-control required" name="shop_smart_video_sub_heading" id="shop_smart_video_sub_heading" placeholder="Smart Section Video Sub Heading" value="{{ $smartContent->shop_smart_video_sub_heading ?? '' }}" maxlength="70" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_video_tagline">Smart Section Video Tagline</label>
                                <input type="text" class="form-control required" name="shop_smart_video_tagline" id="shop_smart_video_tagline" placeholder="Smart Section Video Tagline" value="{{ $smartContent->shop_smart_video_tagline ?? '' }}" maxlength="50" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_video_image_label">Smart Section Background Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($smartContent->shop_smart_video_image))?'required':'' }}" id="shop_smart_video_image" name="shop_smart_video_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="shop_smart_video_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($smartContent->shop_smart_video_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$smartContent->shop_smart_video_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_video_url">Smart Section Video Type</label><br/>
                                <div class="d-flex mt-2">
                                    <div class="form-check me-5">
                                        <input class="video-type form-check-input" id="shop_smart_video_type_link" type="radio" value="1" name="shop_smart_video_type" @if((!empty($smartContent->shop_smart_video_type) && $smartContent->shop_smart_video_type == '1') || empty($smartContent->shop_smart_video_type)) checked @endif />
                                        <label class="form-check-label" for="shop_smart_video_type_link">
                                            Enter YouTube Link
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="video-type form-check-input" type="radio" name="shop_smart_video_type" value="2" id="shop_smart_video_type_upload" @if((!empty($smartContent->shop_smart_video_type) && $smartContent->shop_smart_video_type == '2')) checked @endif />
                                        <label class="form-check-label" for="shop_smart_video_type_upload">
                                            Upload Video (.mp4 file extension allowed)
                                        </label>
                                    </div>
                                </div>

                                {{-- <input type="radio" class="video-type" id="shop_smart_video_type_link" name="shop_smart_video_type" value="1" @if((!empty($smartContent->shop_smart_video_type) && $smartContent->shop_smart_video_type == '1') || empty($smartContent->shop_smart_video_type)) checked @endif />  --}}
                                {{-- <input type="radio" class="video-type" id="shop_smart_video_type_upload" name="shop_smart_video_type" value="2" @if((!empty($smartContent->shop_smart_video_type) && $smartContent->shop_smart_video_type == '2')) checked @endif />  --}}
                            </div>
                        </div>
                        <div class="col-md-12 videolink-blk @if(!empty($smartContent->shop_smart_video_type) && $smartContent->shop_smart_video_type == '2') d-none @endif">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_video_url">Smart Section Video URL</label>
                                <input type="text" class="form-control url" name="shop_smart_video_url" id="shop_smart_video_url" placeholder="Smart Section Video URL" value="{{ $smartContent->shop_smart_video_url ?? '' }}" maxlength="600" >
                            </div>
                        </div>
                        <div class="col-md-12 videoupl-blk @if(!empty($smartContent->shop_smart_video_type) && $smartContent->shop_smart_video_type == '2') @else d-none @endif">
                            <div class="mb-3">
                                <label class="form-label" for="shop_smart_video_label">Upload Smart Section Video</label>
                                <div class="input-group">
                                    <input type="file" class="form-control uplvideo-banner" id="shop_smart_video" name="shop_smart_video" accept="video/mp4" autofocus>
                                    <label class="input-group-text" for="shop_smart_video">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($smartContent->shop_smart_video))
                                    <video width="200px" height="auto" src="{{ asset('assets/storage/homeimages/'.$smartContent->shop_smart_video) }}"></video>
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
            $('#smart_form').validate();
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

        $(document).on('change', '.uplvideo-banner', function () {
            const file = this.files[0];
            $(this).parents(".input-group").next(".pic-errlbl").text(''); // Clear previous messages

            if (!file) return;

            const validTypes = ['video/mp4'];

            if (!validTypes.includes(file.type)) {
                $(this).parents(".input-group").next(".pic-errlbl").text('Only MP4 files are allowed.');
                $(this).val(''); // Clear the input
            }
        });

        $(document).on("change",".video-type",function() {
            var video_type = $(this).val();
            if(video_type == '1') {
                $(".videoupl-blk").addClass("d-none");
                $("#shop_smart_video_url").addClass("required");
                $("#shop_smart_video").removeClass("required");
                $(".videolink-blk").removeClass("d-none");
            } else {
                $(".videolink-blk").addClass("d-none");
                $("#shop_smart_video").addClass("required");
                $("#shop_smart_video_url").removeClass("required");
                $(".videoupl-blk").removeClass("d-none");
            }
        })
    </script>
@endsection
