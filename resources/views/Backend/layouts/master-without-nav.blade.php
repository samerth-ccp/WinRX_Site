@php
    $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
    $configData = array_column($configData,'config_value','config_key');

    Session::put('ConfigData', $configData);
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8" />
        <title> @yield('title') | {{ Session::get('ConfigData')['meta_title'] }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="{{ Session::get('ConfigData')['meta_keywords'] }}" name="keyword" />
        <meta content="{{ Session::get('ConfigData')['meta_description'] }}" name="description" />
        <meta content="{{ Session::get('ConfigData')['meta_title'] }}" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_fav_icon'] }}">
        <script>
            var siteUrl = "{{ config('app.url') }}";
            var ADMIN_APPURL = "{{ config('app.admin_url') }}";
        </script>

        @include('Backend.layouts.head-css')


  </head>

    <body>
        @yield('content')

        @include('Backend.layouts.vendor-scripts')
    </body>
</html>
