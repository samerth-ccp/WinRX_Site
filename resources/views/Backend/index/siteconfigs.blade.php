@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('li_1') Configs @endslot
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
@endcomponent

<div class="site_config_page">
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('backend.updatesiteconfigs',['key'=>$key]) }}" class="needs-validation" id="siteConfigs"  enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                    @foreach ($configData as $k=>$value)
                        @if ($value->config_type =='text')
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="{{ $value->config_key }}">{{ $value->config_name }}</label>
                                    <input type="text" class="form-control" name="{{ $value->config_key }}" id="{{ $value->config_key }}" placeholder="{{ $value->config_name }}" value="{{ $value->config_value }}" maxlength="{{ $value->config_max_length }}"  @if($value->config_key != 'facebook_url' && $value->config_key != 'twitter_url' && $value->config_key != 'instagram_url' && $value->config_key != 'youtube_url') required @endif>
                                </div>
                            </div>
                        @elseif($value->config_type =='textarea')
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="{{ $value->config_key }}">{{ $value->config_name }}</label>
                                    <textarea rows="3" class="form-control" name="{{ $value->config_key }}" id="{{ $value->config_key }}" placeholder="{{ $value->config_name }}" maxlength="{{ $value->config_max_length }}" required>{{ $value->config_value }}</textarea>
                                </div>
                            </div>
                        @elseif($value->config_type =='file')
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="{{ $value->config_key }}">{{ $value->config_name }}</label>
                                        <div class="input-group">
                                        <input type="file" class="form-control {{ (empty($value->config_value))?'required':'' }}" id="{{ $value->config_key }}" name="{{ $value->config_key }}" id="{{ $value->config_key }}" autofocus>
                                        <label class="input-group-text" for="{{ $value->config_key }}">Upload</label>
                                    </div>
                                </div>
                                @if(!empty($value->config_value))
                                    <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;background:#d0d0d0" src="{{ asset('assets/storage/logo/').'/'.$value->config_value }}" data-holder-rendered="true">
                                @endif
                            </div>
                        @elseif($value->config_type =='editor')
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="{{ $value->config_key }}">{{ $value->config_name }}</label>
                                    <textarea rows="3" class="form-control" name="{{ $value->config_key }}" id="{{ $value->config_key }}" placeholder="{{ $value->config_name }}" maxlength="{{ $value->config_max_length }}" required>{{ $value->config_value }}</textarea>
                                </div>
                            </div>
                        @endif

                    @endforeach
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $('#siteConfigs').validate();
        })
    </script>
@endsection
