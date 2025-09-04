@extends('Frontend.layouts.master')

@section('css')
<!-- Include css -->
@endsection

@section('content')

<div class="home_background">

    <section class="shop_banner_section">
        <div class="page_container">
            <div class="row">
                <div class="col-12">
                    <div class="sbs_screen_block" style="background:url('{{ asset('assets/storage/homeimages/').'/'.$bannerContentData->shop_banner_image }}') no-repeat;background-position: top center;background-size: cover;" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="100" data-aos-offset="0">
                        <div class="sbs_content_block">
                            <div class="" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="0">
                                <h1> {{ $bannerContentData->shop_banner_title }} </h1>
                                <p> {{ $bannerContentData->shop_banner_description }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--  --}}

    <div class="product_section">
        <div class="page_container">
            <h1 class="pstitle" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> Products </h1>
            <div class="row">
                @forEach($productData as $product)
                <div class="col-sm-6">
                    <div class="banner_card_block" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">
                        <img src="{{ asset('assets/storage/products/'.$product->product_image) }}" alt="ring" />
                        <div class="ring_info_block">
                            <div class="rib_box">
                                <h2 class="ring_name"> {{ $product->product_name }} </h2>
                                @php
                                    $minPrice = min($product->product_price);
                                @endphp
                                <p class="ring_amount"> From ${{ number_format($minPrice,2) }} </p>
                            </div>
                            <a href="{{ route('frontend.static.productdetail', ['pid' => encrypt($product->product_id)]) }}" class="btn shop_btn"> Shop </a>
                        </div>
                    </div>
                </div>
                @endforEach
            </div>
        </div>
    </div>

    {{--  --}}

    <section class="morder_smart_section">
        <div class="page_container">
            <div class="mss_heading_block">
                <h2 data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $smartContentData->shop_smart_heading }} <br/> <span> {{ $smartContentData->shop_smart_sub_heading }} </span> </h2>
                <p data-aos="fade-up" data-aos-delay="350" data-aos-offset="0"> {{ $smartContentData->shop_smart_description }} </p>
            </div>

            <div class="mss_video_block" style="background:url('{{ asset('assets/storage/homeimages/').'/'.$smartContentData->shop_smart_video_image }}') no-repeat;background-position: top center;background-size: cover;" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                <div class="video_content_block" data-aos="fade-up" data-aos-delay="400" data-aos-offset="0">
                    <h3> {{ $smartContentData->shop_smart_video_heading }} <br/> <span> {{ $smartContentData->shop_smart_video_sub_heading }} </span> </h3>
                    <div class="vcb_row">
                        <span> {{ $smartContentData->shop_smart_video_tagline }} </span>
                        <button class="btn play_btn" data-bs-toggle="modal" data-bs-target="#videoModal"> <img src="{{asset('assets/images/playicon.png')}}" alt="play" /> </button>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{--  --}}

    <section class="about_ring_section">
        <div class="ars_heading_row">
            <div class="page_container">
                <div class="arshr_block">
                    <h4 class="arshr_title" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $complementData->shop_complement_section_heading ?? '' }} <br/> <span> {{ $complementData->shop_complement_section_sub_heading ?? '' }} </span> </h4>
                    <p class="arshr_desc" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $complementData->shop_complement_section_description ?? '' }} </p>
                </div>
            </div>
        </div>

        {{--  --}}

        <div class="ars_content_block">
            <div class="page_container">
                <div class="ars_tab_block">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @forEach($complementContentData as $complementKeyPre => $complementContent)
                            <button class="nav-link @if($complementKeyPre == 0) active @endif" id="v-pills-{{ $complementKeyPre }}" data-bs-toggle="pill" data-bs-target="#v-pills-tab-{{ $complementKeyPre }}" type="button" role="tab" aria-controls="v-pills-{{ $complementKeyPre }}" aria-selected="true" data-aos="fade-right" data-aos-delay="300" data-aos-offset="0"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="" > {{ $complementContent->shop_complement_content_title }} </button>
                            @endforEach
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            @forEach($complementContentData as $complementKey => $complementContent)
                            <div class="tab-pane fade show @if($complementKey == 0) active @endif" id="v-pills-tab-{{ $complementKey }}" role="tabpanel" aria-labelledby="v-pills-{{ $complementKey }}">
                                <div class="about_ring_content_block" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                                    <div class="arcb_image_block">
                                        <div class="arcb_image" data-aos="zoom-in" data-aos-delay="300" data-aos-offset="0">
                                            <img src="{{ asset('assets/storage/homeimages/').'/'.$complementContent->shop_complement_content_first_image }}" alt="img" />
                                        </div>
                                        <div class="arcb_sec_image" data-aos="zoom-in" data-aos-delay="350" data-aos-offset="0">
                                            <img src="{{ asset('assets/storage/homeimages/').'/'.$complementContent->shop_complement_content_second_image }}" alt="img" />
                                        </div>
                                    </div>
                                    @if(!empty($complementContent->shop_complement_content_third_image))
                                    <div class="arcb_info arcb_info_left" data-aos="zoom-in" data-aos-delay="400" data-aos-offset="0">
                                        <img src="{{ asset('assets/storage/homeimages/').'/'.$complementContent->shop_complement_content_third_image }}" alt="img" />
                                    </div>
                                    @endif

                                    @if(!empty($complementContent->shop_complement_content_fourth_image))
                                    <div class="arcb_info arcb_info_right" data-aos="zoom-in" data-aos-delay="500" data-aos-offset="0">
                                        <img src="{{ asset('assets/storage/homeimages/').'/'.$complementContent->shop_complement_content_fourth_image }}" alt="img" />
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforEach
                            {{--  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--  --}}

    <section class="tech_inside_section">
        <div class="page_container">
            <h3 class="tis_heading"  data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> All the Tech Inside </h3>
            <div class="tis_card_block">
                <div class="row">
                    @forEach($techContentData as $techContent)
                    <div class="col-lg-4 col-sm-6">
                        <div class="tis_card" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                            <div class="tis_card_img">
                                <img src="{{ asset('assets/storage/homeimages/'.$techContent->shop_tech_section_image) }}" alt="tis" />
                            </div>
                            <div class="tis_card_content">
                                <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="300" data-aos-offset="0">
                                    <h4> {{ $techContent->shop_tech_section_title }} </h4>
                                    <p class="mb-0"> {{ $techContent->shop_tech_section_description }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforEach
                    {{-- <div class="col-lg-4 col-sm-6">
                        <div class="tis_card" data-aos="fade-up" data-aos-delay="350" data-aos-offset="0">
                            <div class="tis_card_img">
                                <img src="{{asset('assets/images/tisimg2.png')}}" alt="tis" />
                            </div>
                            <div class="tis_card_content">
                                <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="350" data-aos-offset="0">
                                    <h4> Precision Sensors </h4>
                                    <p class="mb-0"> Digital temperature sensor monitors variations in your body temperature </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="tis_card" data-aos="fade-up" data-aos-delay="400" data-aos-offset="0">
                            <div class="tis_card_img">
                                <img src="{{asset('assets/images/tisimg3.png')}}" alt="tis" />
                            </div>
                            <div class="tis_card_content">
                                <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="400" data-aos-offset="0">
                                    <h4> Stay Powered </h4>
                                    <p class="mb-0"> Power that lasts—up to 8 days per charge </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="tis_card" data-aos="fade-up" data-aos-delay="450" data-aos-offset="0">
                            <div class="tis_card_img">
                                <img src="{{asset('assets/images/tisimg4.png')}}" alt="tis" />
                            </div>
                            <div class="tis_card_content">
                                <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="300" data-aos-offset="0">
                                    <h4> Lightweight by Design </h4>
                                    <p class="mb-0"> Just 2.88 mm thin—like a wedding band </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="tis_card" data-aos="fade-up" data-aos-delay="450" data-aos-offset="0">
                            <div class="tis_card_img">
                                <img src="{{asset('assets/images/tisimg5.png')}}" alt="tis" />
                            </div>
                            <div class="tis_card_content">
                                <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="300" data-aos-offset="0">
                                    <h4> Connects Seamlessly </h4>
                                    <p class="mb-0"> Choose metric or imperial—your data, your way. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="tis_card" data-aos="fade-up" data-aos-delay="500" data-aos-offset="0">
                            <div class="tis_card_img">
                                <img src="{{asset('assets/images/tisimg6.jpg')}}" alt="tis" />
                            </div>
                            <div class="tis_card_content">
                                <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="500" data-aos-offset="0">
                                    <h4> Your World, Synced </h4>
                                    <p class="mb-0"> Bluetooth® Low Energy for efficient pairing </p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

</div>

{{--  --}}

<div class="modal fade" id="videoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if($videoType == 1)
        <iframe width="100%" src="{{ $videoUrl }}" frameborder="0" allowfullscreen></iframe>
        @else
        <video width="100%" height="auto" controls>
            <source src="{{ $videoUrl }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        @endif
      </div>
    </div>
  </div>
</div>




@endsection

@section('script')
<!-- Include Js -->
@endsection
