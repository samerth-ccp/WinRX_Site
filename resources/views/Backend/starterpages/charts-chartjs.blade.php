@extends('Backend.layouts.master')

@section('title') @lang('Chartjs') @endsection

@section('content')

@component('Backend.components.breadcrumb')
@slot('li_1') Charts @endslot
@slot('title') Chartjs @endslot
@endcomponent

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Line Chart</h4>
            </div>
            <div class="card-body">

                <canvas id="lineChart" height="300" data-colors='["rgba(81, 86, 190, 0.2)", "#5156be", "rgba(235, 239, 242, 0.2)", "#ebeff2"]'></canvas>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Bar Chart</h4>
            </div>
            <div class="card-body">

                <canvas id="bar" height="300" data-colors='["rgba(41, 181, 125, 0.8)", "rgba(41, 181, 125, 0.9)"]'></canvas>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Pie Chart</h4>
            </div>
            <div class="card-body">

                <canvas id="pie" height="260" data-colors='["#2ab57d", "#ebeff2"]'></canvas>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Donut Chart</h4>
            </div>
            <div class="card-body">

                <canvas id="doughnut" height="260" data-colors='["#5156be", "#ebeff2"]'></canvas>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Polar Chart</h4>
            </div>
            <div class="card-body">

                <canvas id="polarArea" height="300" data-colors='["#fd625e", "#2ab57d", "#ffbf53", "#5156be"]'> </canvas>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Radar Chart</h4>
            </div>
            <div class="card-body">
                <canvas id="radar" height="300" data-colors='["rgba(42, 181, 125, 0.2)", "#2ab57d", "rgba(81, 86, 190, 0.2)", "#5156be"]'></canvas>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')
<!-- Chart JS -->
<script src="{{ URL::asset('/assets/libs/chart.js/Chart.bundle.min.js') }}"></script>
<!-- chartjs init -->
<script src="{{ URL::asset('/assets/js/pages/chartjs.init.js') }}"></script>

@endsection