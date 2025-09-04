{{-- <footer class="footer mt-auto py-3 bg-dark">
  <div class="container">
    <ul class="social_icons">
        @if(!empty(Session::get('ConfigData')['facebook_url']))
        <li class="btn-hover"><a href="{{ Session::get('ConfigData')['facebook_url'] }}" target="_blank" rel="noreferrer"><i data-feather="facebook"></i></a></li>
        @endif
        @if(!empty(Session::get('ConfigData')['instagram_url']))
        <li class="btn-hover"><a href="{{ Session::get('ConfigData')['instagram_url'] }}" target="_blank" rel="noreferrer"><i data-feather="instagram"></i></a></li>
        @endif
        @if(!empty(Session::get('ConfigData')['linkedin_url']))
        <li class="btn-hover"><a href="{{ Session::get('ConfigData')['linkedin_url'] }}" target="_blank" rel="noreferrer"><i data-feather="linkedin"></i></a></li>
        @endif
        @if(!empty(Session::get('ConfigData')['youtube_url']))
        <li class="btn-hover"><a href="{{ Session::get('ConfigData')['youtube_url'] }}" target="_blank" rel="noreferrer"><i data-feather="youtube"></i></a></li>
        @endif
      </ul>
    <span class="text-muted"> {{ date('Y') }} © Place sticky footer content here.</span>
  </div>
</footer> --}}


<footer>
  <div class="page_container">
      <a href="{{ route('frontend.index.index') }}" class="d-block sitelogo">
          {{-- <img src="{{ asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_icon'] }}"> --}}
          {{--<img src="{{asset('assets/images/Logo.png')}}" alt="img" />--}}
          <img src="{{ asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_icon'] }}">
      </a>
      <ul class="social_icons">
        @if(!empty(Session::get('ConfigData')['facebook_url']))
        <li class="btn-hover">
          <a href="{{ Session::get('ConfigData')['facebook_url'] }}" target="_blank" rel="noreferrer">
            <img src="{{asset('assets/images/facebook.svg')}}" alt="img" />
          </a>
        </li>
        @endif
        @if(!empty(Session::get('ConfigData')['instagram_url']))
        <li class="btn-hover">
          <a href="{{ Session::get('ConfigData')['instagram_url'] }}" target="_blank" rel="noreferrer">
            <img src="{{asset('assets/images/instagram.svg')}}" alt="img" />
          </a>
        </li>
        @endif
        @if(!empty(Session::get('ConfigData')['youtube_url']))
        <li class="btn-hover">
          <a href="{{ Session::get('ConfigData')['youtube_url'] }}" target="_blank" rel="noreferrer">
            <img src="{{asset('assets/images/youtube.svg')}}" alt="img" />
          </a>
        </li>
        @endif
        @if(!empty(Session::get('ConfigData')['twitter_url']))
        <li class="btn-hover">
          <a href="{{ Session::get('ConfigData')['twitter_url'] }}" target="_blank" rel="noreferrer">
            <img src="{{asset('assets/images/twitter.svg')}}" alt="img" />
          </a>
        </li>
        @endif
      </ul>
      <p class="copy_right_text"> © {{ date('Y') }}, {{ Session::get('ConfigData')['site_name'] }} - Healthcare Technology Solutions</p>
  </div>
</footer>
