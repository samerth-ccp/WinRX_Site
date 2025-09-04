@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  <a href="{{ route('backend.pages') }}" > Pages </a>  @endslot
@endcomponent
@php
   $lang =  app()->getLocale();
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" action="{{ route('backend.managepages',['pid'=>encrypt($pageData->page_id)]) }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="meta_title_en">Meta Title</label>
                                <input type="text" class="form-control required" name="meta_title_en" id="meta_title_en" placeholder="Title" value="{{ $pageData->meta_title_en }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="meta_keywords_en">Meta Keywords</label>
                                <input type="text" class="form-control required" name="meta_keywords_en" id="meta_keywords_en" placeholder="Keywords" value="{{ $pageData->meta_keywords_en }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="meta_desc_en">Meta Description</label>
                                <textarea rows="3" class="form-control required" name="meta_desc_en" id="meta_desc_en" maxlength="2000" >{{ $pageData->meta_desc_en }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="title_en ">Page Title</label>
                                <input type="text" class="form-control required" name="title_en" id="title_en" placeholder="Page Title" value="{{ $pageData->title_en }}" maxlength="100" >
                            </div>
                        </div>
                        @if($pageData->page_type=='static')
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="page_content">Page Content</label>
                                    <textarea rows="6" class="form-control required" name="page_content" id="page_content" >{{ $pageData->page_content }}</textarea>
                                </div>
                            </div>
                        @endif

                        @foreach($pageDataContents as $k=>$value)
                            @if ($value->page_content_type =='text')
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="{{ $value->page_content_key }}">{{ $value->page_content_title }}</label>
                                        <input type="text" class="form-control" name="{{ $value->page_content_key }}" id="{{ $value->page_content_key }}" placeholder="{{ $value->page_content_title }}" value="{{ $value->page_content_value }}" maxlength="{{ $value->max_limit }}" required>
                                    </div>
                                </div>
                            @elseif($value->page_content_type =='textarea')
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="{{ $value->page_content_key }}">{{ $value->page_content_title }}</label>
                                        <textarea rows="3" class="form-control" name="{{ $value->page_content_key }}" id="{{ $value->page_content_key }}" placeholder="{{ $value->page_content_title }}" maxlength="{{ $value->max_limit }}" required>{{ $value->page_content_value }}</textarea>
                                    </div>
                                </div>
                            @elseif($value->page_content_type =='file')
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="{{ $value->page_content_key }}">{{ $value->page_content_title }}</label>
                                            <div class="input-group"> 
                                            <input type="file" class="form-control {{ (empty($value->page_content_value))?'required':'' }}" id="{{ $value->page_content_key }}" name="{{ $value->page_content_key }}" id="{{ $value->page_content_key }}" autofocus>
                                            <label class="input-group-text" for="{{ $value->page_content_key }}">Upload</label>
                                        </div>
                                    </div>
                                    @if(!empty($value->page_content_value))
                                        <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;" src="{{ $value->page_content_value }}" data-holder-rendered="true">
                                    @endif
                                </div>
                            @elseif($value->page_content_type =='editor')
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="{{ $value->page_content_key }}">{{ $value->page_content_title }}</label>
                                        <textarea rows="6" class="form-control required" name="{{ $value->page_content_key }}" id="{{ $value->page_content_key }}" placeholder="{{ $value->page_content_title }}" maxlength="{{ $value->max_limit }}" required>{{ $value->page_content_value }}</textarea>
                                    </div>
                                </div>
                            @endif
                        @endforeach
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
            CKEDITOR.config.allowedContent = true;
		    CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
            @if($pageData->page_type=='static')
                CKEDITOR.replace('page_content',{
                    toolbar:[ 
                        [ 'Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates' ],
                        [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
                        [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ],
                        [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ],
                        [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ],
                        [ 'Link', 'Unlink', 'Anchor' ],
                        [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ],
                        [ 'Styles', 'Format', 'Font', 'FontSize' ], ['Youtube'],
                        [ 'TextColor', 'BGColor' ],
                        [ 'Maximize', 'ShowBlocks' ],
                    ],
                    height: 350
                });
            @endif
            @foreach($pageDataContents as $k=>$value)
                @if($value->page_content_type =='editor')
                    CKEDITOR.replace({{ $value->page_content_key }},{
                        toolbar:[ 
                            [ 'Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates' ],
                            [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
                            [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ],
                            [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ],
                            [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ],
                            [ 'Link', 'Unlink', 'Anchor' ],
                            [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ],
                            [ 'Styles', 'Format', 'Font', 'FontSize' ], ['Youtube'],
                            [ 'TextColor', 'BGColor' ],
                            [ 'Maximize', 'ShowBlocks' ],
                        ],
                        height: 350
                    });
                @endif
            @endforeach
            CKEDITOR.config.filebrowserUploadUrl = "{{route('backend.uploadmedia',['_token' => csrf_token() ])}}";
        });
    </script>
@endsection