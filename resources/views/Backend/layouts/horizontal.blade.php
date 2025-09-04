<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24"> <span class="logo-txt">Minia</span>
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="24"> <span class="logo-txt">Minia</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="@lang('Search')">
                    <button class="btn btn-primary" type="button"><i class="bx bx-search-alt align-middle"></i></button>
                </div>
            </form>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="@lang('Search')" aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
            <button type="button" class="btn header-item waves-effect"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @switch(Session::get('lang'))
                        @case('ru')
                            <img src="{{ URL::asset('/assets/images/flags/russia.jpg')}}" alt="Header Language" height="16">
                        @break
                        @case('it')
                            <img src="{{ URL::asset('/assets/images/flags/italy.jpg')}}" alt="Header Language" height="16">
                        @break
                        @case('de')
                            <img src="{{ URL::asset('/assets/images/flags/germany.jpg')}}" alt="Header Language" height="16">
                        @break
                        @case('es')
                            <img src="{{ URL::asset('/assets/images/flags/spain.jpg')}}" alt="Header Language" height="16">
                        @break
                        @default
                            <img src="{{ URL::asset('/assets/images/flags/us.jpg')}}" alt="Header Language" height="16">
                    @endswitch
                </button>
                <div class="dropdown-menu dropdown-menu-end">

                    <!-- item-->
                    <a href="{{ url('index/en') }}" class="dropdown-item notify-item language" data-lang="eng">
                        <img src="{{ URL::asset ('/assets/images/flags/us.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">English</span>
                    </a>
                    <!-- item-->
                    <a href="{{ url('index/es') }}" class="dropdown-item notify-item language" data-lang="sp">
                        <img src="{{ URL::asset ('/assets/images/flags/spain.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="{{ url('index/de') }}" class="dropdown-item notify-item language" data-lang="gr">
                        <img src="{{ URL::asset ('/assets/images/flags/germany.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="{{ url('index/it') }}" class="dropdown-item notify-item language" data-lang="it">
                        <img src="{{ URL::asset ('/assets/images/flags/italy.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="{{ url('index/ru') }}" class="dropdown-item notify-item language" data-lang="ru">
                        <img src="{{ URL::asset ('/assets/images/flags/russia.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                    </a>

                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="grid" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <div class="p-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assetsgithub.png" alt="Github">
                                    <span>GitHub</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assetsbitbucket.png" alt="bitbucket">
                                    <span>Bitbucket</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assetsdribbble.png" alt="dribbble">
                                    <span>Dribbble</span>
                                </a>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assetsdropbox.png" alt="dropbox">
                                    <span>Dropbox</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assetsmail_chimp.png" alt="mail_chimp">
                                    <span>Mail Chimp</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assetsslack.png" alt="slack">
                                    <span>Slack</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell" class="icon-lg"></i>
                    <span class="badge bg-danger rounded-pill">5</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> @lang('Notifications') </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small text-reset text-decoration-underline"> @lang('Unread') (3)</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="#!" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ URL::asset('/assets/images/users/avatar-3.jpg') }}"
                                    class="me-3 rounded-circle avatar-sm" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">@lang('James_Lemire')</h6>
                                    <div class="font-size-13 text-muted">
                                        <p class="mb-1">@lang('It_will_seem_like_simplified_English').</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>@lang('1_hours_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#!" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="bx bx-cart"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">@lang('Your_order_is_placed')</h6>
                                    <div class="font-size-13 text-muted">
                                        <p class="mb-1">@lang('If_several_languages_coalesce_the_grammar')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>@lang('3_min_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#!" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="bx bx-badge-check"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">@lang('Your_item_is_shipped')</h6>
                                    <div class="font-size-13 text-muted">
                                        <p class="mb-1">@lang('If_several_languages_coalesce_the_grammar')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>@lang('3_min_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="#!" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ URL::asset('/assets/images/users/avatar-6.jpg') }}"
                                    class="me-3 rounded-circle avatar-sm" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">@lang('Salena_Layfield')</h6>
                                    <div class="font-size-13 text-muted">
                                        <p class="mb-1">@lang('As_a_skeptical_Cambridge_friend_of_mine_occidental').</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>@lang('1_hours_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span>@lang('View_More')</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item right-bar-toggle me-2">
                    <i data-feather="settings" class="icon-lg"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ isset(Session::get('AdminData')->profile_image) ? asset(Session::get('AdminData')->profile_image) : asset('/assets/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ucfirst(Session::get('AdminData')->name)}}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="apps-contacts-profile"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> @lang('Profile')</a>
                    <a class="dropdown-item" href="#"><i class="mdi mdi-credit-card-outline font-size-16 align-middle me-1"></i> @lang('Billing')</a>
                    <a class="dropdown-item" href="#"><i class="mdi mdi-account-settings font-size-16 align-middle me-1"></i> @lang('Settings')</a>
                    <a class="dropdown-item" href="auth-lock-screen"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> @lang('Lock_screen')</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('backend.logout') }}" ><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> <span key="t-logout">@lang('Logout')</span></a>
                </div>
            </div>

        </div>
    </div>
</header>

<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="index" id="topnav-dashboard" role="button">
                            <i data-feather="home"></i><span data-key="t-dashboards">@lang('Dashboard')</span>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button">
                            <i data-feather="briefcase"></i>
                            <span data-key="t-components">@lang('Elements')</span>
                            <div class="arrow-down"></div>
                        </a>

                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl" aria-labelledby="topnav-uielement">
                            <div class="ps-2 p-lg-0">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div>
                                            <div class="menu-title">@lang('Components')</div>
                                            <div class="row g-0">
                                                <div class="col-lg-5">
                                                    <div>
                                                        <a href="ui-alerts" class="dropdown-item" data-key="t-alerts">@lang('Alerts')</a>
                                                        <a href="ui-buttons" class="dropdown-item" data-key="t-buttons">@lang('Buttons')</a>
                                                        <a href="ui-cards" class="dropdown-item" data-key="t-cards">@lang('Cards')</a>
                                                        <a href="ui-carousel" class="dropdown-item" data-key="t-carousel">@lang('Carousel')</a>
                                                        <a href="ui-dropdowns" class="dropdown-item" data-key="t-dropdowns">@lang('Dropdowns')</a>
                                                        <a href="ui-grid" class="dropdown-item" data-key="t-grid">@lang('Grid')</a>
                                                        <a href="ui-images" class="dropdown-item" data-key="t-images">@lang('Images')</a>
                                                        <a href="ui-modals" class="dropdown-item" data-key="t-modals">@lang('Modals')</a>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5">
                                                    <div>
                                                        <a href="ui-offcanvas" class="dropdown-item" data-key="t-offcanvas">@lang('Offcanvas')</a>
                                                        <a href="ui-progressbars" class="dropdown-item" data-key="t-progress-bars">@lang('Progress_Bars')</a>
                                                        <a href="ui-tabs-accordions" class="dropdown-item" data-key="t-tabs-accordions">@lang('Tabs_n_Accordions')</a>
                                                        <a href="ui-typography" class="dropdown-item" data-key="t-typography">Typography</a>
                                                        <a href="ui-video" class="dropdown-item" data-key="t-video">@lang('Video')</a>
                                                        <a href="ui-general" class="dropdown-item" data-key="t-general">@lang('General')</a>
                                                        <a href="ui-colors" class="dropdown-item" data-key="t-colors">@lang('Colors')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div>
                                            <div class="menu-title">@lang('Extended')</div>
                                            <div>
                                                <a href="extended-lightbox" class="dropdown-item" data-key="t-lightbox">@lang('Lightbox')</a>
                                                <a href="extended-rangeslider" class="dropdown-item" data-key="t-range-slider">@lang('Range_Slider')</a>
                                                <a href="extended-sweet-alert" class="dropdown-item" data-key="t-sweet-alert">@lang('SweetAlert_2')</a>
                                                <a href="extended-session-timeout" class="dropdown-item" data-key="t-session-timeout">@lang('Session_Timeout')</a>
                                                <a href="extended-rating" class="dropdown-item" data-key="t-rating">@lang('Rating')</a>
                                                <a href="extended-notifications" class="dropdown-item" data-key="t-notifications">@lang('Notifications')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages" role="button">
                            <i data-feather="grid"></i><span data-key="t-apps">@lang('Apps')</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-pages">

                            <a href="apps-calendar" class="dropdown-item" data-key="t-calendar">@lang('Calendar')</a>
                            <a href="apps-chat" class="dropdown-item" data-key="t-chat">@lang('Chat')</a>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                    role="button">
                                    <span data-key="t-email">@lang('Email')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a href="apps-email-inbox" class="dropdown-item" data-key="t-inbox">@lang('Inbox')</a>
                                    <a href="apps-email-read" class="dropdown-item" data-key="t-read-email">@lang('Read_Email')</a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-invoice"
                                    role="button">
                                    <span data-key="t-invoices">@lang('Invoices')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-invoice">
                                    <a href="apps-invoices-list" class="dropdown-item" data-key="t-invoice-list">@lang('Invoice_List')</a>
                                    <a href="apps-invoices-detail" class="dropdown-item" data-key="t-invoice-detail">@lang('Invoice_Detail')</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-contact"
                                    role="button">
                                    <span data-key="t-contacts">@lang('Contacts')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-contact">
                                    <a href="apps-contacts-grid" class="dropdown-item" data-key="t-user-grid">@lang('User_Grid')</a>
                                    <a href="apps-contacts-list" class="dropdown-item" data-key="t-user-list">@lang('User_List')</a>
                                    <a href="apps-contacts-profile" class="dropdown-item" data-key="t-profile">@lang('Profile')</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                            <i data-feather="box"></i><span data-key="t-components">@lang('Components')</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-form"
                                    role="button">
                                    <span data-key="t-forms">@lang('Forms')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-form">
                                    <a href="form-elements" class="dropdown-item" data-key="t-form-elements">@lang('Basic_Elements')</a>
                                    <a href="form-validation" class="dropdown-item" data-key="t-form-validation">@lang('Validation')</a>
                                    <a href="form-advanced" class="dropdown-item" data-key="t-form-advanced">@lang('Advanced_Plugins')</a>
                                    <a href="form-editors" class="dropdown-item" data-key="t-form-editors">@lang('Editors')</a>
                                    <a href="form-uploads" class="dropdown-item" data-key="t-form-upload">@lang('File_Upload')</a>
                                    <a href="form-wizard" class="dropdown-item" data-key="t-form-wizard">@lang('Wizard')</a>
                                    <a href="form-mask" class="dropdown-item" data-key="t-form-mask">@lang('Mask')</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-table"
                                    role="button">
                                    <span data-key="t-tables">@lang('Tables')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-table">
                                    <a href="tables-basic" class="dropdown-item" data-key="t-basic-tables">@lang('Bootstrap_Basic')</a>
                                    <a href="tables-datatable" class="dropdown-item" data-key="t-data-tables">@lang('DataTables')</a>
                                    <a href="tables-responsive" class="dropdown-item" data-key="t-responsive-table">@lang('Responsive')</a>
                                    <a href="tables-editable" class="dropdown-item" data-key="t-editable-table">@lang('Editable')</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-charts"
                                    role="button">
                                    <span data-key="t-charts">@lang('Charts')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <a href="charts-apex" class="dropdown-item" data-key="t-apex-charts">@lang('Apexcharts')</a>
                                    <a href="charts-echart" class="dropdown-item" data-key="t-e-charts">@lang('Echarts')</a>
                                    <a href="charts-chartjs" class="dropdown-item" data-key="t-chartjs-charts">@lang('Chartjs')</a>
                                    <a href="charts-knob" class="dropdown-item" data-key="t-knob-charts">@lang('Jquery_Knob')</a>
                                    <a href="charts-sparkline" class="dropdown-item" data-key="t-sparkline-charts">@lang('Sparkline')</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-icons"
                                    role="button">
                                    <span data-key="t-icons">@lang('Icons')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                    <a href="icons-boxicons" class="dropdown-item" data-key="t-boxicons">@lang('Boxicons')</a>
                                    <a href="icons-materialdesign" class="dropdown-item" data-key="t-material-design">@lang('Material_Design')</a>
                                    <a href="icons-dripicons" class="dropdown-item" data-key="t-dripicons">@lang('Dripicons')</a>
                                    <a href="icons-fontawesome" class="dropdown-item" data-key="t-font-awesome">@lang('Font_Awesome_5')</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-map" role="button">
                                    <span data-key="t-maps">@lang('Maps')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-map">
                                    <a href="maps-google" class="dropdown-item" data-key="t-g-maps">@lang('Google')</a>
                                    <a href="maps-vector" class="dropdown-item" data-key="t-v-maps">@lang('Vector')</a>
                                    <a href="maps-leaflet" class="dropdown-item" data-key="t-l-maps">@lang('Leaflet')</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button">
                            <i data-feather="file-text"></i><span data-key="t-extra-pages">@lang('Extra_pages')</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth" role="button">
                                    <span data-key="t-authentication">@lang('Authentication')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="auth-login" class="dropdown-item" data-key="t-login">@lang('Login')</a>
                                    <a href="auth-register" class="dropdown-item" data-key="t-register">@lang('Register')</a>
                                    <a href="auth-recoverpw" class="dropdown-item" data-key="t-recover-password">@lang('Recover_Password')</a>
                                    <a href="auth-lock-screen" class="dropdown-item" data-key="t-lock-screen">@lang('Lock_Screen')</a>
                                    <a href="auth-confirm-mail" class="dropdown-item" data-key="t-confirm-mail">@lang('Confirm_Mail')</a>
                                    <a href="auth-email-verification" class="dropdown-item" data-key="t-email-verification">@lang('Email_Verification')</a>
                                    <a href="auth-two-step-verification" class="dropdown-item" data-key="t-two-step-verification">@lang('Two_Step_Verification')</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility" role="button">
                                    <span data-key="t-utility">@lang('Utility')</span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-utility">
                                    <a href="pages-starter" class="dropdown-item" data-key="t-starter-page">@lang('Starter_Page')</a>
                                    <a href="pages-maintenance" class="dropdown-item" data-key="t-maintenance">@lang('Maintenance')</a>
                                    <a href="pages-comingsoon" class="dropdown-item" data-key="t-coming-soon">@lang('Coming_Soon')</a>
                                    <a href="pages-timeline" class="dropdown-item" data-key="t-timeline">Timeline</a>
                                    <a href="pages-faqs" class="dropdown-item" data-key="t-faqs">@lang('FAQs')</a>
                                    <a href="pages-pricing" class="dropdown-item" data-key="t-pricing">@lang('Pricing')</a>
                                    <a href="pages-404" class="dropdown-item" data-key="t-error-404">@lang('Error_404')</a>
                                    <a href="pages-500" class="dropdown-item" data-key="t-error-500">@lang('Error_500')</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="layouts-horizontal" role="button">
                            <i data-feather="layout"></i><span data-key="t-horizontal">@lang('Horizontal')</span>
                        </a>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>