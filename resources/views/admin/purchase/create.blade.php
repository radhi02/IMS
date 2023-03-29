@extends('layouts.app')
@section('content')
<style>
    #tbodyProductList .form-group {
        margin-bottom:0px;
    }
</style>
<?php $count = 1; ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>PurchaseOrder {{isset($PurchaseOrder)?'Edit':"Add"}}</h4>
            <h6>{{isset($PurchaseOrder)?'Update':"Create new"}} purchase</h6>
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
    <form id="PurchaseOrderForm" method="post"
        action=" @if(!empty($PurchaseOrder->id)!=0){{route('purchase.update',$PurchaseOrder->id)}}@else{{route('purchase.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($PurchaseOrder->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Select Vendor<span class="text-danger">*</span></label>
                            <select name="vendor_id" id="vendor_id" class="select">
                                <option value="">Select Vendor</option>
                                @foreach ($Vendor as $data)
                                <option value="{{$data->id}}"
                                    {{ old('vendor_id', (isset($PurchaseOrder)&& $PurchaseOrder->vendor_id == $data->id) ? 'selected' : ''   )}}>
                                    {{$data->vendor_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Order #<span class="text-danger">*</span></label>
                            <input type="text" disabled
                                value="{{ old('code', isset($PurchaseOrder) ? $PurchaseOrder->code : purchaseLastID() )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Purchase Order Date<span class="text-danger">*</span></label>
                            <input type="text" disabled
                                value="{{ old('order_date', isset($PurchaseOrder) ? $PurchaseOrder->order_date : ShowNewDateFormat(date('Y-m-d h:i:s')) )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select name="status" class="select">
                                <option value="Pending"
                                    {{(isset($PurchaseOrder->status) && $PurchaseOrder->status=='Pending')?'selected':'' }}>
                                    Pending</option>
                                <option value="Complete"
                                    {{(isset($PurchaseOrder->status) && $PurchaseOrder->status=='Complete')?'selected':'' }}>
                                    Complete</option>
                                <option value="Processing"
                                    {{(isset($PurchaseOrder->status) && $PurchaseOrder->status=='Processing')?'selected':'' }}>
                                    Processing</option>
                                <option value="Cancelled"
                                    {{(isset($PurchaseOrder->status) && $PurchaseOrder->status=='Cancelled')?'selected':'' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label for="vendor_address">Vendor Address</label>
                            <input type="text" id="vendor_address" disabled
                                value="{{(isset($vendorAddress))?$vendorAddress:''}}">
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label for="description">Terms and Conditions</label>
                            <textarea type="text" id="description" name="description"
                                placeholder="Terms and Conditions">{{ old('description', isset($PurchaseOrder) ? $PurchaseOrder->description : '' )  }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <!-- <th>Description</th> -->
                                    <th>Note</th>
                                    <th>Rate</th>
                                    <th>QTY</th>
                                    <th>Tax (%)</th>
                                    <th>Sub Total</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductList">
                                @if(isset($PurchaseOrder) && !empty($PurchaseOrder->order_products))
                                @php $tmpOrderProducts = json_decode($PurchaseOrder->order_products); @endphp
                                @if(count($tmpOrderProducts) > 0)
                                @foreach($tmpOrderProducts as $op1)
                                <tr>
                                    <td>{{ProductDetail($op1->rawmaterial_id)->name}}</td>
                                    <td>{{ProductDetail($op1->rawmaterial_id)->name}}</td>
                                    <td>{{ProductDetail($op1->rawmaterial_id)->name}}</td>
                                    <td>
                                        <div class="form-group"><input type="number" min=1 oninput="validity.valid||(value='');" style="padding:5px 5px" name="rawqun[{{$count}}][]" class="txtqun" value="{{$op1->quantity}}"></div>
                                    </td>
                                    <td>
                                        <div class="form-group"><input type="number" min=1 oninput="validity.valid||(value='');" style="padding:5px 5px" name="rawprice[{{$count}}][]" class="txtprice" value="{{$op1->base_price}}">
                                        </div>
                                    </td>
                                    <td><label class="txtamt">{{$op1->base_total}}</label><input type="hidden" 
                                            class="rawamtwithtax" name="rawamtwithtax[{{$count}}][]" value="{{$op1->base_total}}"></td>
                                    <td><a href="javascript:void(0);"><img class="deleteraw" src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="svg"></a> 
                                    </td><input type="hidden" name="rawids[{{$count}}][]" value="{{$op1->rawmaterial_id}}">
                                </tr>
                                @endforeach
                                @endif
                                @endif
                                @for($i=1; $i<=5; $i++)
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <select class="select rawmaterial_id" name="purchasematerial[{{$count}}][rawId]">
                                                <option value="">Select Raw material | | </option>
                                                @foreach ($Rawmaterial as $data)
                                                <option value="{{$data->id}}"> {{$data->code}} | {{$data->name}} | {{$data->HSN_CODE}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="note"></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" min=1 oninput="validity.valid||(value='');" style="padding:5px 5px" name="purchasematerial[{{$count}}][rawprice]" class="txtprice">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" min=1 oninput="validity.valid||(value='');" style="padding:5px 5px" name="purchasematerial[{{$count}}][rawqun]" class="txtqun">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" style="padding:5px 5px" name="purchasematerial[{{$count}}][rawtax]" min="0" class="taxselect">
                                            <!-- <select name="purchasematerial[{{$count}}][rawtax]" class="taxselect">
                                                <option value="0">0</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                            </select> -->
                                        </div>
                                    </td>
                                    <td class="tdSubtotal"><label></label></td>
                                    <td  class="tdtotal">
                                        <label></label>
                                    </td>
                                    <td class="tdAction"><a href="javascript:void(0);"><img class="deleteraw" src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="svg"></a> 
                                    </td>
                                    <input type="hidden" name="purchasematerial[{{$count}}][porid]">
                                    <input type="hidden" class="hiddentax" name="purchasematerial[{{$count}}][hiddentax]"></td>
                                    <input type="hidden" class="hiddensubtotal" name="purchasematerial[{{$count}}][hiddensubtotal]"></td>
                                    <input type="hidden" class="hiddentotal" name="purchasematerial[{{$count}}][hiddentotal]"></td>
                                </tr>
                                <?php $count++; ?>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2" style="margin-top: 30px;">
                        <a href="javascript:void(0);" id="addmore" class="btn mb-3 btn-primary btn-block w-100"> <i class="fas fa-plus"></i> Add another line </a>
                    </div>
                    <div class="col-lg-10 float-md-right">
                        <div class="total-order">
                            <ul>
                                <li class="total ordertax">
                                    <h4>Tax Total</h4>
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
                <input type="hidden" id="vendorstate" name="vendorstate">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="{{route('purchase.index')}}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div><!-- /.content -->
<script>
@if(isset($PurchaseOrder))
getVenAdd("{{$PurchaseOrder->vendor_id}}");
@endif

$(function() {
    $('#toggle_btn').click();
    // $('.rawmaterial_id').select2();
    selectDropdown();
    // $('.taxselect').select2();
});

$("#tbodyProductList").on('input', '.txtqun', function() {
    var $qun = $(this).val();
    var $price = $(this).closest('tr').find('.txtprice').val();
    var $taxper = $(this).closest('tr').find('.taxselect').val();
    var $subtotal = parseFloat($qun) * parseFloat($price);
    if (isNaN($subtotal)) $subtotal = 0;
    var $total = $subtotal;
    var $taxruppe = 0;
    if ($taxper > 0) {
        $taxruppe = ($subtotal * $taxper) / 100;
        $total = $subtotal + $taxruppe;
    }
    $(this).closest('tr').find('td.tdSubtotal label').text($subtotal.toFixed(2));
    $(this).closest('tr').find('td.tdtotal label').text($total.toFixed(2));
    $(this).closest('tr').find('.hiddentax').val($taxruppe);
    $(this).closest('tr').find('.hiddensubtotal').val($subtotal);
    $(this).closest('tr').find('.hiddentotal').val($total);
    calculateCost();
});

$("#tbodyProductList").on('input', '.txtprice', function() {
    var $price = $(this).val();
    var $qun = $(this).closest('tr').find('.txtqun').val();
    var $taxper = $(this).closest('tr').find('.taxselect').val();
    var $subtotal = parseFloat($qun) * parseFloat($price);
    if (isNaN($subtotal)) $subtotal = 0;
    var $total = $subtotal;
    var $taxruppe = 0;
    if ($taxper > 0) {
        $taxruppe = ($subtotal * $taxper) / 100;
        $total = $subtotal + $taxruppe;
    }
    $(this).closest('tr').find('td.tdSubtotal label').text($subtotal.toFixed(2));
    $(this).closest('tr').find('td.tdtotal label').text($total.toFixed(2));
    $(this).closest('tr').find('.hiddentax').val($taxruppe);
    $(this).closest('tr').find('.hiddensubtotal').val($subtotal);
    $(this).closest('tr').find('.hiddentotal').val($total);
    calculateCost();
});
$("#tbodyProductList").on('input', '.taxselect', function() {
    var $taxper = $(this).val();
    var $qun = $(this).closest('tr').find('.txtqun').val();
    var $price = $(this).closest('tr').find('.txtprice').val();
    var $subtotal = parseFloat($qun) * parseFloat($price);
    if (isNaN($subtotal)) $subtotal = 0;
    var $total = $subtotal;
    var $taxruppe = 0;
    if ($taxper > 0) {
        $taxruppe = ($subtotal * $taxper) / 100;
        $total = $subtotal + $taxruppe;
    }
    $(this).closest('tr').find('td.tdSubtotal label').text($subtotal.toFixed(2));
    $(this).closest('tr').find('td.tdtotal label').text($total.toFixed(2));
    $(this).closest('tr').find('.hiddentax').val($taxruppe);
    $(this).closest('tr').find('.hiddensubtotal').val($subtotal);
    $(this).closest('tr').find('.hiddentotal').val($total);
    calculateCost();
});
function calculateCost() {
    var Ctaxcost = 0;
    var Csubtotal = 0;
    var Ctotal = 0;
    $(".hiddentax").each(function() {
        if ($(this).val() != "") {
            Ctaxcost = parseFloat(Ctaxcost) + parseFloat($(this).val());
        }
    });
    $(".hiddensubtotal").each(function() {
        if ($(this).val() != "") {
            Csubtotal = parseFloat(Csubtotal) + parseFloat($(this).val());
        }
    });
    $(".hiddentotal").each(function() {
        if ($(this).val() != "") {
            Ctotal = parseFloat(Ctotal) + parseFloat($(this).val());
        }
    });
    $(".total-order .subtotal h5").text('₹ ' + Csubtotal.toFixed(2));
    $("#txtsubtotal").val(Csubtotal.toFixed(2));
    $(".total-order .ordertax h5").text('₹ ' + Ctaxcost.toFixed(2));
    $("#txtordertax").val(Ctaxcost.toFixed(2));
    $(".total-order .grandtotal h5").text('₹ ' + Ctotal.toFixed(2));
    $("#txtgrandtotal").val(Ctotal.toFixed(2));
    $('.taxli').remove();
    if ($("#vendor_id").select2('val') != null && $("#vendorstate").val() != null) {
        if ($("#vendorstate").val() == "{{$companyState}}") {
            var tmptax = $("#txtordertax").val();
            var tmpigst = sgst = 0;
            if(tmptax != null || tmptax > 0) {
                tmpigst= tmptax/2;
                tmpstax= tmptax/2;
            }
            $('li.ordertax').before( '<li class="total taxli"> <h4>IGST</h4><h5>₹ '+tmpigst+'</h5></li><li class="total taxli"> <h4>CGST</h4><h5>₹ '+tmpstax+'</h5></li>' )
        } else {
            var tmptax = $("#txtordertax").val();
            var tmpcgst = 0;
            if(tmptax != null || tmptax > 0) {
                tmpcgst= tmptax;
            }
            $('li.ordertax').before(
                '<li class="total taxli"> <h4>SGST</h4><h5>₹ '+tmpcgst+'</h5></li>'
            )
        }
    }
}

$(document).on('change', '#vendor_id', function() {
    var id = $("#vendor_id").select2('val');
    if (id == '') return false;
    $("#vendor_address").val('');
    getVenAdd(id);
});

function getVenAdd(id) {
    $.ajax({
        url: "{{ route('fetchVendorAddress') }}",
        type: "POST",
        data: {
            id: id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(res) {
            $.each(res.vendor, function(key, value) {
                $("#vendor_address").val('Street: ' + value.vendor_street + ', City: ' +
                    value.cityname + ', State: ' + value.statename + ', Country: ' +
                    value.countryname + ', Pincode: ' + value.vendor_zipcode);
                $('.taxli').remove();
                $("#vendorstate").val(value.state);
                if (value.state == "{{$companyState}}") {
                    var tmptax = $("#txtordertax").val();
                    var tmpigst = sgst = 0;
                    if(tmptax != null || tmptax > 0) {
                        tmpigst= tmptax/2;
                        tmptax= tmptax/2;
                    }
                    $('li.ordertax').before( '<li class="total taxli"> <h4>IGST</h4><h5>₹ '+tmpigst+'</h5></li><li class="total taxli"> <h4>CGST</h4><h5>₹ '+tmpigst+'</h5></li>' )
                } else {
                    var tmptax = $("#txtordertax").val();
                    var tmpcgst = 0;
                    if(tmptax != null || tmptax > 0) {
                        tmpcgst= tmptax;
                    }
                    $('li.ordertax').before(
                        '<li class="total taxli"> <h4>SGST</h4><h5>₹ '+tmpcgst+'</h5></li>'
                    )
                }
                // $('.taxselect').select2();
            });

            // $('.taxselect').on('change', function() {
            //     calculateCost();
            // });
            calculateCost();
        }
    });
};

$(document).on("click", ".deleteraw", function() {
    $(this).closest('tr').remove();
    calculateCost();
});

$('#addmore').on('click', function() {
    var cnt = $('#tbodyProductList tr').length + 1;
    $("#tbodyProductList").append('<tr> <td> <div class="form-group"> <select class="select rawmaterial_id" name="purchasematerial['+cnt+'][rawId]"> <option value="">Select Raw material</option> @foreach ($Rawmaterial as $data) <option value="{{$data->id}}"> {{$data->code}} | {{$data->name}} | {{$data->HSN_CODE}} </option> @endforeach </select> </div> </td> <td class="note"></td> <td> <div class="form-group"> <input type="number" min=1 style="padding:5px 5px" name="purchasematerial['+cnt+'][rawprice]" class="txtprice"> </div> </td> <td> <div class="form-group"> <input type="number" min=1 style="padding:5px 5px" name="purchasematerial['+cnt+'][rawqun]" class="txtqun"> </div> </td> <td> <div class="form-group"> <input type="number" style="padding:5px 5px" name="purchasematerial['+cnt+'][rawtax]" min="0" class="taxselect"> </div> </td> <td class="tdSubtotal"><label></label></td> <td class="tdtotal"> <label></label></td> <td class="tdAction"><a href="javascript:void(0);"><img class="deleteraw" src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="svg"></a> </td> <input type="hidden" name="purchasematerial['+cnt+'][porid]"> <input type="hidden" class="hiddentax" name="purchasematerial['+cnt+'][hiddentax]"> <input type="hidden" class="hiddensubtotal" name="purchasematerial['+cnt+'][hiddensubtotal]"> <input type="hidden" class="hiddentotal" name="purchasematerial['+cnt+'][hiddentotal]"> </tr>'); 
    selectDropdown();
    // $('.taxselect').select2();
    CustomValidation();
});

$('#PurchaseOrderForm').validate({
    rules: {
        vendor_id: { required: true },
        status: { required: true }, 
        'purchasematerial[][rawqun]': { required: true }, 
    },
    messages: {
        vendor_id: { required: "Please select vendor" }, 
        status: { required: "Please select status" }, 
        "purchasematerial[][rawqun]": { required: "Please enter quantity" }, 
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

CustomValidation();
function CustomValidation(){
    var custominput = $('input[name^="purchasematerial"]');

    custominput.filter('input[name$="[rawqun]"]').each(function() {
        $(this).rules("add", {
            required: true,
            messages: {
                required: "Please enter quantity"
            }
        });
    });

    custominput.filter('input[name$="[rawId]"]').each(function() {
        $(this).rules("add", {
            required: true,
            messages: {
                required: "Please select raw material"
            }
        });
    });

    custominput.filter('input[name$="[rawprice]"]').each(function() {
        $(this).rules("add", {
            required: true,
            messages: {
                required: "Please enter rate"
            }
        });
    });

    custominput.filter('input[name$="[rawtax]"]').each(function() {
        $(this).rules("add", {
            required: true,
            messages: {
                required: "Please select tax"
            }
        });
    });
}
function selectDropdown() {
    function formatitemresult(item) {
        var selectionText = item.text.split("|");
        if (Array.isArray(selectionText)) {
            var $returnString = $('<span style="font-weight:700">Code: </span><span>' + selectionText[0] +
                '</span></br><span style="font-weight:700">Name: </span><span>' + selectionText[1] +
                '</span></br><span style="font-weight:700">HSN CODE: </span><span>' + selectionText[2] +
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
    $('.rawmaterial_id').select2({
        placeholder: "Select Raw Material",
        templateResult: formatitemresult,
        templateSelection: fortmatitemselection
    });
}
$(document).on('change', '.rawmaterial_id', function() {
    var element = $(this);
    $.ajax({
        url: "{{ route('fetchMaterialRequirement') }}",
        type: "POST",
        data: {
            id: element.val(),
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(res) {
            element.closest('tr').find('.note').html(res);
        }
    });
});

</script>

@endsection