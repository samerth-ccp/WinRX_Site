@extends('Backend.layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
<style>
    .clr-field {
        display: block;
        position: relative;
    }
    .clr-field button {
        width: 160px !important;
        height: 20px !important;
        right: 10px !important;
        border-radius: 5px;
        padding-right: 20px;
    }
</style>
@endsection

@section('content')

@component('Backend.components.breadcrumb')
    @slot('li_1') <a href="{{ route('colors') }}" >Color List<a> @endslot
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" class="needs-validation" id="manage_form"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="color_name">Color Name</label>
                                <input type="text" class="form-control required" name="color_name" id="color_name" placeholder="Color Name" value="{{$color->color_name??''}}" maxlength="100" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="color_code">Color Code</label>
                                <input type="text" class="form-control required" data-coloris name="color_code" style="height: 36px;" id="color_code" placeholder="Color Code" value="{{$color->color_code??''}}" maxlength="100" >
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

<script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#manage_form').validate();
        });
    </script>
@endsection