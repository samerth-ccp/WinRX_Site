@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('li_1') <a href="{{ route('backend.adminprofile') }}">Admin</a> @endslot
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" action="{{ route('backend.resetpassword') }}" class="needs-validation" id="resetpassword"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="current_password">Current Password</label>
                                <input type="password" class="form-control required" name="current_password" id="current_password" placeholder="Current Password" value="" maxlength="30" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="new_password">New Password</label>
                                <input type="password" class="form-control passcheck required" name="new_password" id="new_password" placeholder="New Password" value="" maxlength="30" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control required" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="" maxlength="30" >
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $('#resetpassword').validate({
                rules:{
                    'confirm_password':{required:true,equalTo:'#new_password'},
                }
            });
        });
    </script>
@endsection