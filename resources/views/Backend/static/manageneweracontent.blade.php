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
    @slot('li_1')  <a href="{{ route('backend.aboutcontent') }}" > New ERA Content </a>  @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                    @if(!empty($neweraContentData->newera_section_content_id))
                    <form method="post" action="{{ route('backend.manageneweracontent',['pid'=>encrypt($neweraContentData->newera_section_content_id)]) }}" class="needs-validation" id="slider_section"  enctype='multipart/form-data' novalidate>
                    @else
                    <form method="post" action="{{ route('backend.manageneweracontent') }}" class="needs-validation" id="slider_section"  enctype='multipart/form-data' novalidate>
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="newera_section_content_title">Content Title</label>
                                <input type="text" class="form-control required" name="newera_section_content_title" id="newera_section_content_title" placeholder="Title" value="{{ $neweraContentData->newera_section_content_title ?? '' }}" maxlength="200" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="newera_section_content_tagline">Content Tagline</label>
                                <input type="text" class="form-control required" name="newera_section_content_tagline" id="newera_section_content_tagline" placeholder="Tagline" value="{{ $neweraContentData->newera_section_content_tagline ?? '' }}" maxlength="60" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="newera_section_content_background_image_label">Background Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($neweraContentData->newera_section_content_background_image))?'required':'' }}" id="newera_section_content_background_image" name="newera_section_content_background_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="newera_section_content_background_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($neweraContentData->newera_section_content_background_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$neweraContentData->newera_section_content_background_image }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="newera_section_content_image_label">Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($neweraContentData->newera_section_content_image))?'required':'' }}" id="newera_section_content_image" name="newera_section_content_image" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="newera_section_content_image">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($neweraContentData->newera_section_content_image))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$neweraContentData->newera_section_content_image }}" data-holder-rendered="true">
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

            const validTypes = ['image/png', 'image/jpeg'];

            if (!validTypes.includes(file.type)) {
                $(this).parents(".input-group").next(".pic-errlbl").text('Only PNG and JPG files are allowed.');
                $(this).val(''); // Clear the input
            }
        });
    </script>
@endsection
