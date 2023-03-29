@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>SalesOrder {{isset($SalesOrder)?'Edit':"Add"}}</h4>
            <h6>{{isset($SalesOrder)?'Update':"Create new"}} salesorder</h6>
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
    <form id="SalesOrderForm" method="post"
        action=" @if(!empty($SalesOrder->id)!=0){{route('salesorder.update',$SalesOrder->id)}}@else{{route('salesorder.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($SalesOrder->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Select Customer<span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" class="select">
                                <option value="">Select Customer</option>
                                @foreach ($Customer as $data)
                                <option value="{{$data->id}}"
                                    {{ old('customer_id', (isset($SalesOrder)&& $SalesOrder->customer_id == $data->id) ? 'selected' : ''   )}}>
                                    {{$data->customer_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Order #<span class="text-danger">*</span></label>
                            <input type="text" disabled
                                value="{{ old('code', isset($SalesOrder) ? $SalesOrder->code : orderLastID() )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Sale Order Date<span class="text-danger">*</span></label>
                            <input type="text" disabled
                                value="{{ old('order_date', isset($SalesOrder) ? $SalesOrder->order_date : ShowNewDateFormat(date('Y-m-d h:i:s')) )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select name="status" class="select">
                                <option value="Pending"
                                    {{(isset($SalesOrder->status) && $SalesOrder->status=='Pending')?'selected':'' }}>
                                    Pending</option>
                                <option value="Complete"
                                    {{(isset($SalesOrder->status) && $SalesOrder->status=='Complete')?'selected':'' }}>
                                    Complete</option>
                                <option value="Processing"
                                    {{(isset($SalesOrder->status) && $SalesOrder->status=='Processing')?'selected':'' }}>
                                    Processing</option>
                                <option value="Cancelled"
                                    {{(isset($SalesOrder->status) && $SalesOrder->status=='Cancelled')?'selected':'' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="customer_address">Customer Address</label>
                            <input type="text" id="customer_address" disabled
                                value="{{(isset($customerAddress))?$customerAddress:''}}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="payment_terms">Payment Terms<span class="text-danger">*</span></label>
                            <select name="payment_terms" id="payment_terms" class="select">
                                <option value="">Select Payment Terms</option>
                                <option value="Advance" {{  old('payment_terms', (isset($SalesOrder->payment_terms) && $SalesOrder->payment_terms == "Advance" ) ? 'selected' : '') }}> Advance</option>
                                <option value="Immediate" {{  old('payment_terms', (isset($SalesOrder->payment_terms) && $SalesOrder->payment_terms == "Immediate" ) ? 'selected' : '') }}> Immediate</option>
                                <option value="7Days" {{  old('payment_terms', (isset($SalesOrder->payment_terms) && $SalesOrder->payment_terms == "7Days" ) ? 'selected' : '') }}> 7 Days From Date of Invoice </option>
                                <option value="15Days" {{  old('payment_terms', (isset($SalesOrder->payment_terms) && $SalesOrder->payment_terms == "15Days" ) ? 'selected' : '') }}> 15 Days From Date of Invoice </option>
                                <option value="30Days" {{  old('payment_terms', (isset($SalesOrder->payment_terms) && $SalesOrder->payment_terms == "30Days" ) ? 'selected' : '') }}> 30 Days From Date of Invoice </option>
                                <option value="45Days" {{  old('payment_terms', (isset($SalesOrder->payment_terms) && $SalesOrder->payment_terms == "45Days" ) ? 'selected' : '') }}> 45 Days From Date of Invoice </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="description">Invoice Terms and Conditions<span class="text-danger">*</span></label>
                            <textarea type="text" id="description" name="description"
                                placeholder="Description">{{ old('description', isset($SalesOrder) ? br2nl($SalesOrder->description) : "Goods once sold will not be taken back.
Interest @ 18% p.a. will be charged if the payment
is not made within the stipulated time. 
Subject to 'Vadodara' Jurisdiction only.")  }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="delivery_mode">Delivery Information<span class="text-danger">*</span></label>
                            <textarea type="text" id="delivery_mode" name="delivery_mode"
                                placeholder="Delivery Information">{{ old('delivery_mode', isset($SalesOrder) ? $SalesOrder->delivery_mode : '' )  }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label>Select Product<span class="text-danger">*</span></label>
                            <select id="product_id" class="select">
                                <option value="">Select Product | | |</option>
                                @foreach ($Product as $data)
                                <option value="{{$data->id}}">
                                    {{$data->name}} | {{$data->sku}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>QTY</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Tax (%)</th>
                                    <th>Total</th>
                                    <th>Delivery Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductList">
                                @if(isset($SalesOrder) && !empty($SalesOrder->order_products))
                                @php $tmpOrderProducts = json_decode($SalesOrder->order_products); @endphp
                                @if(count($tmpOrderProducts) > 0)
                                @foreach($tmpOrderProducts as $op1)
                                <tr>
                                    <td>{{ProductDetail($op1->product_id)->name}}</td>
                                    <td>
                                        <div class="form-group" style="margin-bottom: 0px;"><input type="number" style="padding:5px 5px" name="rawqun[]" min="1" class="txtqun" value="{{$op1->quantity}}"></div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin-bottom: 0px;"><input type="number" style="padding:5px 5px" name="rawprice[]" min="1" class="txtprice" value="{{$op1->base_price}}">
                                        </div>
                                    </td>
                                    <td>
                                        <label class="txtamt">{{$op1->base_subtotal_withoutax}}</label>
                                        <input type="hidden" class="rawamtwithouttax" name="rawamtwithouttax[]" value="{{$op1->base_subtotal_withoutax}}">
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <input type="number" style="padding:5px 5px" name="rawtax[]" min="1" class="txttax" value="{{$op1->base_tax_per}}"> 
                                            <input type="hidden" class="rawtaxamount" name="rawtaxamount[]" value="{{$op1->base_tax_amount}}">
                                        </div>
                                    </td>
                                    <td>
                                        <label class="txttotalamt">{{$op1->base_total}}</label>
                                        <input type="hidden" class="rawamtwithtax" name="rawamtwithtax[]" value="{{$op1->base_total}}"></td>
                                    <td>
                                        <div class="form-group" style="margin-bottom: 0px;"> <input type="date" min="<?php echo date('Y-m-d');?>" name="rawdelivery[]" style="padding:5px 5px" value="{{date('Y-m-d', strtotime($op1->delivery_date))}}"></div>
                                    </td>
                                    <td><a href="javascript:void(0);"><img class="deleteraw" src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="svg"></a> 
                                    </td><input type="hidden" name="rawids[]" value="{{$op1->product_id}}">
                                </tr>
                                @endforeach
                                @endif
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th><label id="tfootquantity"></label></th>
                                    <th><label id="tfootrate"></label></th>
                                    <th><label id="tfootamt"></label></th>
                                    <th>-</th>
                                    <th><label id="tfoottotal"></label></th>
                                    <th>-</th>
                                    <th>-</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 float-md-right">
                        <div class="total-order">
                            <ul>
                                <li class="total ordertax">
                                    <h4>Order Tax</h4>
                                    <h5>₹ 0.00</h5>
                                </li>
                                <li class="total subtotal">
                                    <h4>Sub Total (Tax excluded)</h4>
                                    <h5>₹ 0.00</h5>
                                </li>
                                <li class="total grandtotal">
                                    <h4>Grand Total</h4>
                                    <h5>₹ 0.00</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="txtordertax" name="txtordertax">
                <input type="hidden" id="txtsubtotal" name="txtsubtotal">
                <input type="hidden" id="txtgrandtotal" name="txtgrandtotal">
                <input type="hidden" id="txttotalquantity" name="txttotalquantity">
                <input type="hidden" id="txttotalamount" name="txttotalamount">
                <input type="hidden" id="customerstate" name="customerstate">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="{{route('salesorder.index')}}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div><!-- /.content -->
<script>
@if(isset($SalesOrder))
getCusAdd("{{$SalesOrder->customer_id}}");
@endif

$(function() {
    $('#toggle_btn').click();
    function formatitemresult(item) {
        var selectionText = item.text.split("|");
        if (Array.isArray(selectionText)) {
            var $returnString = $('<span style="font-weight:700">Name: </span><span>' + selectionText[0] +
                '</span></br><span style="font-weight:700">Code: </span><span>' + selectionText[1] +
                '</span>');
            return $returnString;
        }
    };

    function fortmatitemselection(item) {
        var selectionText = item.text.split("|");
        if (Array.isArray(selectionText)) {
            var $returnString = $('<span>' + selectionText[0] + '</span>');
            return $returnString;
        }
    };
    $('#product_id').select2({
        placeholder: "Select Product",
        templateResult: formatitemresult,
        templateSelection: fortmatitemselection
    });

});

$('#SalesOrderForm').validate({
    rules: {
        customer_id: { required: true },
        status: { required: true }, 
        delivery_mode: { required: true }, 
        payment_terms: { required: true }, 
        description: { required: true }, 
        'rawqun[]': { required: true }, 
        'rawprice[]': { required: true }, 
        'rawamtwithtax[]': { required: true }, 
        'rawids[]': { required: true }, 
        'rawdelivery[]': { required: true },
    },
    messages: {
        customer_id: { required: "Please select customer" }, 
        status: { required: "Please select status" }, 
        delivery_mode: { required: "Please enter delivery information" }, 
        payment_terms: { required: "Please select payment terms" }, 
        description: { required: "Please enter Payment Terms and Conditions" }, 
        "rawqun[]": { required: "Please enter quantity" }, 
        "rawprice[]": { required: "Please enter price" }, 
        "rawamtwithtax[]": { required: "Please enter amount" }, 
        "rawids[]": { required: "Please select product" }, 
        "rawdelivery[]": { required: "Please enter delivery date" }, 
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

$("#tbodyProductList").on('input', '.txtqun', function() {
    var $qun = $(this).val();
    var $price = $(this).closest('tr').find('.txtprice').val();
    var $tax = $(this).closest('tr').find('.txttax').val();
    var $amount = parseFloat($qun) * parseFloat($price);
    var $txtamt = 0;
    var $tax_amount = $amount;
    if ($tax != 0) {
        $txtamt = ($amount * $tax) / 100
        $tax_amount = $amount + ( ($amount * $tax) / 100);
    }
    if (isNaN($tax_amount)) $tax_amount = 0;
    if (isNaN($amount)) $amount = 0;
    if (isNaN($txtamt)) $txtamt = 0;
    $(this).closest('tr').find('.txtamt').text($amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithouttax').val($amount.toFixed(2));
    $(this).closest('tr').find('.rawtaxamount').val($txtamt.toFixed(2));
    $(this).closest('tr').find('.txttotalamt').text($tax_amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithtax').val($tax_amount.toFixed(2));
    calculateCost();
});

$("#tbodyProductList").on('input', '.txtprice', function() {
    var $price = $(this).val();
    var $qun = $(this).closest('tr').find('.txtqun').val();
    var $tax = $(this).closest('tr').find('.txttax').val();
    var $amount = parseFloat($qun) * parseFloat($price);
    var $txtamt = 0;
    var $tax_amount = $amount;
    if ($tax != 0) {
        $txtamt = ($amount * $tax) / 100
        $tax_amount = $amount + ( ($amount * $tax) / 100);
    }
    if (isNaN($tax_amount)) $tax_amount = 0;
    if (isNaN($amount)) $amount = 0;
    if (isNaN($txtamt)) $txtamt = 0;
    $(this).closest('tr').find('.txtamt').text($amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithouttax').val($amount.toFixed(2));
    $(this).closest('tr').find('.rawtaxamount').val($txtamt.toFixed(2));
    $(this).closest('tr').find('.txttotalamt').text($tax_amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithtax').val($tax_amount.toFixed(2));
    calculateCost();
});

$("#tbodyProductList").on('input', '.txttax', function() {
    var $tax = $(this).val();
    var $price = $(this).closest('tr').find('.txtprice').val();
    var $qun = $(this).closest('tr').find('.txtqun').val();
    var $amount = parseInt($qun) * parseFloat($price);
    var $txtamt = 0;
    var $tax_amount = $amount;
    if ($tax != 0) {
        $txtamt = ($amount * $tax) / 100
        $tax_amount = $amount + ( ($amount * $tax) / 100);
    }
    if (isNaN($tax_amount)) $tax_amount = 0;
    if (isNaN($amount)) $amount = 0;
    if (isNaN($txtamt)) $txtamt = 0;

    $(this).closest('tr').find('.txtamt').text($amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithouttax').val($amount.toFixed(2));
    $(this).closest('tr').find('.rawtaxamount').val($txtamt.toFixed(2));
    $(this).closest('tr').find('.txttotalamt').text($tax_amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithtax').val($tax_amount.toFixed(2));
    calculateCost();
});

function calculateCost() {    
    var totaltaxamount = 0;
    var totalwithouttax = 0;
    var totalwithtax = 0;
    var totalquantity = 0;
    var totalrawamt = 0;
    $(".txtqun").each(function() {
        if ($(this).val() != "") {
            totalquantity = parseInt(totalquantity) + parseInt($(this).val());
        }
    });
    $(".rawtaxamount").each(function() {
        if ($(this).val() != "") {
            totaltaxamount = parseFloat(totaltaxamount) + parseFloat($(this).val());
        }
    });
    $(".rawamtwithouttax").each(function() {
        if ($(this).val() != "") {
            totalwithouttax = parseFloat(totalwithouttax) + parseFloat($(this).val());
        }
    });
    $(".rawamtwithtax").each(function() {
        if ($(this).val() != "") {
            totalwithtax = parseFloat(totalwithtax) + parseFloat($(this).val());
        }
    });
    $(".txtprice").each(function() {
        if ($(this).val() != "") {
            totalrawamt = parseFloat(totalrawamt) + parseFloat($(this).val());
        }
    });
    if (isNaN(totaltaxamount)) totaltaxamount = 0;
    if (isNaN(totalwithouttax)) totalwithouttax = 0;
    if (isNaN(totalwithtax)) totalwithtax = 0;
    if (isNaN(totalrawamt)) totalrawamt = 0;

    $("#tfootquantity").text(totalquantity.toFixed(2));
    $("#tfootrate").text(totalrawamt.toFixed(2));
    $("#tfootamt").text(totalwithouttax.toFixed(2));
    $("#tfoottotal").text(totalwithtax.toFixed(2));

    $("#txttotalquantity").val(totalquantity.toFixed(2));
    $("#txttotalamount").val(totalrawamt.toFixed(2));
    $(".total-order .ordertax h5").text('₹ ' + totaltaxamount.toFixed(2));
    $("#txtordertax").val(totaltaxamount.toFixed(2));
    $(".total-order .subtotal h5").text('₹ ' + totalwithouttax.toFixed(2));
    $("#txtsubtotal").val(totalwithouttax.toFixed(2));
    $(".total-order .grandtotal h5").text('₹ ' + totalwithtax.toFixed(2));
    $("#txtgrandtotal").val(totalwithtax.toFixed(2));
    
    $('.taxli').remove();
    if ( ($("#customer_id").select2('val') != null) && ($("#customerstate").val() != '') ) {
        if ($("#customerstate").val() == "{{$companyState}}") {
            var tmptax = $("#txtordertax").val();
            var tmpigst = sgst = 0;
            if(tmptax != null || tmptax > 0) {
                tmpigst= tmptax/2;
                tmpstax= tmptax/2;
            }
            $('li.ordertax').before(
                '<li class="total taxli"> <h4>IGST </h4><h5>₹ '+Number(tmpigst).toFixed(2)+'</h5><input type="hidden" name="igst" id="igst" value="'+Number(tmpigst).toFixed(2)+'"></li><li class="total taxli"> <h4>CGST </h4><h5>₹ '+Number(tmpstax).toFixed(2)+'</h5><input type="hidden" name="sgst" id="sgst" value="'+Number(tmpstax).toFixed(2)+'"></li>'
            )
        } else {
            var tmptax = $("#txtordertax").val();
            var tmpcgst = 0;
            if(tmptax != null || tmptax > 0) {
                tmpcgst= tmptax;
            }
            $('li.ordertax').before(
                '<li class="total taxli"> <h4>SGST </h4><h5>₹ '+Number(tmpcgst).toFixed(2)+'</h5><input type="hidden" name="cgst" id="cgst" value="'+Number(tmpcgst).toFixed(2)+'"></li>'
            )
        }
    }

}

$('#customer_id').on('change', function() {
    var id = $("#customer_id").select2('val');
    if (id == '') return false;
    $("#customer_address").val('');
    getCusAdd(id);
});

function getCusAdd(id) {
    $.ajax({
        url: "{{ route('fetchCustomerAddress') }}",
        type: "POST",
        data: {
            id: id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(res) {
            $.each(res.customer, function(key, value) {
                $("#customer_address").val(' City: ' +
                    value.cityname + ', State: ' + value.statename + ', Country: ' +
                    value.countryname + ', Pincode: ' + value.customer_zipcode);
                // $("#customer_address").val('Street: ' + value.customer_street + ', City: ' +
                //     value.cityname + ', State: ' + value.statename + ', Country: ' +
                //     value.countryname + ', Pincode: ' + value.customer_zipcode);
                $("#customerstate").val(value.state);
                $('.taxli').remove();
                // $('.total-order li:not(:last-child)').remove();
                if (value.state == "{{$companyState}}") {
                    var tmptax = $("#txtordertax").val();
                    var tmpigst = sgst = 0;
                    if(tmptax != null || tmptax > 0) {
                        tmpigst= tmptax/2;
                        tmpstax= tmptax/2;
                    }
                    $('li.ordertax').before(
                        '<li class="total taxli"> <h4>IGST </h4><h5>₹ '+Number(tmpigst).toFixed(2)+'</h5><input type="hidden" name="igst" id="igst" value="'+Number(tmpigst).toFixed(2)+'"></li><li class="total taxli"> <h4>CGST </h4><h5>₹ '+Number(tmpstax).toFixed(2)+'</h5><input type="hidden" name="sgst" id="sgst" value="'+Number(tmpstax).toFixed(2)+'"></li>'
                    )
                } else {
                    var tmptax = $("#txtordertax").val();
                    var tmpcgst = 0;
                    if(tmptax != null || tmptax > 0) {
                        tmpcgst= tmptax;
                    }
                    $('li.ordertax').before(
                        '<li class="total taxli"> <h4>SGST </h4><h5>₹ '+Number(tmpcgst).toFixed(2)+'</h5><input type="hidden" name="cgst" id="cgst" value="'+Number(tmpcgst).toFixed(2)+'"></li>'
                    )
                }
            });
            calculateCost();

            <?php if(isset($SalesOrder)) { ?>
                // $("#igst").val("{{$SalesOrder->igst}}").trigger('change');
                // $("#cgst").val("{{$SalesOrder->cgst}}").trigger('change');
                // $("#sgst").val("{{$SalesOrder->sgst}}").trigger('change');
            <?php } ?>
        }
    });
};

$(document).on("click", ".deleteraw", function() {
    $(this).closest('tr').remove();
    calculateCost();
});

$('#product_id').on('change', function() {
    var id = $("#product_id").select2('val');
    if (id == '') return false;
    $.ajax({
        url: "{{ route('addProduct') }}",
        type: "POST",
        data: {
            id: id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(res) {
            // return true;
            $.each(res.product, function(key, value) {
                $("#tbodyProductList").append('<tr><td>' + value.name +
                    '</td> <td><div class="form-group" style="margin-bottom: 0px;"><input type="number" name="rawqun[]" min="1" class="txtqun" style="padding:5px 5px"></div></td><td><div class="form-group" style="margin-bottom: 0px;"><input style="padding:5px 5px" type="number" name="rawprice[]" min="1" class="txtprice"></div></td><td> <label class="txtamt"></label><input type="hidden" class="rawamtwithouttax" name="rawamtwithouttax[]"></td><td><div class="form-group" style="margin-bottom: 0px;"><input type="number" style="padding:5px 5px" name="rawtax[]" class="txttax"><input type="hidden" class="rawtaxamount" name="rawtaxamount[]"> </div></td><td><label class="txttotalamt">0.00</label><input type="hidden" class="rawamtwithtax" name="rawamtwithtax[]"></td><td><div class="form-group" style="margin-bottom: 0px;"> <input type="date" min="<?php echo date('Y-m-d');?>" name="rawdelivery[]"></div></td><td><a href="javascript:void(0);"><img class="deleteraw" src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="svg"></a> </td> <input type="hidden" name="rawids[]" value="' + value.id + '"></tr>'); });
            // $('.select').select2();
        }
    });
});
</script>

@endsection