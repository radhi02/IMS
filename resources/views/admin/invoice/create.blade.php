@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Create New Invoice</h4>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Opps!</strong>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <form id="InvoiceGenerationForm" method="post" action="{{route('invoice.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Select Customer<span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" class="select">
                                <option value="">Select Customer</option>
                                @foreach ($Customer as $data)
                                <option value="{{$data->id}}">
                                    {{$data->customer_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Sales Order <span class="text-danger">*</span></label>
                            <select name="sales_order_id" id="sales_order_id" class="select">
                            <option value="">Select Sales Order</option>
                        </select>
                    </div>
                </div>
                <div class="row" id="my-content-div">
                </div>  
            </div>
        </div>
    </form>
</div><!-- /.content -->
<script>
$(function() {
    $('#toggle_btn').click();
    $('#customer_id').select2();
    $('#sales_order_id').select2();
});

$('#customer_id').on('change', function() {
    var id = $("#customer_id").select2('val');
    if (id == '') return false;
    $("#sales_order_id").html('');
    $("#my-content-div").html('');
    $.ajax({
        url: "{{ route('fetchSalesOrderList') }}",
        type: "POST",
        data: {
            id: id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(res) {
            $("#sales_order_id").append('<option value="">Select Sales Order</option>')
            $.each(res.orderlist, function(key, value) {
                $("#sales_order_id").append('<option value="' + value.id + '">' + value
                    .code + '</option>');
            });
        }
    });
});

$('#sales_order_id').on('change', function() {
    var id = $("#sales_order_id").select2('val');
    if (id == '') return false;
    $("#my-content-div").html('');
    $.post("{{ route('fetchSalesOrderData') }}", { id: id, _token: '{{csrf_token()}}' },
    function(data){
        $("#my-content-div").html(data);
    });
});

</script>

@endsection