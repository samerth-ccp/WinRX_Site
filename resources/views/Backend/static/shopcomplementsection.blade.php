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
                <form method="post" action="{{ route('backend.shopcomplementsection') }}" class="needs-validation" id="pages"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_section_heading">Complement Section Heading</label>
                                <input type="text" class="form-control required" name="shop_complement_section_heading" id="shop_complement_section_heading" placeholder="Complement Section Heading" value="{{ $complementContent->shop_complement_section_heading ?? '' }}" maxlength="30" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_section_sub_heading">Complement Section Sub Heading</label>
                                <input type="text" class="form-control required" name="shop_complement_section_sub_heading" id="shop_complement_section_sub_heading" placeholder="Complement Section Sub Heading" value="{{ $complementContent->shop_complement_section_sub_heading ?? '' }}" maxlength="50" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="shop_complement_section_description">Complement Section Description</label>
                                <textarea id="shop_complement_section_description" name="shop_complement_section_description" class="form-control required" placeholder="Complement Section Description" maxlength="400">{{ $complementContent->shop_complement_section_description ?? '' }}</textarea>
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
    </script>
@endsection
