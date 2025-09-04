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
    @slot('li_1')  <a href="{{ route('backend.shoptechsection') }}" > Shop Tech Section </a>  @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                    @if(!empty($shoptechData->shop_tech_section_id))
                    <form method="post" action="{{ route('backend.manageshoptechsection',['pid'=>encrypt($shoptechData->shop_tech_section_id)]) }}" class="needs-validation" id="tech_section"  enctype='multipart/form-data' novalidate>
                    @else
                    <form method="post" action="{{ route('backend.manageshoptechsection') }}" class="needs-validation" id="tech_section"  enctype='multipart/form-data' novalidate>
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_tech_section_title">Tech Section Title</label>
                                <input type="text" class="form-control required" name="shop_tech_section_title" id="shop_tech_section_title" placeholder="Title" value="{{ $shoptechData->shop_tech_section_title ?? '' }}" maxlength="30" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_tech_section_description">Tech Section Description</label>
                                <textarea rows="3" class="form-control required" name="shop_tech_section_description" id="shop_tech_section_description" placeholder="Description" maxlength="150">{{ $shoptechData->shop_tech_section_description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="shop_tech_section_image_label">Tech Section Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($shoptechData->shop_tech_section_image))?'required':'' }}" id="shop_tech_section_image" name="shop_tech_section_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="shop_tech_section_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($shoptechData->shop_tech_section_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$shoptechData->shop_tech_section_image }}" data-holder-rendered="true">
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
            $('#tech_section').validate();
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
