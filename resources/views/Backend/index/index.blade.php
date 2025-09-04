@extends('Backend.layouts.master')

@section('title') @lang('Dashboard') @endsection

@section('css')

<!-- plugin css -->
<link href="{{ URL::asset('/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
<style>
.apexcharts-xaxis text, .apexcharts-yaxis text
{
    fill: #000 !important;
}
</style>
@endsection

@section('content')

    @component('Backend.components.breadcrumb')
        @slot('li_1') Dashboard @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <div class="row dashboard-page">
        <h1 class="text-center dashtext">Welcome to Dashboard!</h1>
    </div>

@endsection

@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<script>
    $('#chart_type').change(function(){

     $.ajax({
           url     : '{{route('backend.getchart')}}',
           type    : "GET",
           dataType:'json',
           data: {_token:$('meta[name="csrf-token"]').attr('content'),type:$('#chart_type').val()},
           success:function(response){
               //alert(response);
               if(response){

                   //$('#total_earning').html(response.total_earning);
               }

           }
           });
    });



	  $(document).ready(function(){
		  //loadChart();
	  })
        </script>

        <!-- Plugins js-->
        <script src="{{ URL::asset('/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ URL::asset('/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>

    <!-- dashboard init -->
    <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
@endsection
