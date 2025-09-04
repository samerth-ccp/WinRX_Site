@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  <a href="{{ route('backend.emailtemplate') }}" > Emails </a>  @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" action="{{ route('backend.managetemplate',['tid'=>encrypt($templateData->template_id)]) }}" class="needs-validation" id="template"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="template_title">Title</label>
                                <input type="text" class="form-control required" name="template_title" id="template_title" placeholder="Title" value="{{ $templateData->template_title }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="template_subject">Subject</label>
                                <input type="text" class="form-control required" name="template_subject" id="template_subject" placeholder="Title" value="{{ $templateData->template_subject }}" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="template_content">Content</label>
                                <textarea rows="6" class="form-control required" name="template_content" id="template_content" >{{ $templateData->template_content }}</textarea>
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
            $('#template').validate();
            CKEDITOR.config.allowedContent = true;
		    CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
			CKEDITOR.replace('template_content',{
                contentsCss : [''],
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
            CKEDITOR.config.filebrowserUploadUrl = "{{route('backend.uploadmedia',['_token' => csrf_token() ])}}";
        });
    </script>
@endsection