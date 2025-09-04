@extends('Frontend.layouts.master')

@section('endCss')
<link href="{{ URL::asset('/assets/libs/croppie/croppie.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('css')
<!-- Include css -->

@endsection

@section('content')
    <div class="container">
        <div class="main-body mb-5">
            <a href="{{ route('stripe.connectuser') }}" class="btn btn-primary">Stripe Connect</i></a>
            <form method="post" id="profile" enctype="multipart/form-data"> 
                @csrf
                <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="{{ getUserImage($userData->image) }}" alt="Admin" class="rounded-circle" width="150">

                                    <div class="mt-3">
                                        <h4>{{ $userData->name }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Image</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <div class="input-group"> 
                                        <input type="file" class="form-control " id="image" name="image" autofocus="">
                                        <label class="input-group-text" style="line-height: 0.5;margin-bottom: 0;" for="image">Upload</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Full Name*</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input class="form-control required " type="text" name="name" id="name" value="{{ $userData->name }}">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email*</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <input class="form-control required email" type="text" name="email" id="email" value="{{ $userData->email }}" {{ ($userData->signup_type=='social')?'readonly':'' }}>
                                </div>
                            </div>

                            <hr> 
                            <div class="row" id="emailPassword" style="display:none;">
                        
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Password</h6>
                                </div>
                                <div class="col-sm-9 text-secondary" >
                                    <input class="form-control required" type="password" name="password" value="">
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row gutters-sm {{ ($userData->signup_type=='social')?'d-none':'' }}">
                <div class="col-md-4 mb-3">
                </div>
                <div class="col-md-8" id="changePassword">
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="post" action="{{ route('frontend.changepassword') }}" class="needs-validation" id="passwordForm"  enctype='multipart/form-data' novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="current_password">Current Password</label>
                                            <input type="password" class="form-control required" name="current_password" id="current_password" placeholder="Current Password" value="" maxlength="30" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="password">New Password</label>
                                            <input type="password" class="form-control required passcheck" name="password" id="password" placeholder="New Password" value="" maxlength="30" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="confirm_password">Confirm Password</label>
                                            <input type="password" class="form-control required" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="" maxlength="30" >
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- image model -->
    <!-- Modal -->
    <div class="modal fade" id="uploadimageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" style="justify-content: center;display: flex;">
                        <div class="loader_modal text-center">
                            <div class="spinner-border" role="status">
                              <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="croppie_area d-none">
                            <div id="image_demo" style="width:250px; margin-top:30px:"></div>
                        </div>
                    </div>
                    <div class="col-12 " style="padding-bottom:20px; color: #a2a2a2;justify-content: center;display: flex;">
                        <div>Crop & Upload Image</div>
                        <div>Please upload a headshot against a solid background</div>
                    </div>
                    <div class="col-12 text-center" style="padding-bottom:20px;justify-content: center;display: flex;">
                        <button class="btn btn-primary crop_image" data-inprocess="Processing..." data-default="SUBMIT" style="max-width: 200px; width: 100%;">SUBMIT</button>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
    
@section('script')
<!-- Include Js -->
<script src="{{ URL::asset('/assets/libs/croppie/exif.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/croppie/croppie.js') }}"></script>

<script>
    $(document).on('keyup','#email',function(){
        var current_email = '{{ $userData->email }}';
        var change_email = $(this).val();

        if(current_email!=change_email){
            $('#emailPassword').show();
        }else{
            $('#emailPassword').hide();
        }
    });

    var $image_crop = $('#image_demo').croppie({
      enableExif: true,
      viewport: {
        width:200,
        height:200,
        type:'square' //circle
      },
      boundary:{
        width:250,
        height:250
      },
      enableOrientation:true
    });

    $('#image').on('change',function(){
        var fileTypes = ['jpg', 'jpeg', 'png'];
        var reader = new FileReader();
        var file = this.files[0]; // Get your file here
         
        var fileExt = file.type.split('/')[1]; // Get the file extension
        if (fileTypes.indexOf(fileExt) !== -1) { 
            var trueImage = false;
            //check image is valid or not on server side.
            var formdata = new FormData();
            formdata.append('file',file);
            formdata.append('_token',$('meta[name="csrf-token"]').attr('content'));
            
            $.ajax({
                url: "{{ route('frontend.checkimage') }}",
                type: "POST",
                data:formdata,
                contentType: false,  
                processData: false,  
                success:function(response){
                    if(response.success){


                        $('#uploadimageModal').modal('toggle');                                
                        
                        setTimeout(function(){ 
                            reader.onloadend = function (evt) {
                            $image_crop.croppie('bind', {
                                url: evt.target.result,
                                orientation: 1,
                            }).then(function(){ });
                            }
                            reader.readAsDataURL(file);  

                            $('.loader_modal').hide();
                            $('.croppie_area').removeClass('d-none');

                        }, 1000);



                    }else{
                        html=`<i class="mdi mdi-block-helper label-icon" ></i> <strong> Error </strong> - Your uploaded file is not valid! `;
                        alertify.error(html);
                    }                         
                }
            });
        }else{
            html=`<i class="mdi mdi-block-helper label-icon" ></i> <strong> Error </strong> - Your file is not valid! `;
            alertify.error(html);
            return false;   
        }
    });

    $('.crop_image').click(function(event){
        freezeButton('.crop_image',$('.crop_image').data('inprocess'),'disabled');
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response){
            $.ajax({
                url:"{{route('frontend.uploadimage')}}",
                type: "POST",
                data:{"image": response,_token:$('meta[name="csrf-token"]').attr('content'),check_image:'contactProfileimage'},
                success:function(response)
                {
                    freezeButton('.crop_image',$('.crop_image').data('default'),'active');
                    if(response.success){
                        $('.rounded-circle').attr('src',response.image);
                    }
                    if(response.error){
                        html=`<i class="mdi mdi-block-helper label-icon" ></i> <strong> Error </strong> - Something Went Wrong! `;
                        alertify.error(html);
                    }
                    
                    $('#uploadimageModal').modal('hide');

                    $('.loader_modal').show();
                    $('.croppie_area').addClass('d-none');
                    
                }
            });
        })
    });
</script>
@endsection