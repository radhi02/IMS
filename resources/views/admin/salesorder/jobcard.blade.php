@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>JobCard Add</h4>
            <h6>Create new jobcard</h6>
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

        @if(!empty($SalesOrder->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="card-sales-split" style="margin-bottom: 25px;">
                    <h2>Sale Detail : {{$SalesOrder->code}}</h2>
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
                                    <th>Add to BOM</th>
                                    <th>Ordered QTY</th>
                                    <th>Instock QTY</th>
                                    <th>Required QTY</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductList">
                                @if(isset($SalesOrder) && !empty($SalesOrder->order_products))
                                @php $tmpOrderProducts = json_decode($SalesOrder->order_products); @endphp
                                @if(count($tmpOrderProducts) > 0)
                                @foreach($tmpOrderProducts as $op1) 
                                    @php 
                                        $oqun = $op1->quantity; 
                                        $squn = ProductDetail($op1->product_id)->quantity; 
                                        $rqun = $oqun-$squn;
                                    @endphp
                                <tr>
                                    <td>
                                        @if($squn < $oqun)
                                        <label class="checkboxs">
                                            <input type="checkbox" class="checkbox select-all-sub" name="checks[]" data-pid="{{$op1->product_id}}" data-qun="{{$rqun}}" data-divid="tbldiv-{{$loop->iteration}}">
                                            <span class="checkmarks"></span>
                                        </label>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin-bottom: 0px;">{{$oqun}}</div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin-bottom: 0px;">{{$squn}}</div>
                                    </td>
                                    <td>@if($squn < $oqun)
                                        <div class="form-group" style="margin-bottom: 0px;">{{$rqun}}</div>@endif
                                    </td>
                                    <td>{{ProductDetail($op1->product_id)->name}}</td>
                                    <input type="hidden" name="rawids[]" value="{{$op1->product_id}}">
                                </tr>
                                @endforeach
                                @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
           </div>
        </div>
        <form id="SalesOrderForm" method="post"
        action=" @if(!empty($SalesOrder->id)!=0){{route('salesorder.update',$SalesOrder->id)}}@else{{route('salesorder.store')}}@endif"
        enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                <div class="row" id="results" style="margin-bottom: 25px;"></div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="{{route('salesorder.index')}}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div><!-- /.content -->
<script>

$(function() {
    $('#toggle_btn').click();

    function formatitemresult(item) {
        var selectionText = item.text.split("|");
        if (Array.isArray(selectionText)) {
            if (selectionText[3] > 0) {
                stockstatus = "IN STOCK";
            } else {
                stockstatus = "OUT OF STOCK"
            }
            var $returnString = $('<span style="font-weight:700">Name: </span><span>' + selectionText[0] +
                '</span><span style="float:right;font-weight:700">' + stockstatus +
                '</span></br><span style="font-weight:700">Code: </span><span>' + selectionText[1] +
                '</span><span style="font-weight:700">Rate: </span><span>₹.' + selectionText[2] +
                '</span><span style="float:right"> ' + selectionText[3] +
                ' </span><span style="font-weight:700;float:right">Quantity: </span>');
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
        customer_id: {
            required: true
        },
        status: {
            required: true
        },
        'rawqun[]': {
            required: true
        },
        'rawprice[]': {
            required: true
        },
        'rawamtwithtax[]': {
            required: true
        },
        'rawids[]': {
            required: true
        },
        'rawdelivery[]': {
            required: true
        },
    },
    messages: {
        customer_id: {
            required: "Please select customer"
        },
        status: {
            required: "Please select status"
        },
        "rawqun[]": {
            required: "Please enter quantity"
        },
        "rawprice[]": {
            required: "Please enter price"
        },
        "rawamtwithtax[]": {
            required: "Please enter amount"
        },
        "rawids[]": {
            required: "Please select product"
        },
        "rawdelivery[]": {
            required: "Please enter delivery date"
        },
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

$('#annexureList').on('change', function() {
    var ids = $("#annexureList").select2('val');
    $("#printThis").html('');
    $.ajax({
        url: "{{ route('quote.fetchAnnexure') }}",
        type: "POST",
        data: {
            ids: ids,
            _token: '{{csrf_token()}}'
        },
        success: function(data) {
            $("#printThis").html(data);
        },
    });
});

$(".select-all-sub").click(function(){
    var pid = $(this).data('pid');
    var qun = $(this).data('qun');
    var divid = $(this).data('divid');
    if($(this).is(':checked')){
        $.ajax({
            url: "{{ route('addToBOM') }}",
            type: "POST",
            data: {
                pid: pid,
                qun: qun,
                divid: divid,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(html) {
                $("#results").append(html.data);
            },
        })
    } else {
        $("#"+divid).remove();
    }
});
</script>

@endsection