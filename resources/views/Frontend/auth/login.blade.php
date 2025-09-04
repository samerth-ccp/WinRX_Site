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
  <form class="form-signin" method="post">
    <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

    <div>
      <ul class="social_icons">
          <li class="btn-hover"><a href="{{ route('frontend.googleredirect')}}" ><i data-feather="mail"></i></a></li>
          <li class="btn-hover"><a href="{{ route('frontend.facebookredirect')}}"><i data-feather="facebook"></i></a></li>
          <li class="btn-hover"><a href="{{ route('frontend.linkedinredirect')}}" ><i data-feather="linkedin"></i></a></li>
          <li class="btn-hover"><a href="{{ route('frontend.twitterredirect')}}"><i data-feather="twitter"></i></a></li>
      </ul>
    </div>

    @csrf

    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="inputEmail" name="email" class="form-control required email" placeholder="Email address"  autofocus>

    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="password" class="form-control required" placeholder="Password" >


    <div class="checkbox mb-3">
      <label>
        <a href="{{ route('frontend.forgotpassowrd') }}"> Forgot Password?</a>
      </label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted"></p>
  </form>
@endsection

@section('script')
<!-- Include Js-->
@endsection
