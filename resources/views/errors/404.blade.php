@extends('Frontend.layouts.master')
@section('title')
    @lang('Error_404')
@endsection

@section('css')
<!-- Include css -->
<style>
header, footer{display: none!important;}
</style>
@endsection

@section('content')

    <div class="errorpage">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error_content_block">
                        <h1 class="display-1 fw-semibold">4<span class="mx-2"> <img class="ring-flip" src="{{asset('assets/images/banner_ring.png')}}" alt="rign" /> </span>4</h1>
                        <h4 class="text-uppercase">Sorry, page not found </h4>
                        <div class="mt-5 text-center">
                            <a class="btn common_btn" href="{{ route('frontend.index.index') }}">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<!-- Include Js -->
<script type="text/javascript">
    $(document).ready(function(){
        $('header').removeClass('d-flex');
    });
</script>
@endsection