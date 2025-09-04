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
                <form method="post" action="{{ route('backend.newerasection') }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="newera_section_heading">New ERA Section Heading</label>
                                <input type="text" class="form-control required" name="newera_section_heading" id="newera_section_heading" placeholder="Section Heading" value="{{ $neweraContent->newera_section_heading ?? '' }}" maxlength="40" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="newera_section_subheading">New ERA Section Sub Heading</label>
                                <input type="text" class="form-control required" name="newera_section_subheading" id="newera_section_subheading" placeholder="Section Sub Heading" value="{{ $neweraContent->newera_section_subheading ?? '' }}" maxlength="70" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="newera_section_para">New ERA Section Sub Heading</label>
                                <input type="text" class="form-control required" name="newera_section_para" id="newera_section_para" placeholder="Section Para" value="{{ $neweraContent->newera_section_para ?? '' }}" maxlength="255" >
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
