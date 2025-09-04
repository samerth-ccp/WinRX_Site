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
    @slot('li_1')  <a href="{{ route('backend.aboutcontent') }}" > About Content </a>  @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                    @if(!empty($aboutContentData->about_section_content_id))
                    <form method="post" action="{{ route('backend.manageaboutcontent',['pid'=>encrypt($aboutContentData->about_section_content_id)]) }}" class="needs-validation" id="slider_section"  enctype='multipart/form-data' novalidate>
                    @else
                    <form method="post" action="{{ route('backend.manageaboutcontent') }}" class="needs-validation" id="slider_section"  enctype='multipart/form-data' novalidate>
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_content_title">Content Title</label>
                                <input type="text" class="form-control required" name="about_section_content_title" id="about_section_content_title" placeholder="Title" value="{{ $aboutContentData->about_section_content_title ?? '' }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_content_img1_label">First Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($aboutContentData->about_section_content_img1))?'required':'' }}" id="about_section_content_img1" name="about_section_content_img1" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="about_section_content_img1">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($aboutContentData->about_section_content_img1))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$aboutContentData->about_section_content_img1 }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_content_img2_label">Second Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner {{ (empty($aboutContentData->about_section_content_img2))?'required':'' }}" id="about_section_content_img2" name="about_section_content_img2" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="about_section_content_img2">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($aboutContentData->about_section_content_img2))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$aboutContentData->about_section_content_img2 }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_content_img3_label">Third Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner" id="about_section_content_img3" name="about_section_content_img3" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="about_section_content_img3">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($aboutContentData->about_section_content_img3))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$aboutContentData->about_section_content_img3 }}" data-holder-rendered="true">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="about_section_content_img3_label">Fourth Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control upl-banner" id="about_section_content_img4" name="about_section_content_img4" accept="image/png,image/jpeg" autofocus>
                                    <label class="input-group-text" for="about_section_content_img4">Upload</label>
                                </div>
                                <p id="banner_imgmessage" class="pic-errlbl" style="color: red;"></p>
                                @if(!empty($aboutContentData->about_section_content_img4))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/homeimages/').'/'.$aboutContentData->about_section_content_img4 }}" data-holder-rendered="true">
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
