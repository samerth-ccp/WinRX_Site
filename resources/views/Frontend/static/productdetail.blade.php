@extends('Frontend.layouts.master')

@section('css')
<!-- Include css -->
<style>
    .slick-slide { padding: 0px 10px; }
</style>
@endsection

@section('content')

<div class="home_background">
    <section class="product_information_section">
        <div class="page_container">
            <div class="pis_section">
                <div class="pis_product_image" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> <img src="{{asset('assets/storage/products/'.$product->product_image)}}" alt="alt" /> </div>
                <div class="pis_info">
                    <h1 class="pis_name" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{$product->product_name}}</h1>
                    <p class="pis_desc" data-aos="fade-up" data-aos-delay="350" data-aos-offset="0"> {{$product->product_description}} </p>
                    <div class="color_block" data-aos="fade-up" data-aos-delay="400" data-aos-offset="0">
                        <label for="">Color:</label>
                        <div class="choose_color">

                            @foreach($product->color as $key=>$color)
                                <div class="color_box">
                                    <input class="checkbox-tools" type="radio" name="color" id="color_{{$color->color_id}}" onchange="changeColor(this)" value="{{$color->color_id}}" {{$key==0?'checked':''}}>
                                    <label for="color_{{$color->color_id}}">
                                        <span class="color" style="background: {{$color->color_code}};"></span>
                                        {{$color->color_name}}
                                    </label>
                                </div>
                            @endforeach


                        </div>
                    </div>
                    {{--  --}}
                    <div class="size_block" data-aos="fade-up" data-aos-delay="450" data-aos-offset="0">
                        <label for="">Size:</label>
                        <div class="choose_size">
                            @foreach($product->size as $key=>$size)
                                <div class="size_box">
                                    <input class="size-tools" type="radio" name="size" id="ring{{$size->size}}" value="{{$size->size}}" {{$key==0?'checked':''}}>
                                    <label for="ring{{$size->size}}"> <span> {{$size->size}} </span> </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{--  --}}
                    <div class="pis_bottom_row" data-aos="fade-up" data-aos-delay="500" data-aos-offset="0">
                        @php
                         $x = 0;
                        @endphp
                        @foreach($product->product_price as $p=>$price)
                            <p class="price color_price_list color_price_{{$p}}" style="display:{{$x>0?'none':'block'}}"> ${{number_format($price, 2)}}</p>
                            @php
                                $x++;
                            @endphp
                        @endforeach
                        <button class="btn add_cart_btn" onclick="addToCart(this)"> Add to Cart </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--  --}}

    @if(!empty($product->product_specification))
        <section class="psp_made_section tech_inside_section">
            <div class="page_container">
                <h3 class="tis_heading" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> Where sleek meets smart—</br> <span> made for you. </span> </h3>
                <div class="tis_card_block">

                    <div class="tis_card_slider">
                        @foreach($product->product_specification as $k=>$specification)
                            <div class="">
                                <div class="tis_card ms-auto me-auto" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                                    <div class="tis_card_img">
                                        <img src="{{asset('assets/storage/products/'.$specification['image'])}}" alt="tis" />
                                    </div>
                                    <div class="tis_card_content">
                                        <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="300" data-aos-offset="0">
                                            <p class="mb-0"> {!!$specification['text']!!} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="col-lg-4 col-sm-6">
                            <div class="tis_card" data-aos="fade-up" data-aos-delay="350" data-aos-offset="0">
                                <div class="tis_card_img">
                                    <img src="{{asset('assets/images/tisimg2.png')}}" alt="tis" />
                                </div>
                                <div class="tis_card_content">
                                    <div class="tiscc_box" data-aos="zoom-in" data-aos-delay="350" data-aos-offset="0">
                                        <p class="mb-0"> <strong>Titanium strength.</strong> Hidden sensors. Comfort around the clock. </p>
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
                                        <p class="mb-0"> Over a <strong>week of battery life,</strong> designed to keep up with you. </p>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                </div>
            </div>
        </section>
    @endif
    {{--  --}}

    <section class="helpful_answer_section">
        @if(!empty($product->product_helpful_answer))
            <div class="page_container">
                <div class="common_heading_block">
                    <h4 class="arshr_title" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> Helpful Answers </h4>
                    <p class="arshr_desc" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> {{$product->product_helpful_answer}} </p>
                </div>
            </div>
        @endif
        {{--  --}}
        <div class="has_content_block">
            <div class="page_container">
                <div class="row">
                    <div class="col-lg-6">
                        @if(!empty($product->product_faqs))
                            <div class="accordion" id="accordionExample">
                                @foreach($product->product_faqs as $k=>$faq)
                                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                                        <h2 class="accordion-header" id="heading{{$k}}">
                                        <button class="accordion-button {{$k>0?'collapsed':''}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$k}}" aria-expanded="true" aria-controls="collapse{{$k}}">
                                            {{$faq['question']}}
                                        </button>
                                        </h2>
                                        <div id="collapse{{$k}}" class="accordion-collapse collapse {{$k==0?'show':''}}" aria-labelledby="heading{{$k}}" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p>{!! nl2br($faq['answer']) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    {{--  --}}
                    <div class="col-lg-6">
                        <div class="has_img_block" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                            <img src="{{asset('assets/storage/products/'.$product->product_faq_image)}}" alt="hasimg" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--  --}}

    <div class="what_inbox_section">
        <div class="page_container">
            <h2 class="wis_heading" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> All the Tech Inside </h2>
            <div class="wis_content_block">
                <div class="wis_ring ring-flip" > <img src="{{asset('assets/images/banner_ring.png')}}" alt="img" /> </div>
                <div class="what_block" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                    <h3 class="wis_title"> What’s In-box </h3>
                    <div class="wis_list">
                        <ul data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                            @foreach(explode(',',$product->product_in_box) as $value)
                                <li>{{$value}}</li>
                            @endforeach
                        </ul>
                    </div>
                    {{--  --}}
                    <div class="wisImg" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                        <img src="{{asset('assets/storage/products/'.$product->product_in_box_image)}}" alt="alt" />
                    </div>
                </div>
                {{--  --}}
                {!! $product->product_tech_insights !!}
                {{-- <div class="tech_block">
                    <h3 class="wis_title" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0"> Tech Insights </h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="techcard_outer" data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                                <div class="techcard_inner">
                                    <div class="tci_top_row">
                                        <img src="{{asset('assets/images/arcticons_pedometer.svg')}}" alt="techicon" />
                                        <span> Health Tracking </span>
                                    </div>
                                    <p>Blood Oxygen Monitoring — Red and infrared LEDs track SpO₂ levels.</p>
                                    <p>24/7 Heart & Respiration Tracking — Green and infrared LEDs measure heart rate, heart rate variability, and breathing patterns.</p>
                                    <p>Temperature Trends — A digital sensor monitors variations in body temperature.</p>
                                    <p>Activity Tracking — An accelerometer records movement and activity around the clock.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="techcard_outer" data-aos="fade-up" data-aos-delay="350" data-aos-offset="0">
                                <div class="techcard_inner">
                                    <div class="tci_top_row">
                                        <img src="{{asset('assets/images/arcticons_zendesk-support.svg')}}" alt="techicon" />
                                        <span> Supported Platforms </span>
                                    </div>
                                    <p>Multi-Language App — Available in Czech, Danish, Dutch, English, Finnish, French, German, Italian, Japanese, Norwegian, Spanish, and Swedish.</p>
                                    <p>Flexible Units — Supports both metric and imperial measurements.<br/> Seamless Integrations — Connects with 40+ popular health and fitness apps.</p>
                                    <p>Cross-Platform — Works on both iOS and Android devices.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="techcard_outer" data-aos="fade-up" data-aos-delay="400" data-aos-offset="0">
                                <div class="techcard_inner">
                                    <div class="tci_top_row">
                                        <img src="{{asset('assets/images/token_ring.svg')}}" alt="techicon" />
                                        <span> Measurements </span>
                                    </div>
                                    <p>Width: 7.9 mm</p>
                                    <p>Thickness: 2.9 mm — about the same as a wedding band</p>
                                    <p>Weight: 3.3 – 5.2 g, depending on ring size</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="techcard_outer" data-aos="fade-up" data-aos-delay="450" data-aos-offset="0">
                                <div class="techcard_inner">
                                    <div class="tci_top_row">
                                        <img src="{{asset('assets/images/symbols_mobile-charge-outline.svg')}}" alt="techicon" />
                                        <span> All-Day Power </span>
                                    </div>
                                    <p>Long-Lasting Power — Up to 8 days on a single charge.</p>
                                    <p>Quick Recharge — Charges in just 20–80 minutes, depending on battery level.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>


</div>

@endsection

@section('script')
<!-- Include Js -->
<script>
    $(document).ready(function () {
        $('.tech_block').removeAttr('style');
    })

    const changeColor = (that) => {
        $('.color_price_list').each(function () {
            if($(this).hasClass('color_price_'+that.value)){
                $(this).css('display', 'block');
            }else{
                $(this).css('display', 'none');
            }
        });
    }

    $(document).ready(function() {
        $('.tis_card_slider').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            //arrows: false,
            prevArrow: '<button type="button" class="slick-custom-arrow slick-prev"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}"> </button>',
            nextArrow: '<button type="button" class="slick-custom-arrow slick-next"> <img src="{{asset('assets/images/solar_arrow_active.svg')}}"> </button>',
            responsive: [
                {
                breakpoint: 1400,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
                },
                {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
                },
                {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
                },
                {
                breakpoint: 750,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
                },
                {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
                }
            ]
        });
    });

    const addToCart = async () => {
        const resp = await fetch(`{{route('cart.add')}}`, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify({ product_id: {{$product->product_id}}, qty: 1, meta: { size:$('[name="size"]:checked').val(), color:$('[name="color"]:checked').val() } })
        }).then(response => response.json()).then(data => {
            showNotify('success',data.message);
            $('.card_count').text(Object.keys(data.data.items).length);
            window.location.href = "{{route('frontend.static.cart')}}";
        });

    }


</script>

@endsection
