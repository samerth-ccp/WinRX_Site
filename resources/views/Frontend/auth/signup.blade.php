@extends('Frontend.layouts.master')

@section('css')
<!-- Include Css-->
<style>
.social_icons {
    list-style: none;
    padding: 30px 0px;
    display: flex;
    margin: 0px;
    justify-content: center;
}
.social_icons li {
    padding-right: 15px;
}
</style>
@endsection
@section('content')
    <div class="container">
      <form method="post" class="form-signin" id="form-signup" >
        <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please Sign Up</h1>

        <div>
          <ul class="social_icons">
              <li class="btn-hover"><a href="{{ route('frontend.googleredirect')}}" ><i data-feather="mail"></i></a></li>
              <li class="btn-hover"><a href="{{ route('frontend.facebookredirect')}}"><i data-feather="facebook"></i></a></li>
          <li class="btn-hover"><a href="{{ route('frontend.linkedinredirect')}}" ><i data-feather="linkedin"></i></a></li>
          <li class="btn-hover"><a href="{{ route('frontend.twitterredirect')}}"><i data-feather="twitter"></i></a></li>
          </ul>
        </div>

        @csrf
        <label for="name" class="sr-only">Name</label>
        <input type="text" id="name" name="name" class="form-control required" placeholder="Name">

        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control required email" placeholder="Email address"  autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control passcheck required" placeholder="Password">
        
        <div class="checkbox mb-3">
        </div>
        <button class="btn btn-lg btn-primary btn-block signup" type="button">Sign Up</button>
        <p class="mt-5 mb-3 text-muted"></p>
      </form>
    </div>
@endsection

@section('script')
<!-- Include Js-->
<script src="https://www.google.com/recaptcha/api.js?render={{ Session::get('ConfigData')['recaptcha_site_key'] }}"></script>
<script>
  $(document).ready(function(){
    $('.signup').click(function(event){  
        if($('#form-signup').valid()){
          $('#hiddenRecaptcha').remove();
            grecaptcha.execute(`{{ Session::get('ConfigData')['recaptcha_site_key'] }}`).then(function(token) {
            $('#form-signup').append('<input type="hidden" name="hiddenRecaptcha" id="hiddenRecaptcha" value="'+token+'">');
            setTimeout(function(){  $('#form-signup').submit(); }, 500);
          });
        }
    });
  });
</script>
@endsection