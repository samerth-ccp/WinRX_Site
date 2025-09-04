@extends('Backend.layouts.master')

@section('css')
<style>
.error{color:red;}
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('li_1') Admin @endslot
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" action="{{ route('backend.adminprofile') }}" class="needs-validation" id="profile"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            @if(!empty(Session::get('AdminData')->profile_image))
                                <img class="img-thumbnail mb-3" alt="200x200" width="auto" style="max-width:200px;" src="{{ asset('assets/storage/avtar/').'/'.Session::get('AdminData')->profile_image }}" data-holder-rendered="true">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="profile_image">Image</label>
                                    <div class="input-group"> 
                                    <input type="file" class="form-control" id="profile_image" name="profile_image"  autofocus>
                                    <label class="input-group-text" for="profile_image">Upload</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control required" name="name" id="name" placeholder="Name" value="{{ Session::get('AdminData')->name }}" maxlength="25" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">Email</label>
                                <input type="text" class="form-control required" name="email" id="email" placeholder="Email" value="{{ Session::get('AdminData')->email }}" maxlength="100" >
                            </div>
                            <div class="mb-3 changePassword" style="display:none;">
                                <label class="form-label" for="password">Current Password</label>
                                <input type="password" class="form-control required" name="password" id="password" placeholder="Password" value="" maxlength="30" >
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
            $('#profile').validate();
        });
        
        
        $(document).on('keyup','#email',function(){
            var current_email = '{{ Session::get('AdminData')->email }}';
            var change_email = $(this).val();
            
            if(current_email.toLowerCase() !== change_email.toLowerCase()){
                $('.changePassword').show();
            }else{
                $('.changePassword').hide(); 
                
            }
        });

        $('#profile').on('submit', function(e) {
            if (!$(this).valid()) {
                e.preventDefault();
                return;
            }
            const emailInput = document.getElementById('email');
            emailInput.value = emailInput.value.trim().toLowerCase();
        });

    </script>
@endsection