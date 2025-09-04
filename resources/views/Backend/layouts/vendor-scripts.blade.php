<!-- JAVASCRIPT -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/jquery-validation/dist/jquery.validate.js')}}"></script>
<script src="{{ URL::asset('assets/libs/jquery-validation/dist/additional-methods.js')}}"></script>
<!-- pace js -->
<script src="{{ URL::asset('assets/libs/pace-js/pace.min.js')}}"></script>
<!-- choices js -->
<script src="{{ URL::asset('/assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<!-- ckeditor -->
<script src="{{ URL::asset('/assets/libs/@ckeditor/ckeditor/ckeditor.js') }}"></script>
<!-- Required datatable js -->
<script src="{{ URL::asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ URL::asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<!-- alertifyjs js -->
<script src="{{ URL::asset('/assets/libs/alertifyjs/build/alertify.min.js') }}"></script>
<!-- Sweet Alerts js -->
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Block UI js -->
<script src="{{ URL::asset('assets/frontjs/jquery.blockUI.js')}}"></script>

<!-- Cookies Jquery js -->
<script  src="{{ URL::asset('/assets/libs/js-cookie/dist/js.cookie.js') }}"></script>

@yield('script')

<script>

$('#mode-setting-btn').on('click', function (e) {
	var body = document.getElementsByTagName("body")[0];
    //alert(body.getAttribute("data-layout-mode"));
	if(body.hasAttribute("data-layout-mode") && body.getAttribute("data-layout-mode") == "dark") {
		
		$.ajax({
			url: "{{ route('ajax.request') }}",
			dataType: "json",
			data: {theme:'light'},
			success: function(data) {
				if(data.success){
			  
				document.body.setAttribute('data-layout-mode', 'light');
				document.body.setAttribute('data-topbar', 'light');
				document.body.setAttribute('data-sidebar', 'light');
				(body.hasAttribute("data-layout") && body.getAttribute("data-layout") == "horizontal") ? '' : document.body.setAttribute('data-sidebar', 'light');
				
				}
			}
		});

		
	} else {
		
		$.ajax({
			url: "{{ route('ajax.request') }}",
			dataType: "json",
			data: {theme:'dark'},
			success: function(data) {
				if(data.success){
				document.body.setAttribute('data-layout-mode', 'dark');
				document.body.setAttribute('data-topbar', 'dark');
				document.body.setAttribute('data-sidebar', 'dark');
				(body.hasAttribute("data-layout") && body.getAttribute("data-layout") == "horizontal") ? '' : document.body.setAttribute('data-sidebar', 'dark');
				
				}
			}
		});
	}
});	
</script>

<!-- App js -->
<script src="{{ URL::asset('assets/js/app.js')}}"></script>

<!-- Backend js -->
<script src="{{ URL::asset('assets/backendjs/app.js')}}"></script>

<script>
    $(document).on('click', '#vertical-menu-btn', function() {
        var navMode = $('body').attr('data-sidebar-size');
        $.ajax({
            url: "{{ route('ajax.navrequest') }}",
            dataType: "json",
            data: {navMode:navMode},
            success: function(data) {
                if(data.success){
            
                }
            }
        });

        Cookies.set("navMode", navMode, {
            path: '/',
            secure: true,
            sameSite: 'strict',
        });
    });


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
@yield('script-bottom')