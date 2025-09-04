@if(!empty($lines))
    @foreach($lines as $l=>$line)
        <div class="olb_block" data-product="{{$line['key']}}"  data-aos="fade-up" data-aos-delay="50" data-aos-offset="0">
            <div class="olb_info">
                <div class="olb_img"> <img src="{{asset('assets/storage/products/'.$line['product_image'])}}" alt="img"> </div>
                <div class="olb_detail">
                    <p class="p_name"> {{$line['product_name']}} </p>
                    <p class="p_size"> Size: {{$line['size']}} </p>
                    <p class="p_color"> Color: {{$line['color']}} </p>
                    <p class="p_amount"> ${{$line['unit_cents']}} </p>
                </div>
            </div>
            <div class="product_counter_block">
                <div class="qty-stepper" data-min="1" data-max="99">
                    <button class="qs-btn" aria-label="Decrease">−</button>
                        <span class="qs-value" role="status" aria-live="polite">{{$line['qty']}}</span>
                    <button class="qs-btn" aria-label="Increase">+</button>
                </div>
            </div>
            {{--  --}}
            <button type="button" class="delete_btn" onclick="removeFromCart(`{{$line['key']}}`)"> <img src="{{asset('assets/images/delete.svg')}}" alt="delete"> </button>
        </div>
    @endforeach
@else
<div class="empty_cart_block">
    <div class="ecb_img"> <img src="{{asset('assets/images/empty-cart.png')}}" alt="tabler_arrow"> </div>
    <h3> Your Cart is Empty! </h3>
    <a href="{{ route('frontend.static.shop') }}" class="btn place_order_btn"> Return to Shop </a>
</div>
@endif
