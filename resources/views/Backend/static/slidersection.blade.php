@extends('Backend.layouts.master')

@section('css')
<style>
table svg { width: 14px; }
</style>
@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') {{ Session::get('PageHeading'); }} @endslot
    @slot('li_1')  Home Page  @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('backend.removeslidersection') }}" onsubmit="return checkSelects('Form')" id="Form">
                    @csrf
                    {{--  ADD Edit Button  --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('backend.manageslidersection') }}"><button type="button" class="btn btn-primary waves-effect waves-light add-section">
                            Add Slider Section
                        </button></a>

                        <button type="submit" class="btn btn-danger waves-effect waves-light">
                            Delete Slider Section
                        </button>
                    </div>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th> S No.</th>
                                <th style="text-align: center;"><input class="form-check-input group-checkable" id="deletebcchk" name="deletebcchk" type="checkbox" id="formCheck"></th>
                                <th> Tagline</th>
                                <th> Heading</th>
                                <th> Sub Heading</th>
                                <th> Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                <form>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

<script>
	var slength=[1,5];
    $(document).ready(function(){
        $("#datatable").DataTable({
            "bProcessing":false,
            "bServerSide":false,
            "bAutoWidth":true,
            "responsive":true,
            "responsive": {
			    "details": {
                    renderer: function(api, rowIdx, columns){
                        var $row_details = $.fn.DataTable.Responsive.defaults.details.renderer(api, rowIdx, columns);
                        return $row_details;
                    }
                }
            },
            "bInfo":false,
            "pagingType":"full_numbers",
            //"order":[[2,"desc"]],
            "aoColumnDefs":[
                {"bSortable":false,"aTargets":slength},
                {"bSearchable":false,"aTargets":slength}
            ],
            "sAjaxSource": "{{ route('backend.getslidersection')}}",
            "iDisplayLength":10,
            "aLengthMenu":[[10,50,100],[10,50,100]],
            "fnDrawCallback":function(oSettings){
                if($(".deimg").length>0){
                    $('.deimg').initial();
                }
            },
        });
    });
</script>

@endsection
