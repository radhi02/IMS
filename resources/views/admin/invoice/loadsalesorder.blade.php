<?php $count = 1; ?>

<div class="card-sales-split" style="margin-bottom: 25px;">
    <h2>Sale Order : {{$SalesOrder->code}}</h2>
</div>
<div class="row" style="margin-bottom: 25px;">
    <div class="col-md-3">
        <ul class="list-unstyled mb-0">
            <li style="color: #7367F0;">Customer Info</li>
            <li>{{$SalesOrder->customer_name}}</li>
            <li>{{$SalesOrder->customer_email}}</li>
            <li>{{$SalesOrder->customer_phone}}</li>
            <li>{{$SalesOrder->customer_street}} , {{$SalesOrder->cityname}}</li>
            <li>{{$SalesOrder->statename}} , {{$SalesOrder->countryname}}</li>
            <li>{{$SalesOrder->customer_zipcode}}</li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul class="list-unstyled mb-0">
            <li style="color: #7367F0;">Sale Order Date</li>
            <li>{{ShowNewDateFormat($SalesOrder->order_date)}}</li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul class="list-unstyled mb-0">
            <li style="color: #7367F0;">Invoice Terms and Conditions</li>
            <li>{{br2nl($SalesOrder->description)}}</li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul class="list-unstyled mb-0">
            <li style="color: #7367F0;">Order Status</li>
            <li>{{$SalesOrder->status}}</li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="table-responsive mb-3">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:40%">Name</th>
                    <th>Available QTY</th>
                    <th>Ordered QTY</th>
                    <th>QTY</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Tax (%)</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbodyProductList">
                @if(isset($SalesOrder) && !empty($SalesOrder->order_products))
                @php $tmpOrderProducts = json_decode($SalesOrder->order_products); @endphp
                @if(count($tmpOrderProducts) > 0)
                @foreach($tmpOrderProducts as $op1)
                @php
                $AvailableQ = ProductDetail($op1->product_id)->quantity;
                $RemainedQ = $op1->invoice_remained_quantity;
                $maxQ = ($RemainedQ>($AvailableQ))?($AvailableQ):$RemainedQ; @endphp
                @if($RemainedQ > 0)
                @if($AvailableQ > 0)
                <tr>
                    <td>{{ProductDetail($op1->product_id)->name}}</td>
                    <td>{{$AvailableQ}}</td>
                    <td>{{$RemainedQ}}</td>
                    <td>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <input type="number" style="padding:5px 5px" name="invoicedata[{{$count}}][quantity]" min="1" class="txtqun" value="{{$RemainedQ}}">
                        </div>
                    </td>
                    <td>
                        <label>{{$op1->base_price}}</label>
                        <input type="hidden" name="invoicedata[{{$count}}][base_price]" min="1" class="txtprice" value="{{$op1->base_price}}"> 
                    </td>
                    <td>
                        <label class="lbltxtamt">{{$op1->base_subtotal_withoutax}}</label>
                        <input type="hidden" class="rawamtwithouttax" name="invoicedata[{{$count}}][base_subtotal_withoutax]" value="{{$op1->base_subtotal_withoutax}}">
                    </td> 
                    <td>
                        <label class="lbltax">{{$op1->base_tax_per}}</label>
                        <input type="hidden" style="padding:5px 5px" name="invoicedata[{{$count}}][rawtax]" min="1" class="txttax" value="{{$op1->base_tax_per}}"> 
                        <input type="hidden" class="rawtaxamount" name="invoicedata[{{$count}}][rawtaxamount]" value="{{$op1->base_tax_amount}}">
                    </td>
                    <td>
                        <label class="lbltotalamt">{{$op1->base_total}}</label>
                        <input type="hidden" class="rawamtwithtax" name="invoicedata[{{$count}}][base_total]" value="{{$op1->base_total}}">
                    </td> 
                    <td>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label class="checkboxs" style="height: 10px;">
                                <input type="checkbox" class="checkbox select-all-sub" data-id="{{$op1->product_id}}" data-orderq="{{$RemainedQ}}" name="invoicedata[{{$count}}][chk]">
                                <span class="checkmarks"></span>
                            </label>
                        </div>
                    </td>
                    <input type="hidden" name="invoicedata[{{$count}}][product_id]" value="{{$op1->product_id}}">
                </tr>
                <?php $count++; ?>
                @else
                <tr>
                    <td>{{ProductDetail($op1->product_id)->name}}</td>
                    <td colspan="8">{{$AvailableQ}}</td>
                </tr>
                @endif
                @endif
                @endforeach
                @endif
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 float-md-right">
        <div class="total-order">
            <ul>
                @if($SalesOrder->stateid == $companyState)
                <li class="total taxli">
                    <h4>IGST</h4>
                    <h5>₹ {{($SalesOrder->base_tax_amount)/2}}</h5>
                </li>
                <li class="total taxli">
                    <h4>CGST</h4>
                    <h5>₹ {{($SalesOrder->base_tax_amount)/2}}</h5>
                </li>
                @else
                <li class="total taxli">
                    <h4>SGST</h4>
                    <h5>₹ {{$SalesOrder->base_tax_amount}}</h5>
                </li>
                @endif
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
<div class="col-lg-12">
    <button type="submit" class="btn btn-submit me-2">Submit</button>
    <a href="{{route('invoice.index')}}" class="btn btn-cancel">Cancel</a>
</div>
<script>
$(function() {
    calculateCost();

    var names = "";
    $('.select-all-sub').each(function() {
        names += $(this).attr('name') + " ";
    });
    names = $.trim(names);

    $('#InvoiceGenerationForm').validate({
        groups: {
            myGroup: names
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
    $('.select-all-sub').each(function () {
        $(this).rules('add', { 
            require_from_group: [1, '.select-all-sub'] ,
            messages: { require_from_group: "Please select at least one product" }
        });
    });
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
    $(this).closest('tr').find('.lbltxtamt').text($amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithouttax').val($amount.toFixed(2));
    $(this).closest('tr').find('.rawtaxamount').val($txtamt.toFixed(2));
    $(this).closest('tr').find('.lbltotalamt').text($tax_amount.toFixed(2));
    $(this).closest('tr').find('.rawamtwithtax').val($tax_amount.toFixed(2));
    calculateCost();
});

function calculateCost() {
    var totaltaxamount = 0;
    var totalwithouttax = 0;
    var totalwithtax = 0;
    var totalquantity = 0;
    var totalrawamt = 0;

    // $('.select-all-sub').each(function() {
    //     if(this.checked) {
    //         var rval = $(this).closest('td').siblings().find('input.rawamt').val();
    //         if (rval != "") {
    //             cost = parseFloat(cost) + parseFloat(rval);
    //         }
    //     }
    //     if (taxper != 0) {
    //         taxcost = (cost * taxper) / 100;
    //     }
    //     totalwithtax = taxcost + cost;
    // });

    $('.select-all-sub').each(function() {
        if(this.checked) {

            var txtqun = $(this).closest('tr').find('.txtqun').val();
            if (txtqun != "") {
                totalquantity = parseInt(totalquantity) + parseInt(txtqun);
            }

            var rawtaxamount = $(this).closest('tr').find('.rawtaxamount').val();
            if (rawtaxamount != "") {
                totaltaxamount = parseFloat(totaltaxamount) + parseFloat(rawtaxamount);
            }

            var rawamtwithouttax = $(this).closest('tr').find('.rawamtwithouttax').val();
            if (rawamtwithouttax != "") {
                totalwithouttax = parseFloat(totalwithouttax) + parseFloat(rawamtwithouttax);
            }

            var rawamtwithtax = $(this).closest('tr').find('.rawamtwithtax').val();
            if (rawamtwithtax != "") {
                totalwithtax = parseFloat(totalwithtax) + parseFloat(rawamtwithtax);
            }

            var txtprice = $(this).closest('tr').find('.txtprice').val();
            if (txtprice != "") {
                totalrawamt = parseFloat(totalrawamt) + parseFloat(txtprice);
            }

            if (isNaN(totaltaxamount)) totaltaxamount = 0;
            if (isNaN(totalwithouttax)) totalwithouttax = 0;
            if (isNaN(totalwithtax)) totalwithtax = 0;
            if (isNaN(totalrawamt)) totalrawamt = 0;
        }
    });

    $("#txttotalquantity").val(totalquantity.toFixed(2));
    $("#txttotalamount").val(totalrawamt.toFixed(2));
    $(".total-order .ordertax h5").text('₹ ' + totaltaxamount.toFixed(2));
    $("#txtordertax").val(totaltaxamount.toFixed(2));
    $(".total-order .subtotal h5").text('₹ ' + totalwithouttax.toFixed(2));
    $("#txtsubtotal").val(totalwithouttax.toFixed(2));
    $(".total-order .grandtotal h5").text('₹ ' + totalwithtax.toFixed(2));
    $("#txtgrandtotal").val(totalwithtax.toFixed(2));

    $('.taxli').remove();
    if ("{{$SalesOrder->stateid}}" == "{{$companyState}}") {
        var tmptax = $("#txtordertax").val();
        var tmpigst = sgst = 0;
        if(tmptax != null || tmptax > 0) {
            tmpigst= tmptax/2;
            tmpstax= tmptax/2;
        }
        $('li.ordertax').before(
            '<li class="total taxli"> <h4>IGST </h4><h5>₹ '+tmpigst+'</h5><input type="hidden" name="igst" id="igst" value="'+tmpigst+'"></li><li class="total taxli"> <h4>CGST </h4><h5>₹ '+tmpstax+'</h5><input type="hidden" name="sgst" id="sgst" value="'+tmpstax+'"></li>'
        )
    } else {
        var tmptax = $("#txtordertax").val();
        var tmpcgst = 0;
        if(tmptax != null || tmptax > 0) {
            tmpcgst= tmptax;
        }
        $('li.ordertax').before(
            '<li class="total taxli"> <h4>SGST </h4><h5>₹ '+tmpcgst+'</h5><input type="hidden" name="cgst" id="cgst" value="'+tmpcgst+'"></li>'
        )
    }
}

$('.select-all-sub').change(function() {
    var element = $(this);
    if(this.checked) {
        var qun = element.closest('td').siblings().find('input.txtqun').val();
        var pid = element.data('id');
        var orderq = element.data('orderq');
        element.closest('td').siblings().find('input.txtqun').prop("readonly", true);
        $.ajax({
            url: "{{ route('fetchProductQun') }}",
            type: "POST",
            data: {
                pid: pid,
                qun: qun,
                orderq: orderq,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(res) {
                if(res.msg == 2) {
                    Swal.fire({
                        icon: 'warning',
                        title: "Warning!", 
                        text: res.msgtext, 
                        type: "warning", 
                        confirmButtonClass: "btn btn-warning", 
                        buttonsStyling: !1 
                    });
                    element.prop('checked', false);
                    element.closest('td').siblings().find('input.txtqun').prop("readonly", false);
                }
            }
        });
    } else {
        element.closest('td').siblings().find('input.txtqun').prop("readonly", false);
    }
    calculateCost();
});

</script>
