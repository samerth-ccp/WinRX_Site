@extends('Backend.layouts.master')

@section('css')

@endsection

@section('content')

@component('Backend.components.breadcrumb')
    @slot('title') Product List @endslot
    @slot('li_1') {{ Session::get('PageHeading'); }}  @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('delete.products') }}" onsubmit="return checkSelects('Form')" id="Form">
                    @csrf
                    <!--  Add Delete Button  -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('manage.products') }}"><button type="button" class="btn btn-primary waves-effect waves-light"><i data-feather="plus"></i> Add Product</button></a>
                        <button type="submit" class="btn btn-danger waves-effect waves-light"><i data-feather="minus"></i> Delete Product </button>
                    </div>

                    <table id="datatable" class="table responsive table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th width="5%"><i data-feather="hash" ></i> S No.</th>
                                <th width="5%" style="text-align: center;">
                                    <input class="form-check-input group-checkable" id="deletebcchk" name="deletebcchk" type="checkbox" id="formCheck">
                                </th>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Updated On</th>
                                <th>Action</th>
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
	var slength=[0,1,3,4,6];
    $(document).ready(function(){
        $("#datatable").DataTable({
            "bProcessing":false,
            "bServerSide":true,
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
            "order":[[2,"desc"]],
            "aoColumnDefs":[
                {"bSortable":false,"aTargets":slength},
                {"bSearchable":false,"aTargets":slength}
            ],
            "sAjaxSource": "{{ route('get.products')}}",
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