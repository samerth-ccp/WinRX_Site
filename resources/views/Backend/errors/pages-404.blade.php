@extends('Backend.layouts.master-without-nav')

@section('title')
@lang('Error_404')
@endsection

@section('body')

<body>
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
                            <a class="btn common_btn" href="{{ route('backend.dashboard')}}">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row justify-content-center">
                <div class="col-md-10 col-xl-8">
                    <div>
                        <img src="{{ asset('/assets/images/error-img.png') }} " alt="" class="img-fluid">
                    </div>
                </div>
            </div> --}}
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end content -->

    @endsection