@yield('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css"/>
<!-- Icons Css -->
<link href="{{ URL::asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- alertifyjs Css -->
<link href="{{ URL::asset('/assets/libs/alertifyjs/build/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />
<!-- alertifyjs default themes  Css -->
<link href="{{ URL::asset('/assets/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Sweet Alert-->
<link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

@yield('endCss')

<!-- App Css-->
<link href="{{ URL::asset('/assets/frontcss/slick.css') }}"  rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/frontcss/aos.css') }}"  rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/frontcss/style.css') }}" id="app-style" rel="stylesheet" type="text/css"/>



