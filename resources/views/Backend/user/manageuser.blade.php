@extends('Backend.layouts.master')

@section('css')
<style> .error{color:red;} </style>
<link href="https://releases.transloadit.com/uppy/v2.7.0/uppy.min.css" rel="stylesheet"/>
@endsection
@section('content')

@component('Backend.components.breadcrumb')
    @slot('li_1') <a href="{{ route('backend.users') }}" >Users List<a> @endslot
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
@endcomponent
@php $lang =  app()->getLocale(); @endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" id="manageUser" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="meta_title_en">First Name</label>
                                <input type="text" class="form-control required" name="meta_title_en" id="meta_title_en" placeholder="First Name" value="" maxlength="100" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="meta_keywords_en">Last Name</label>
                                <input type="text" class="form-control required" name="meta_keywords_en" id="meta_keywords_en" placeholder="Last Name" value="" maxlength="100" >
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="file_upload">Last Name</label>
                                <input type="file" class="form-control" name="file_upload" id="file_upload">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="file_upload">Last Name</label>
                                <div id="drag-drop-area"></div>
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
<script src="https://releases.transloadit.com/uppy/v2.7.0/uppy.min.js"></script>

<script>
    var uppy = new Uppy.Core()
        .use(Uppy.Dashboard, {
            inline: true,
            target: '#drag-drop-area'
        })
        .use(Uppy.GoogleDrive, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
        .use(Uppy.Dropbox, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
        .use(Uppy.Box, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
        .use(Uppy.Instagram, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
        .use(Uppy.Facebook, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
        .use(Uppy.OneDrive, { target:Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
        .use(Uppy.Unsplash, { target: Uppy.Dashboard, companionUrl: 'https://companion.uppy.io' })
        .use(Uppy.Webcam, { target: Uppy.Dashboard })
        .use(Uppy.Audio, { target: Uppy.Dashboard })
        .use(Uppy.ImageEditor, { target: Uppy.Dashboard })
        .use(Uppy.Tus, { endpoint: 'https://tusd.tusdemo.net/files/' })
        .use(Uppy.DropTarget, {target: document.body })
        .use(Uppy.GoldenRetriever)


    uppy.on('complete', (result) => {
      console.log('Upload complete! We’ve uploaded these files:', result.successful)
    })
</script>

<script>
    $(document).ready(function(){
        $('#manageUser').validate();
    });
</script>
@endsection