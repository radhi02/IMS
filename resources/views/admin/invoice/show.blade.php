@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Invoice Payment Details</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-sales-split" style="margin-bottom: 25px;">
                        <h2>Invoice Order : {{$InvoiceData->code}}</h2>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Amount</th>
                                        <th>Bank Details</th>
                                        <th>Cheque No. / Transaction No.</th>
                                        <th>Created at</th>
                                        <th>Recieved by</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductList">
                                    @foreach($Paymentdata as $k=>$values)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$values['received_amount']}} </td>
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
</div>
@endsection


