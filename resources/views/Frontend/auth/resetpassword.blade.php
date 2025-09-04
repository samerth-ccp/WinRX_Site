@extends('Frontend.layouts.master')

@section('css')
<!-- Include Css-->
@endsection

@section('content')
    <div class="container">
      <form class="form-signin" method="post" id="form">
        <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
        @csrf
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control passcheck required" placeholder="Password" >

        <label for="confirm_password" class="sr-only">Password</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control required" placeholder="Confirm Password" >

        <div class="checkbox mb-3">
          <label>

          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block submit" type="button">Submit</button>
        <p class="mt-5 mb-3 text-muted"></p>
      </form>
    </div>
@endsection

@section('script')
<!-- Include Js-->
<script src="https://www.google.com/recaptcha/api.js?render={{ Session::get('ConfigData')['recaptcha_site_key'] }}"></script>
<script>
  $(document).ready(function(){
    $('.submit').click(function( event ) {  
        if($('#form').valid()){
          $('#hiddenRecaptcha').remove();
          grecaptcha.execute(`{{ Session::get('ConfigData')['recaptcha_site_key'] }}`).then(function(token) {
            $('#form').append('<input type="hidden" name="hiddenRecaptcha" id="hiddenRecaptcha" value="'+token+'">');
            setTimeout(function(){  $('#form').submit(); }, 500);
          });
        }
    });
  });
</script>
@endsection