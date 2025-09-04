<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {{-- <li class="menu-title" data-key="t-menu">@lang('Menu')</li> --}}

                <li>
                    <a href="{{ route('backend.dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="settings"></i>
                        <span data-key="t-config">Site Configuration</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('backend.siteconfigs',['key'=>'configuration']) }}"> <i data-feather="sliders"></i>Configuration</a></li>
                        {{-- <li><a href="{{ route('backend.siteconfigs',['key'=>'social']) }}"> <i data-feather="globe"></i> Social Settings</a></li>
                        <li><a href="{{ route('backend.siteconfigs',['key'=>'payment']) }}"> <i data-feather="dollar-sign"></i> Payment Settings</a></li>
                        <li><a href="{{ route('backend.siteconfigs',['key'=>'api']) }}"> <i data-feather="key"></i> API Settings (Keys)</a></li> --}}
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-pages">Home Page</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        {{-- <li><a href="{{ route('backend.emailtemplate') }}"> <i data-feather="mail"></i>Email Template</a></li> --}}
                        {{-- <li><a href="{{ route('backend.pages') }}"> <i data-feather="layout"></i> Pages</a></li> --}}
                        <li><a href="{{ route('backend.bannersection') }}"> <i data-feather="layout"></i> Banner Section</a></li>
                        <li><a href="{{ route('backend.slidercontent') }}"> <i data-feather="layout"></i> Slider Main Section</a></li>
                        <li><a href="{{ route('backend.slidersection') }}"> <i data-feather="layout"></i> Slider Section</a></li>
                        <li><a href="{{ route('backend.aboutsection') }}"> <i data-feather="layout"></i> About Section</a></li>
                        <li><a href="{{ route('backend.aboutcontent') }}"> <i data-feather="layout"></i> About Content</a></li>
                        <li><a href="{{ route('backend.newerasection') }}"> <i data-feather="layout"></i> New ERA Section</a></li>
                        <li><a href="{{ route('backend.neweracontent') }}"> <i data-feather="layout"></i> New ERA Content</a></li>
                        <li><a href="{{ route('backend.smartsolutions') }}"> <i data-feather="layout"></i> Smart Section</a></li>
                        <li><a href="{{ route('backend.accuratesection') }}"> <i data-feather="layout"></i> Accurate Section</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-pages">Shop Page</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('backend.shopbannersection') }}"> <i data-feather="layout"></i> Banner Section</a></li>
                        <li><a href="{{ route('backend.shopsmartsection') }}"> <i data-feather="layout"></i> Smart Section</a></li>
                        <li><a href="{{ route('backend.shopcomplementsection') }}"> <i data-feather="layout"></i> Complement Section</a></li>
                        <li><a href="{{ route('backend.shopcomplementcontent') }}"> <i data-feather="layout"></i> Complement Content</a></li>
                        <li><a href="{{ route('backend.shoptechsection') }}"> <i data-feather="layout"></i> Tech Section</a></li>
                    </ul>
                </li>

                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="users"></i>
                        <span data-key="t-authentication">Users</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('backend.users') }}"> <i data-feather="user"></i> User</a></li>
                    </ul>
                </li> --}}


                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        {{ svg('polaris-product-cost-icon') }}
                        <span data-key="t-authentication">Manage Product</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('sizes')}}"> {{ svg('css-size') }} Size Chart</a></li>
                        <li><a href="{{route('colors')}}"> {{ svg('css-color-bucket') }} Color Chart</a></li>
                        <li><a href="{{route('products')}}"> {{ svg('css-product-hunt') }} Products</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('backend.logout') }}">
                        <i data-feather="log-out"></i>
                        <span data-key="t-dashboard">Logout</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
