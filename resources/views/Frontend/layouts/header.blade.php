{{-- <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
    <a href="{{ route('frontend.index.index') }}" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none " style="width:100px;">
        <img src="{{ asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_icon'] }}" style="width:100%;padding-left: 30px;">
    </a>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-right mb-md-0" style="padding-right: 30px;">
        <li><a href="{{ route('frontend.index.index') }}" class="nav-link px-2 link-secondary">Home</a></li>
        <li><a href="{{ route('frontend.static.contactus') }}" class="nav-link px-2 link-dark">Contact Us</a></li>
        <li><a href="{{ route('frontend.static.aboutus') }}" class="nav-link px-2 link-dark">About Us</a></li>
        <li><a href="{{ route('frontend.static.terms') }}" class="nav-link px-2 link-dark">Terms & Condition</a></li>
        <li><a href="{{ route('frontend.static.privacy') }}" class="nav-link px-2 link-dark">Privacy Policy</a></li>
        @if(empty(auth()->guard('user')->id()))
            <li><a href="{{ route('frontend.login') }}" class="nav-link px-2 link-dark">Login</a></li>
            <li><a href="{{ route('frontend.register') }}" class="nav-link px-2 link-dark">Sign-up</a></li>
        @else
            <li><a href="{{ route('frontend.profile') }}" class="nav-link px-2 link-dark">Profile</a></li>
            <li><a href="{{ route('frontend.logout') }}" class="nav-link px-2 link-dark">Log Out</a></li>
        @endif

    </ul>
</header> --}}
@php
    $currentUrl = $_SERVER['REQUEST_URI'];
@endphp
<header>
    <div class="page_container">
        <div class="navbar">
            <a href="{{ route('frontend.index.index') }}" class="d-block sitelogo">
                <img src="{{ asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_icon'] }}">
                {{--<img src="{{asset('assets/images/Logo.png')}}" alt="img" />--}}
            </a>
            {{--  --}}
            <ul class="nav ms-auto">
                @if (strpos($currentUrl, 'shop') !== false)
                <li class="d-xl-block d-none"><a href="{{ route('frontend.index.index').'#why-ring' }}" class="nav-link"> Why Ring </a></li>
                @else
                <li class="d-xl-block d-none"><a href="#why-ring" class="nav-link"> Why Ring </a></li>
                @endif
                <li class="d-xl-block d-none shop_link"><a href="{{ route('frontend.static.shop') }}" class="nav-link"> Shop </a></li>
                <li class="cart_link"><a href="{{ route('frontend.static.cart') }}" class="nav-link"> <span class="card_count"></span> <img src="{{asset('assets/images/Cart.svg')}}" alt="img" /> </a></li>
            </ul>
            {{--  --}}
            <div class="dot_icon d-xl-none ms-3 d-block" id="openSidebarMenu">
                <img src="{{asset('assets/images/add-list.png')}}" alt="menu" />
            </div>
        </div>
    </div>
</header>

<div id="sidebarMenu" class="d-xl-none d-block hidden">
    <div class="close_button" id="closeSidebarMenu"> <img src="{{asset('assets/images/close.png')}}" alt=""> </div>
    <ul class="sidebarMenuInner">
        @if (strpos($currentUrl, 'shop') !== false)
        <li><a href="{{ route('frontend.index.index').'#why-ring' }}" class="nav-link"> Why Ring </a></li>
        @else
        <li><a href="#why-ring" class="nav-link"> Why Ring </a></li>
        @endif
        <li class="shop_link"><a href="{{ route('frontend.static.shop') }}" class="nav-link"> Shop </a></li>
    </ul>
</div>
