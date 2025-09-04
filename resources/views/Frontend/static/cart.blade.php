@extends('Frontend.layouts.master')

@section('css')
<!-- Include css -->
<style>
    .home_background {background: url('{{asset('assets/images/checkshadow.jpg')}}') no-repeat !important; background-position: center !important; background-size: cover !important; }
</style>
@endsection

@section('content')

<div class="home_background">

    <section class="cart_product_section">
        <div class="page_container">
            <div class="my_order_block">
                <div class="review_order"  data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                    <div class="back_product"><a href="{{route('frontend.static.shop')}}"> <img src="{{asset('assets/images/tabler_arrow-back.svg')}}" alt="tabler_arrow"> Continue Shopping </a> </div>
                    <h2 class="cps_title"> Review Your Order </h2>

                    <div class="order_list_block">

                    </div>
                    {{--  --}}

                    {{--  --}}
                    <div class="benifit_block">
                        <div class="bb_box"  data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                            <img src="{{asset('assets/images/streamline-plump.svg')}}" alt="bbimg" />
                            <span> Free shipping </span>
                        </div>
                        <div class="bb_box"  data-aos="fade-up" data-aos-delay="350" data-aos-offset="0">
                            <img src="{{asset('assets/images/hugeicons.svg')}}" alt="bbimg" />
                            <span> 30-day returns </span>
                        </div>
                        <div class="bb_box"  data-aos="fade-up" data-aos-delay="400" data-aos-offset="0">
                            <img src="{{asset('assets/images/streamline-flex.svg')}}" alt="bbimg" />
                            <span> 1-year warranty </span>
                        </div>
                        <div class="bb_box"  data-aos="fade-up" data-aos-delay="450" data-aos-offset="0">
                            <img src="{{asset('assets/images/material-symbols.svg')}}" alt="bbimg" />
                            <span> Charger included </span>
                        </div>
                    </div>
                    {{--  --}}
                </div>
                {{--  --}}
                <div class="order_summary_block"  data-aos="fade-up" data-aos-delay="300" data-aos-offset="0">
                    <div class="mark_gift_block"  data-aos="fade-up" data-aos-delay="350" data-aos-offset="0">
                        <h2 class="cps_title"> Order Summary </h2>
                        <div class="gift_block">
                            <label class="checkbox" > Mark as Gift
                                <input type="checkbox" name="" value="" id="change_pass" >
                                <span class="checkmark" ></span>
                            </label>
                            <img src="{{asset('assets/images/gift.svg')}}" alt="gift" />
                        </div>
                    </div>
                    <div class="pay_and_subtotal"  data-aos="fade-up" data-aos-delay="400" data-aos-offset="0">
                        <div class="subtotal">
                            <p> Subtotal <span class="d-block"> Local taxes and fees may apply on delivery. </span> </p>
                            <p class="amount" id="total_cost"> $ 0.00 USD </p>
                        </div>
                        <button type="button" class="btn place_order_btn"> Place Your Order </button>
                        <div class="pay_type_block">
                            <img src="{{asset('assets/images/paytypeimg.png')}}" alt="pay" />
                        </div>
                    </div>
                    {{--  --}}
                </div>
                {{-- order_summary_block --}}
            </div>
        </div>
    </section>

</section>

@endsection

@section('script')
<!-- Include Js -->
<script>

    // Update qty
    const getCartItems = async () => {
        await fetch(`{{route('cart.get')}}`, {
            method: 'GET',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}
        }).then(res => res.json()).then(data => {
            $('#total_cost').text('$ '+data.total_cents+' USD')
            $('.order_list_block').html(data.items);
            $('.card_count').text(data.count||'');
        })
    }

    const updateQty = async (key, qty) => {

        await fetch(`{{route('cart.update')}}/` + encodeURIComponent(key), {
            method: 'PATCH',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify({ qty })
        }).then(res => res.json()).then(data => {
            getCartItems();
        })
    }

    const removeFromCart = async (key) => {
        await fetch(`{{route('cart.delete')}}/` + encodeURIComponent(key), {
            method: 'DELETE',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
        }).then(res => res.json()).then(data => {
             getCartItems();
        })

        getCartItems();
    }

    getCartItems();

    $(document).ready(function () {

        function setStepperValue($stepper, newVal) {

            const min = parseInt($stepper.data('min')) || 0;
            const max = parseInt($stepper.data('max')) || Infinity;
            const $decBtn = $stepper.children().eq(0);
            const $valueEl = $stepper.children().eq(1);
            const $incBtn = $stepper.children().eq(2);

            const val = Math.min(max, Math.max(min, newVal));

            updateQty($stepper.parents('div.olb_block').data('product'), val);

            $valueEl.text(val);

            $decBtn.prop('disabled', val <= min);
            $incBtn.prop('disabled', val >= max);
        }

        function getStepperValue($stepper) {
            const $valueEl = $stepper.children().eq(1);
            return parseInt($.trim($valueEl.text())) || 0;
        }

        // Initialize steppers
        function initStepper($stepper) {
            const min = parseInt($stepper.data('min')) || 0;
            setStepperValue($stepper, getStepperValue($stepper) || min);
            $stepper.attr('tabindex', 0);
        }

        $('.qty-stepper').each(function () {
            initStepper($(this));
        });

        // Delegated click: Decrement
        $(document).on('click', '.qty-stepper > :first-child', function () {
            const $stepper = $(this).closest('.qty-stepper');
            setStepperValue($stepper, getStepperValue($stepper) - 1);
        });

        // Delegated click: Increment
        $(document).on('click', '.qty-stepper > :last-child', function () {
            const $stepper = $(this).closest('.qty-stepper');
            setStepperValue($stepper, getStepperValue($stepper) + 1);
        });

        // Keyboard support
        $(document).on('keydown', '.qty-stepper', function (e) {
            const $stepper = $(this);
            if (['ArrowLeft', 'ArrowDown', '-'].includes(e.key)) {
            e.preventDefault();
            setStepperValue($stepper, getStepperValue($stepper) - 1);
            }
            if (['ArrowRight', 'ArrowUp', '+', '='].includes(e.key)) {
            e.preventDefault();
            setStepperValue($stepper, getStepperValue($stepper) + 1);
            }
        });

    });
</script>

@endsection
