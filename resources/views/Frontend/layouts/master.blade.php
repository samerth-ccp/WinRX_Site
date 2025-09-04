@php $configData = Session::get('ConfigData'); @endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title> {{ !empty($pageMetaTitle)?$pageMetaTitle:$configData['meta_title'] }} | {{  $configData['site_name'] }} </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta content="{{ !empty($pageMetaKeyword)?$pageMetaKeyword:$configData['meta_keywords'] }}" name="keyword" />
    <meta content="{{ !empty($pageMetaDescription)?$pageMetaDescription:$configData['meta_description'] }}" name="description" />
    
    <link rel="manifest" href="{{ asset('/assets/frontjs/manifest.json') }}">
    <link rel="canonical" href="{{url()->current()}}" /> 
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAV ICON -->
	<link rel="shortcut icon" href="{{ asset('assets/storage/logo/').'/'.$configData['site_fav_icon'] }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <script>
        var APP_URL = "{{ config('app.url') }}";
        var captchaUrl = "https://www.google.com/recaptcha/api.js?render={{ Session::get('ConfigData')['recaptcha_site_key'] }}";
    </script>

    @include('Frontend.layouts.head-css')
    
    @if(checkIos())
        <link href="{{ URL::asset('/assets/frontcss/ios-design.css?time=') }}" rel="stylesheet"/>
    @endif
</head>

<body class="@if(checkIos()) iosload @endif">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            @include('Frontend.layouts.header')
            <div class="page-content">
                @yield('content')
            </div>
            <!-- End Page-content -->
            @include('Frontend.layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    @include('Frontend.layouts.vendor-scripts')
</body>

<script>
    if ('serviceWorker' in navigator) {
       console.log("Will the service worker register?");
       navigator.serviceWorker.register('service-worker.js')
         .then(function(reg){
           console.log("Yes, it did.");
        }).catch(function(err) {
           console.log("No it didn't. This happened:", err)
       });
    }
</script>

</html>
