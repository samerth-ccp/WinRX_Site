@extends('Backend.layouts.master')

@section('title') @lang('Starter_Page') @endsection

@section('content')

@component('Backend.components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Starter Page @endslot
@endcomponent

@endsection