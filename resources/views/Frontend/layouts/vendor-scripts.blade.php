<!-- JAVASCRIPT -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/jquery-validation/dist/jquery.validate.js')}}"></script>
<script src="{{ URL::asset('assets/libs/jquery-validation/dist/additional-methods.js')}}"></script>
<!-- alertifyjs js -->
<script src="{{ URL::asset('/assets/libs/alertifyjs/build/alertify.min.js') }}"></script>
<!-- Sweet Alerts js -->
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Block UI js -->
<script src="{{ URL::asset('assets/frontjs/jquery.blockUI.js')}}"></script>

@yield('script')

<!-- App js -->
<script src="{{ URL::asset('assets/frontjs/slick.min.js')}}" defer></script>
<script src="{{ URL::asset('assets/frontjs/aos.js')}}" defer></script>
<script src="{{ URL::asset('assets/frontjs/app.js')}}"></script>

<script>
    @if(Session::has('success'))
        showNotify('success', `{{ Session::get("success") }}`);
        @php Session::forget('success'); @endphp
    @endif

    @if(Session::has('warning'))
        showNotify('warning', `{{ Session::get("warning") }}`);
        @php Session::forget('warning'); @endphp
    @endif

    @if(Session::has('error'))
        showNotify('error', `{{ Session::get("error") }}`);
        @php Session::forget('error'); @endphp
    @endif

    @if(Session::has('info'))
        showNotify('info', `{{ Session::get("info") }}`);
        @php Session::forget('info'); @endphp
    @endif

    @if(!empty($errors))
        @foreach ($errors->all() as $error)
            showNotify('error', `{{ $error }}`);
        @endforeach
    @endif
</script>

<script>
    async function updateCartCount() {
        try {
            const res = await fetch(`{{route('cart.count')}}`, { credentials: 'same-origin' });
            const data = await res.json();
            if(data.count > 0){
                $('.card_count').text(data.count || 0);
            }
        } catch (e) {
            console.error('Error fetching cart count', e);
        }
    }

    document.addEventListener('DOMContentLoaded', updateCartCount);
</script>

@yield('script-bottom')