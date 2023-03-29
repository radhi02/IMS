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
<?php $bageStatus = ['Open'=>'bg-lightyellow','Close'=>'bg-lightgreen','Partial Paid'=>'bg-lightblue','Overdue'=>'bg-lightred'] ?>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Invoices List</h4>
            <h6>Manage your Invoices</h6>
        </div>
        <div class="page-btn">
            <a href="{{route('invoice.create')}}" class="btn btn-added"><img src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Create New Invoice</a>
        </div>
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
                            <th>Invoice No.</th>
                            <th>Sales Order No.</th>
                            <th>Invoice Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Due Amount</th>
                            <th>Status</th>
                            <th style="width:150px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Invoicedata as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$values->code}} </td>
                            <td>{{$values->scode}} </td>
                            <td>{{ShowNewDateFormat($values->created_at)}} </td>
                            <td>{{$values->customer_name}} </td>
                            <td>{{$values->base_grandtotal}} </td>
                            <td>{{$values->due_amount}} </td>
                            <td id="status{{$values->id}}">
                                <span class="badges {{$bageStatus[$values->status]}}">{{$values->status}}</span></td>
                            <td>
                                <div class="action_btn_box">
                                    <a title="pdf" href="{{route('invoice.download',$values->id)}}" target="_blank" ><img src="{{URL::asset('admin_asset/img/icons/pdf.svg')}}" alt="img"></a>
                                    @if($values->status != "Close")
                                        <a class="btn btn-block btn-info btn-sm" href="{{route('invoice.createInvoicePayment',$values->id)}}" title="Receive Payment">
                                            Receive Payment
                                        </a>
                                    @else 
                                    <a class="me-3" href="{{route('invoice.show',$values->id)}}" title="Invoice Payment Details" style="margin-left: 10px;">
                                        <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="img">
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection