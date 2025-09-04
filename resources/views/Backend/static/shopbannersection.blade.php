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
                <form method="post" action="{{ route('backend.shopbannersection') }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_banner_title">Banner Heading</label>
                                <input type="text" class="form-control required" name="shop_banner_title" id="shop_banner_title" placeholder="Banner Heading" value="{{ $bannerContent->shop_banner_title ?? '' }}" maxlength="80" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_banner_description">Banner Description</label>
                                <textarea id="shop_banner_description" name="shop_banner_description" class="form-control required" placeholder="Banner Description" maxlength="140">{{ $bannerContent->shop_banner_description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_banner_image_label">Accurate Section Background Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($bannerContent->shop_banner_image))?'required':'' }}" id="shop_banner_image" name="shop_banner_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="shop_banner_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($bannerContent->shop_banner_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$bannerContent->shop_banner_image }}" data-holder-rendered="true">
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
