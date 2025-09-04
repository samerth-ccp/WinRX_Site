@extends('Backend.layouts.master')

@section('css')
@endsection

@section('content')

@component('Backend.components.breadcrumb')
    @slot('li_1') <a href="{{ route('sizes') }}" >Size List<a> @endslot
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-5">
                <form method="post" class="needs-validation" id="manage_form"  enctype='multipart/form-data' novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="size">Size Number</label>
                                <input type="number" class="form-control required onlyNumbers" name="size" id="size" placeholder="Enter Size Number..." value="{{$size->size??''}}" min="1" >
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $('#manage_form').validate();
        });
    </script>
@endsection