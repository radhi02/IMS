@extends('layouts.app')
@section('content')
<script>
@if(Session::has('msg') != '')
Swal.fire(
    'Success!',
    '{{ Session::has("msg") ? Session::get("msg") : '
    ' }}',
    'success'
)
@endif
</script>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Manufactured Order List</h4>
            <h6>Check your manufactured order</h6>
        </div>
        <!-- <div class="page-btn">
            <a href="{{route('manufacture.create')}}" class="btn btn-added"><img src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Create New Manufacturing Order</a>
        </div> -->
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="{{URL::asset('admin_asset/img/icons/search-white.svg')}}"
                                alt="img"></a>
                    </div>
                    <!-- <div class="col-lg col-sm-6 col-12">
                        <div style="margin-left: 10px; width: 200px">
                            <select class="select statuschange" id="statuschange">
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Complete">Complete</option>
                                <option value="Processing">Processing</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Code</th>
                            <th>Sales Order</th>
                            <th>Product</th>
                            <th>Delivery Date</th>
                            <th>Finish Good Quantity</th>
                            <th>Quantity</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ManufactureOrder as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <form action="{{ route('qualitychecking.updatestock') }}" method="POST">
                            @csrf
                                <td>{{$loop->iteration}}</td>
                                <td>{{$values->code}}</td>
                                <td>{{$values->s_code}}</td>
                                <td>{{$values->p_name}}</td>
                                <td>{{ShowNewDateFormat($values->delivery_date)}}</td>
                                <td>{{$values->check_quantity}}</td>
                                <td>
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <input type="number" style="padding:5px 5px" name="checkqun" id="checkqun" min="1" max="{{$values->check_quantity}}" value="{{$values->check_quantity}}">
                                    </div>
                                </td>
                                <td>
                                    <input type="hidden" name="mo_id" value="{{$values->id}}">
                                    <input type="hidden" name="product_id" value="{{$values->product_id}}">
                                    <input type="hidden" name="so_id" value="{{$values->s_id}}">
                                    
                                    <button type="submit" class="btn btn-success btn-sm" name="btnstatus" value="approve">Approve</button>
                                    <button type="button" class="btn btn-danger btn-sm btn-reject" data-mid="{{$values->id}}" data-pid="{{$values->product_id}}">Reject</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal custom-modal fade" id="rejection_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Reason for Rejection</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form id="ProductRejectionForm" method="post" action="{{route('qualitychecking.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <textarea type="text" name="modal_reason" id="reason" placeholder="Reason"></textarea>
                    </div>
                    <input type="hidden" name="modal_mo_id" id="modal-mo_id">
                    <input type="hidden" name="modal_product_id" id="modal-product_id">
                    <input type="hidden" name="modal_quantity" id="modal-quantity">
                    <div class="submit-section">
                        <button type="submit" class="btn btn-submit">Submit</button>                    
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.btn-reject').click(function () {
        $('#ProductRejectionForm').trigger("reset");
        $("#modal-mo_id").val($(this).data('mid'));
        $("#modal-product_id").val($(this).data('pid'));
        $("#modal-quantity").val($("#checkqun").val());
        $('#rejection_modal').modal('show');
    });
});

$('#ProductRejectionForm').validate({
    rules: {
        modal_reason: { required: true },
        modal_mo_id: { required: true },
        modal_product_id: { required: true },
        modal_quantity: { required: true },
    },
    messages: {
        modal_reason: { required: "Please enter reason" },
        modal_mo_id: { required: "Please select Manufacturing order" },
        modal_product_id: { required: "Please select product" },
        modal_quantity: { required: "Please enter quantity" },
    },
    errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});

</script>

@endsection