@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Receive Invoice Payment</h4>
            <h6>Manage your Invoice Payment</h6>
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
    <form id="frmreceivepayment" method="post" action="{{route('invoice.storeinvoicepayment')}}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div style="margin-bottom: 25px; ">
                    <h6>Receive Payment</h6>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="received_amount">Amount<span class="text-danger">*</span></label>
                            <input type="number" step="any" name="received_amount" id="received_amount" value="{{$InvoiceData->due_amount}}" placeholder="Amount">
                        </div>
                    </div>
                    <div class="col-sm-4 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="bank_details">Customer Bank Details<span class="text-danger">*</span></label>
                            <input type="text" name="bank_details" id="bank_details" placeholder="Bank Details">
                        </div>
                    </div>
                    <div class="col-sm-4 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="reference_no">Customer Cheque No. / Transaction No.<span class="text-danger">*</span></label>
                            <input type="text" name="reference_no" id="reference_no" placeholder="Cheque No. / Transaction No.">
                        </div>
                    </div>
                    <div class="col-sm-4 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label>Select Our Bank<span class="text-danger">*</span></label>
                            <select id="company_bank_id" name="company_bank_id" class="tagging">
                                <option value="">Select Bank</option>
                                @foreach ($bank as $data)
                                <option value="{{$data->id}}">
                                    Name : {{$data->BName}} , Account No: {{$data->Baccount}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <input type="hidden" name="invoice_id" id="invoice_id" value="{{$InvoiceData->id}}">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('invoice.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if($Paymentdata != '') 
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                <div class="row" style="margin-bottom: 25px; ">
                    <div class="col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li style="color: #7367F0;">Customer Info</li>
                            <li>{{$InvoiceData->customer_name}}</li>
                            <li>{{$InvoiceData->customer_email}}</li>
                            <li>{{$InvoiceData->customer_phone}}</li>
                            <li>{{$InvoiceData->customer_street}} , {{$InvoiceData->cityname}}</li>
                            <li>{{$InvoiceData->statename}} , {{$InvoiceData->countryname}}</li>
                            <li>{{$InvoiceData->customer_zipcode}}</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li style="color: #7367F0;">Sale Order Date</li>
                            <li>{{ShowNewDateFormat($InvoiceData->sales_order_date)}}</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li style="color: #7367F0;">Sale Order</li>
                            <li>{{$InvoiceData->scode}}</li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li style="color: #7367F0;">Invoice Order</li>
                            <li>{{$InvoiceData->code}}</li>
                        </ul>
                    </div>
                </div>
                <div style="margin-bottom: 25px; ">
                    <h6>Invoice Product Details</h6>
                </div>
                <div class="row" style="margin-bottom: 25px; ">
                    <div class="table-responsive mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th style=" width: 50%; ">Product</th>
                                    <th>Invoice Date</th>
                                    <th>QTY</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductList">
                                @if(isset($InvoiceData) && !empty($InvoiceData->order_products))
                                @php $tmpOrderProducts = json_decode($InvoiceData->order_products); @endphp
                                @if(count($tmpOrderProducts) > 0)
                                @foreach($tmpOrderProducts as $op1)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ProductDetail($op1->product_id)->name}}</td>
                                    <td>{{ShowNewDateFormat($InvoiceData->created_at)}}</td>
                                    <td>{{$op1->quantity}}</td>
                                    <td>₹ {{$op1->base_price}}</td>
                                    <td>₹ {{$op1->base_total}}</td>
                                </tr>
                                @endforeach
                                @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                    <div style="margin-bottom: 25px; ">
                        <h6>Payment Details</h6>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Recieved Amount</th>
                                        <th>Company Bank Name</th>
                                        <th>Customer Bank Name</th>
                                        <th>Customer Cheque No. / Transaction No.</th>
                                        <th>Recieved Date</th>
                                        <th>Recieved by</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductList">
                                    @foreach($Paymentdata as $k=>$values)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$values['received_amount']}} </td>
                                        <td>{{$values['BName']}} </td>
                                        <td>{{$values['bank_details']}} </td>
                                        <td>{{$values['reference_no']}} </td>
                                        <td>{{ShowNewDateFormat($values['created_at'])}} </td>
                                        <td>{{$values['first_name']}} {{$values['last_name']}} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    $('#frmreceivepayment').validate({
        rules: {
            received_amount: { required: true },
            bank_details: { required: true },
            reference_no: { required: true },
            company_bank_id: { required: true },
        },
        messages: {
            received_amount: { required: "Please enter amount" },
            bank_details: { required: "Please enter bank details" },
            company_bank_id: { required: "Please select bank" },
            reference_no: { required: "Please enter reference no." },
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