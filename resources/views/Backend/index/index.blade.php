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

                   chart.updateSeries([{
                       name: 'Users',
                       data: response.last_seven_users
                     }]),

                   chart.updateOptions({
                       xaxis: {
                       categories:response.last_seven_dates
                       }
                     }, false, true)
                   //$('#total_earning').html(response.total_earning);
               }

           }
           });
    });

    function loadChart()
	{
	! function() {
    function e(e) {
        return e = $(e).attr("data-colors"), (e = JSON.parse(e)).map((function(e) {
            return -1 == (e = e.replace(" ", "")).indexOf("--") ? e : (e = getComputedStyle(document.documentElement).getPropertyValue(e)) || void 0
        }))
    }
			 t = {
        chart: {
            height: 350,
            type: "bar",
            toolbar: {
                show: !1
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                columnWidth: "45%"
            }
        },
        dataLabels: {
            enabled: !1
        },
        stroke: {
            show: !0,
            width: 2,
            colors: ["transparent"]
        },
        series: [{
            name: "Users",
            data: {!!json_encode($last_seven_users)!!}
        }],
        colors: e("#column_chart1"),
        xaxis: {
            categories: {!!json_encode($last_seven_dates)!!}
        },
        yaxis: {
            title: {
                text: "Users",
                style: {
                    fontWeight: "500"
                }
            },
            labels: {
                "formatter": function (val) {
                    //alert(val);
                    return val.toFixed();
                },
            }
        },
        grid: {
            borderColor: "#f1f1f1"
        },
        fill: {
            opacity: 1
        },
        tooltip: {
			y: {
                formatter: function(e) {
                    return e
                }
            },

        }
    };
    (chart = new ApexCharts(document.querySelector("#column_chart1"), t)).render();
			}();
	  }

	  $(document).ready(function(){
		  loadChart();
	  })
        </script>

        <!-- Plugins js-->
        <script src="{{ URL::asset('/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ URL::asset('/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>

    <!-- dashboard init -->
    <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
@endsection
