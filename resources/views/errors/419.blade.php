@extends('Frontend.layouts.master')
@section('css')
<style>
header, footer { display:none !important; }
#main { margin-left: 0 !important; }
</style>

@endsection

@section('content')
<div class="my-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <h1 class="display-1 fw-semibold">4<span class="text-primary mx-2">1</span>9</h1>
                    <h4 class="text-uppercase">Sorry, your session has expired. <br>Please refresh and try again</h4>
                    <div class="mt-5 text-center">
                        <a class="btn btn-primary waves-effect waves-light" href="{{ url('/') }}">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 col-xl-8">
                <div>
                    <img src="{{ asset('assets/images/error-img.png') }}" alt="" class="img-fluid">
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end content -->

@endsection

@section('script')
<!-- Include Js -->
<script type="text/javascript">
$(document).ready(function(){
    $('header').removeClass('d-flex');
});
</script>
@endsection

