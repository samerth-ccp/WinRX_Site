<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title> {{ Session::get('PageHeading'); }} | {{ Session::get('ConfigData')['meta_title'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ Session::get('ConfigData')['meta_keywords'] }}" name="keyword" />
    <meta content="{{ Session::get('ConfigData')['meta_description'] }}" name="description" />
    <meta content="{{ Session::get('ConfigData')['meta_title'] }}" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_fav_icon'] }}">
    <script>
        var APP_URL = "{{ config('app.url') }}";
        var ADMIN_APPURL = "{{ config('app.admin_url') }}";
    </script>
    @include('Backend.layouts.head-css')
</head>
@php

    $AdminData = DB::table('admins')->where('id',auth()->guard('admin')->id())->first();
    Session::put('AdminData', $AdminData);
    if(!empty(Session::get('AdminData')->admin_theme)){
        $admin_theme = Session::get('AdminData')->admin_theme;
    }else{
        $admin_theme ='dark';
    }
    if(!empty(Session::get('AdminData')->admin_sidebar_size)){
         $navmode = Session::get('AdminData')->admin_sidebar_size;
    }else{
        $navmode = 'LG';
    }

    //$mode  = (!empty($_COOKIE['siteMode']))?$_COOKIE['siteMode']:'light';
    //$navmode  = (!empty($_COOKIE['navMode']))?$_COOKIE['navMode']:'';
@endphp
@section('body')
    {{-- <body class="" data-layout-mode="{{ $mode }}" data-topbar="{{ $mode }}" data-sidebar="{{ $mode }}" data-sidebar-size="{{ $navmode }}"> --}}
        <body class="" @if($admin_theme=='dark') data-layout-mode="dark" bgcolor="#495057" data-topbar="dark" data-sidebar="dark" @endif data-sidebar-size="{{ $navmode }}">
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('Backend.layouts.topbar')
        @include('Backend.layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('Backend.layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('Backend.layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- JAVASCRIPT -->
    @include('Backend.layouts.vendor-scripts')
</body>

</html>
