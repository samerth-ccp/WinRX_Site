@extends('Frontend.layouts.master')

@section('css')
<!-- Include Css-->
@endsection

@section('content')
    <div class="container">
      <form class="form-signin" method="post" id="form">
        <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Forgot Password</h1>
        @csrf
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control required" placeholder="Email address"  autofocus>

        <div class="checkbox mb-3">
          <label>
            
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block submit" type="button">Reset Password</button>
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