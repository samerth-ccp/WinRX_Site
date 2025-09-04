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
    @slot('li_1')  <a href="{{ route('backend.shopcomplementcontent') }}" > Shop Complement Content </a>  @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                    @if(!empty($shopcomplementContentData->shop_complement_content_id))
                    <form method="post" action="{{ route('backend.manageshopcomplementcontent',['pid'=>encrypt($shopcomplementContentData->shop_complement_content_id)]) }}" class="needs-validation" id="complement_section"  enctype='multipart/form-data' novalidate>
                    @else
                    <form method="post" action="{{ route('backend.manageshopcomplementcontent') }}" class="needs-validation" id="complement_section"  enctype='multipart/form-data' novalidate>
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_content_title">Content Title</label>
                                <input type="text" class="form-control required" name="shop_complement_content_title" id="shop_complement_content_title" placeholder="Title" value="{{ $shopcomplementContentData->shop_complement_content_title ?? '' }}" maxlength="60" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_content_first_image_label">First Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($shopcomplementContentData->shop_complement_content_first_image))?'required':'' }}" id="shop_complement_content_first_image" name="shop_complement_content_first_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="shop_complement_content_first_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($shopcomplementContentData->shop_complement_content_first_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$shopcomplementContentData->shop_complement_content_first_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_content_second_image_label">Second Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($shopcomplementContentData->shop_complement_content_second_image))?'required':'' }}" id="shop_complement_content_second_image" name="shop_complement_content_second_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="shop_complement_content_second_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($shopcomplementContentData->shop_complement_content_second_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$shopcomplementContentData->shop_complement_content_second_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_content_third_image_label">Third Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner" id="shop_complement_content_third_image" name="shop_complement_content_third_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="shop_complement_content_third_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($shopcomplementContentData->shop_complement_content_third_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$shopcomplementContentData->shop_complement_content_third_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_content_fourth_image_label">Fourth Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner" id="shop_complement_content_fourth_image" name="shop_complement_content_fourth_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="shop_complement_content_fourth_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($shopcomplementContentData->shop_complement_content_fourth_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$shopcomplementContentData->shop_complement_content_fourth_image }}" data-holder-rendered="true">
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
            $('#complement_section').validate();
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

            const validTypes = ['image/png', 'image/jpeg'];

            if (!validTypes.includes(file.type)) {
                $(this).parents(".input-group").next(".pic-errlbl").text('Only PNG and JPG files are allowed.');
                $(this).val(''); // Clear the input
            }
        });
    </script>
@endsection
