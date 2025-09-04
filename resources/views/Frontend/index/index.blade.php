@extends('Frontend.layouts.master')

@section('css')
<!-- Include css -->
@endsection

@section('content')

<div class="home_background">

<div class="banner_section">
    <div class="page_container">
        <div class="row">
            <div class="col-12">
                <div class="banner_screen_block" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="100" data-aos-offset="0">
                    <img class="banner_bg" src="{{ asset('assets/storage/homeimages/').'/'.$bannerData->banner_background_image }}" alt="ring" />
                    <div class="bsb_content_block">
                        <div class="" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="0">
                            <h1> {{ $bannerData->banner_first_heading ?? 'Minimal. Intelligent.' }} <img class="ring-flip" src="{{ asset('assets/storage/homeimages/').'/'.$bannerData->banner_image }}" alt="ring" /> {{ $bannerData->banner_second_heading ?? 'Made for you.' }} </h1>
                            <p> {{ $bannerData->banner_para ?? 'Your complete health loop' }} </p>
                        </div>
                    </div>
                </div>
            </div>

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
        {{--  --}}
    </div>
</div>

{{--  --}}

<section class="accuracy_section">
    <div class="page_container">
        <h2 class="as-heading" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $sliderMainData->slider_content_heading }} </h2>

        <div class="accuracy_carousel" id="accuracyCarousel">
            <div class="accuracy_track">
                @forEach($sliderData as $sliderDataVal)
                <div class="accuracy_card" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                    <div class="accuracy_card_inner">
                        <div class="aci_img"><img src="{{ asset('assets/storage/homeimages/').'/'.$sliderDataVal->slider_section_background_image }}" alt="accuracy" /> </div>

                        <div class="accuracy_card_content_block" id="acharfContentBlock">
                            <div class="accb_top_row">
                                <p class="accbtr_title"> <span class="text"> {{ $sliderDataVal->slider_section_tagline }} </span> <span class="icon"> <img src="{{ asset('assets/storage/homeimages/').'/'.$sliderDataVal->slider_section_tagline_image }}" alt="" /> </span> </p>
                                <button class="btn show_btn"> <img src="{{asset('assets/images/plus.svg')}}" alt="plus"> </button>
                            </div>
                            <h3 class="accb_title"> {{ $sliderDataVal->slider_section_heading }}<br/> <span>{{ $sliderDataVal->slider_section_sub_heading }}</span> </h3>
                        </div>
                        {{--  --}}
                        <div class="accuracy_card_content_block" id="acfullContentBlock">
                            <div class="accb_top_row">
                                <p class="accbtr_title"> <span class="text"> {{ $sliderDataVal->slider_section_tagline }} </span> <span class="icon"> <img src="{{ asset('assets/storage/homeimages/').'/'.$sliderDataVal->slider_section_tagline_image }}" alt="" /> </span> </p>
                                <button class="btn close_btn"><img src="{{asset('assets/images/close.svg')}}" alt="close"></button>
                            </div>
                            <div class="accb_bottom_content">
                                <div class="ccbbc_left_block">
                                    <h3 class="accb_title"> {{ $sliderDataVal->slider_section_heading }}<br/> <span>{{ $sliderDataVal->slider_section_sub_heading }}</span> </h3>
                                    <p> {{ $sliderDataVal->slider_section_para }} </p>
                                    <a href="javascript:void(0)" class="btn shop_btn"> Shop </a>
                                </div>
                                <div class="ccbbc_right_block">
                                    @if(!empty($sliderDataVal->slider_section_reviewer_name))
                                    <div class="ccbbc_user_info">
                                        <div class="ui_block">
                                            @if(!empty($sliderDataVal->slider_section_reviewer_image))
                                            <div class="uiimg"> <img src="{{ asset('assets/storage/homeimages/').'/'.$sliderDataVal->slider_section_reviewer_image }}" alt="user" /> </div>
                                            @endif
                                            <div class="ui_info">
                                                <p class="ui_name"> {{ $sliderDataVal->slider_section_reviewer_name }} </p>
                                                <p class="uip"> {{ $sliderDataVal->slider_section_reviewer_info }} </p>
                                            </div>
                                        </div>
                                        <p class="text"> {{ $sliderDataVal->slider_section_review }} </p>
                                    </div>
                                    @endif
                                    {{--  --}}
                                    @if(!empty($sliderDataVal->slider_section_image))
                                    <div class="ccbbc_img">
                                        <img src="{{ asset('assets/storage/homeimages/').'/'.$sliderDataVal->slider_section_image }}" alt="img" />
                                    </div>
                                    @endif
                                </div>
                            </div>
                            {{--  --}}
                        </div>

                    </div>
                </div>
                @endforEach
                {{--  --}}
            </div>
        </div>

        <div class="next_prev_buttons" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
            <button class="prev_btn"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="img" /> </button>
            <button class="next_btn"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="img" /> </button>
        </div>
    </div>
</section>

{{--  --}}

<section class="about_ring_section" id="why-ring">
    <div class="ars_heading_row">
        <div class="page_container">
            <h3 class="ars_heading" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $aboutMainData->about_section_main_heading ?? ''  }} </h3>
            <div class="arshr_block">
                <h4 class="arshr_title" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $aboutMainData->about_section_heading ?? '' }} <br/> <span> {{ $aboutMainData->about_section_sub_heading ?? '' }} </span> </h4>
                <p class="arshr_desc" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $aboutMainData->about_section_para ?? '' }}</p>
            </div>
        </div>
    </div>
    {{--  --}}
    <div class="ars_content_block">
        <div class="page_container">
            <div class="ars_tab_block">
                <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @forEach($aboutContentData as $abtKey => $aboutContent)
                        @if($abtKey == 0)
                        <button class="nav-link active" id="v-pills-{{ $abtKey }}" data-bs-toggle="pill" data-bs-target="#v-pills-tab-{{ $abtKey }}" type="button" role="tab" aria-controls="v-pills-{{ $abtKey }}" aria-selected="true" data-aos="fade-right" data-aos-delay="300" data-aos-offset="0"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="" > {{ $aboutContent->about_section_content_title }}</button>
                        @else
                        <button class="nav-link" id="v-pills-{{ $abtKey }}" data-bs-toggle="pill" data-bs-target="#v-pills-tab-{{ $abtKey }}" type="button" role="tab" aria-controls="v-pills-{{ $abtKey }}" aria-selected="true" data-aos="fade-right" data-aos-delay="300" data-aos-offset="0"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="" > {{ $aboutContent->about_section_content_title }}</button>
                        @endif
                        @endforEach
                        {{-- <button class="nav-link" id="v-pills-everystep-tab" data-bs-toggle="pill" data-bs-target="#v-pills-everystep" type="button" role="tab" aria-controls="v-pills-everystep" aria-selected="false" data-aos="fade-right" data-aos-delay="350" data-aos-offset="0"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="" > Every step, smarter</button>
                        <button class="nav-link" id="v-pills-feeling-tab" data-bs-toggle="pill" data-bs-target="#v-pills-feeling" type="button" role="tab" aria-controls="v-pills-feeling" aria-selected="false" data-aos="fade-right" data-aos-delay="400" data-aos-offset="0"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="" > Not feeling your best</button>
                        <button class="nav-link" id="v-pills-unwind-tab" data-bs-toggle="pill" data-bs-target="#v-pills-unwind" type="button" role="tab" aria-controls="v-pills-unwind" aria-selected="false" data-aos="fade-right" data-aos-delay="450" data-aos-offset="0"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt="" > Unwind smarter</button>
                        <button class="nav-link" id="v-pills-celebrate-tab" data-bs-toggle="pill" data-bs-target="#v-pills-celebrate" type="button" role="tab" aria-controls="v-pills-celebrate" aria-selected="false" data-aos="fade-right" data-aos-delay="500" data-aos-offset="0"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}" alt=""> Celebrate smarter</button> --}}
                    </div>
                    <div class="tab-content" id="v-pills-tabContent">
                        @forEach($aboutContentData as $abtKey => $aboutContent)
                        <div class="tab-pane fade show @if($abtKey == 0) active @endif" id="v-pills-tab-{{ $abtKey }}" role="tabpanel" aria-labelledby="v-pills-{{ $abtKey }}">
                            <div class="about_ring_content_block" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                                <div class="arcb_image_block">
                                    <div class="arcb_image" data-aos="zoom-in" data-aos-delay="300" data-aos-offset="0">
                                        <img src="{{ asset('assets/storage/homeimages/').'/'.$aboutContent->about_section_content_img1 }}" alt="img" />
                                    </div>
                                    <div class="arcb_sec_image" data-aos="zoom-in" data-aos-delay="350" data-aos-offset="0">
                                        <img src="{{ asset('assets/storage/homeimages/').'/'.$aboutContent->about_section_content_img2 }}" alt="img" />
                                    </div>
                                </div>
                                @if(!empty($aboutContent->about_section_content_img3))
                                <div class="arcb_info arcb_info_left" data-aos="zoom-in" data-aos-delay="400" data-aos-offset="0">
                                    <img src="{{ asset('assets/storage/homeimages/').'/'.$aboutContent->about_section_content_img3 }}" alt="img" />
                                </div>
                                @endif

                                @if(!empty($aboutContent->about_section_content_img4))
                                <div class="arcb_info arcb_info_right" data-aos="zoom-in" data-aos-delay="500" data-aos-offset="0">
                                    <img src="{{ asset('assets/storage/homeimages/').'/'.$aboutContent->about_section_content_img4 }}" alt="img" />
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

<section class="new_era_section">
    <div class="page_container">
        <div class="common_heading_block">
            <h4 class="arshr_title"  data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $eraMainData->newera_section_heading }} <br> <span> {{ $eraMainData->newera_section_subheading }} </span> </h4>
            <p class="arshr_desc"  data-aos="fade-up" data-aos-delay="350" data-aos-offset="0"> {{ $eraMainData->newera_section_para }} </p>
        </div>
        <div class="era_content_block" data-aos="fade-up" data-aos-delay="500" data-aos-offset="0">
            @forEach($eraContentData as $eraContent)
            <div class="era_card" style="background:url('{{ asset('assets/storage/homeimages/').'/'.$eraContent->newera_section_content_background_image }}')">
                <div class="ec_top"> {{ $eraContent->newera_section_content_tagline }} </div>
                <div class="ec_bottom_block">
                    <div class="ecbb_content" data-aos="zoom-in" data-aos-delay="500" data-aos-offset="0">
                        <p>{{ $eraContent->newera_section_content_title }}</p>
                    </div>
                    <div class="ecbb_info">
                        <img src="{{ asset('assets/storage/homeimages/').'/'.$eraContent->newera_section_content_image }}" alt="img" />
                    </div>
                </div>
            </div>
            @endforEach
        </div>
    </div>
</section>

{{--  --}}

<section class="rign_bottom_section">
    <div class="page_container">
        <div class="common_heading_block">
            <h4 class="arshr_title" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $smartContentData->smart_section_heading }} <br> <span> {{ $smartContentData->smart_section_subheading }} </span> </h4>
            <p class="arshr_desc" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{ $smartContentData->smart_section_para }} </p>
        </div>
    </div>

    <div class="era_information_block">
        <div class="page_container">
            <div class="eraib_block" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                <h5 class="eraib_title"> {{ $smartContentData->smart_section_first_heading }} </h5>
                <p class="eraib_text"> {{ $smartContentData->smart_section_first_heading }} </p>
            </div>
             <div class="eraib_block" data-aos="fade-up" data-aos-delay="350" data-aos-offset="0">
                <h5 class="eraib_title"> {{ $smartContentData->smart_section_second_heading }} </h5>
                <p class="eraib_text"> {{ $smartContentData->smart_section_second_para }} </p>
            </div>
             <div class="eraib_block" data-aos="fade-up" data-aos-delay="400" data-aos-offset="0">
                <h5 class="eraib_title"> {{ $smartContentData->smart_section_third_heading }} </h5>
                <p class="eraib_text"> {{ $smartContentData->smart_section_third_para }} </p>
            </div>
        </div>
    </div>

    <div class="accurate_block">
        <div class="page_container">
            <div class="ab_content_block"  data-aos="fade-up" data-aos-delay="300" data-aos-offset="0" style="background:url('{{ asset('assets/storage/homeimages/').'/'.$accuracyContentData->accurate_section_background_image }}') no-repeat;background-size: cover;background-position: center;">
                <div class="abcb_heading_block"  data-aos="fade-up" data-aos-delay="500" data-aos-offset="0">
                    <h2 class=""> {{ $accuracyContentData->accurate_section_heading }} <span>{{ $accuracyContentData->accurate_section_sub_heading }}</span> </h2>
                    <p> {{ $accuracyContentData->accurate_section_para }}</p>
                </div>
                {{--  --}}

                <div class="ab_counter_block">
                    <div class="ab_count_box"  data-aos="zoom-in" data-aos-delay="300" data-aos-offset="0">
                        <p class="per_text"> {{ $accuracyContentData->accurate_section_first_heading }} </p>
                        <p class="ab_text"> <span> {{ $accuracyContentData->accurate_section_first_sub_heading }} </span> <span> {{ $accuracyContentData->accurate_section_first_para }} </span> </p>
                    </div>
                    <div class="ab_count_box" data-aos="zoom-in" data-aos-delay="350" data-aos-offset="0">
                        <p class="per_text"> {{ $accuracyContentData->accurate_section_second_heading }} </p>
                        <p class="ab_text"> <span> {{ $accuracyContentData->accurate_section_second_sub_heading }} </span> <span> {{ $accuracyContentData->accurate_section_second_para }} </span> </p>
                    </div>
                    <div class="ab_count_box" data-aos="zoom-in" data-aos-delay="400" data-aos-offset="0">
                        <p class="per_text"> {{ $accuracyContentData->accurate_section_third_heading }} </p>
                        <p class="ab_text"> <span> {{ $accuracyContentData->accurate_section_third_sub_heading }} </span> <span> {{ $accuracyContentData->accurate_section_third_para }} </span> </p>
                    </div>
                    <div class="ab_count_box" data-aos="zoom-in" data-aos-delay="450" data-aos-offset="0">
                        <p class="per_text"> {{ $accuracyContentData->accurate_section_fourth_heading }} </p>
                        <p class="ab_text"> <span> {{ $accuracyContentData->accurate_section_fourth_sub_heading }} </span> <span> {{ $accuracyContentData->accurate_section_fourth_para }} </span> </p>
                    </div>
                </div>
                {{--  --}}
            </div>
        </div>
    </div>

</section>

</div>


@endsection

@section('script')
<!-- Include Js -->

<script>

/* slider block */
document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.getElementById('accuracyCarousel');
  const track = carousel.querySelector('.accuracy_track');
  const cards = Array.from(track.querySelectorAll('.accuracy_card'));
  const prevBtn = document.querySelector('.next_prev_buttons .prev_btn');
  const nextBtn = document.querySelector('.next_prev_buttons .next_btn');

  // State
  let mode = 'grid';          // 'grid' (3-up feel) or 'focus' (expanded 1.2 look)
  let activeIndex = 0;        // which card we consider "active" for prev/next
  let scrollTO;

  function isFullyInViewX(containerEl, el, margin) {
    if (!containerEl || !el) return false;
    const m = typeof margin === 'number' ? margin : 8;
    const c = containerEl.getBoundingClientRect();
    const r = el.getBoundingClientRect();
    return (r.left >= c.left + m) && (r.right <= c.right - m);
    }

    // If expanded card's close button is clipped (or the card is), click Next
    function ensureExpandedVisible() {
    const expanded = carousel.querySelector('.accuracy_card.is-expanded');
    if (!expanded) return;
    const closeBtn = expanded.querySelector('.close_btn');
    const targetEl = closeBtn || expanded; // prefer checking close button itself
    if (!isFullyInViewX(carousel, targetEl, 8)) {
        if (nextBtn) {

        nextBtn.click();
        // after scroll, try to re-center the expanded card
        setTimeout(() => {
            expanded.scrollIntoView({
            behavior: 'smooth',
            inline: (mode === 'focus') ? 'center' : 'start',
            block: 'nearest'
            });
        }, 60);
        }
    }
    }

  // Helpers
  const collapseAll = () => {
    cards.forEach(card => card.classList.remove('is-expanded'));
  };

  const isAnyExpanded = () => cards.some(c => c.classList.contains('is-expanded'));

  const setMode = (newMode) => {
    if (newMode === mode) return;
    mode = newMode;
    if (mode === 'focus') {
      carousel.classList.add('mode-focus');
    } else {
      carousel.classList.remove('mode-focus');
    }
  };

  // Scroll helpers
  const scrollToIndex = (index, behavior = 'smooth') => {
    const card = cards[index];
    if (!card) return;

    // In grid mode, align card to start; in focus mode, center it.
    card.scrollIntoView({
      behavior,
      inline: (mode === 'focus') ? 'center' : 'start',
      block: 'nearest'
    });
  };

  // Derive visible index based on scroll position (approx)
  const getNearestIndex = () => {
    const { left: cLeft, width: cWidth } = carousel.getBoundingClientRect();
    let best = 0, bestDist = Infinity;

    cards.forEach((card, i) => {
      const rect = card.getBoundingClientRect();
      const cardCenter = rect.left + rect.width / 2;
      const ref = (mode === 'focus') ? (cLeft + cWidth / 2) : cLeft; // center vs left
      const dist = Math.abs(cardCenter - ref);
      if (dist < bestDist) { bestDist = dist; best = i; }
    });
    return best;
  };

  // Wire up Show/Close per card
  cards.forEach((card, index) => {
    const inner = card.querySelector('.accuracy_card_inner');
    const blocks = card.querySelectorAll('.accuracy_card_content_block');
    if (!inner || blocks.length < 2) return;

    const shortBlock = blocks[0];
    const fullBlock  = blocks[1];
    const showBtn = shortBlock.querySelector('.show_btn');
    const closeBtn = fullBlock.querySelector('.close_btn');

    if (showBtn) {
      showBtn.addEventListener('click', () => {
        // Only one expanded
        collapseAll();
        card.classList.add('is-expanded');
        setMode('focus');
        activeIndex = index;
        // Center the expanded card
        scrollToIndex(activeIndex);
        setTimeout(() => {
            ensureExpandedVisible(); // <--- add this
        },500)
        // restart the CSS animation every open
        fullBlock.classList.remove("play-right");
        void fullBlock.offsetWidth;          // force reflow
        fullBlock.classList.add("play-right");
      });
    }

    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        card.classList.remove('is-expanded');
        fullBlock.classList.remove("play-right");
        // If none expanded, back to grid mode
        if (!isAnyExpanded()) {
          setMode('grid');
        }
        // Keep it in view
        activeIndex = index;
        scrollToIndex(activeIndex);
      });
    }
  });

  // Prev / Next
  prevBtn?.addEventListener('click', () => {
    // Update activeIndex relative to current view
    activeIndex = getNearestIndex();
    const step = (mode === 'focus') ? 1 : 3; // grid feels like page of 3, focus moves 1
    activeIndex = Math.max(0, activeIndex - step);
    scrollToIndex(activeIndex);
  });

  nextBtn?.addEventListener('click', () => {
    activeIndex = getNearestIndex();
    const step = (mode === 'focus') ? 1 : 3;
    activeIndex = Math.min(cards.length - 1, activeIndex + step);
    scrollToIndex(activeIndex);
  });

  // Keep activeIndex roughly in sync on user scroll
  carousel.addEventListener('scroll', () => {
    clearTimeout(scrollTO);
    scrollTO = setTimeout(() => { activeIndex = getNearestIndex(); }, 500);
  });
});
/*  */

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.era_content_block').forEach(row => {
    const cards = row.querySelectorAll('.era_card');

    function clear() {
      row.classList.remove('hover-1','hover-2','hover-3');
    }

    cards.forEach((card, idx) => {
      card.addEventListener('mouseenter', () => {
        clear();
        row.classList.add('hover-' + (idx + 1)); // 1|2|3
      });
    });

    row.addEventListener('mouseleave', clear);
  });
});

</script>

@endsection
