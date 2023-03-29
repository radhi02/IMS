@extends('layouts.app')
@section('content')
@php
$PT = [
    "Advance" => "Advance",    
    "Immediate" => "Immediate",    
    "7Days" => "7 Days From Date of Invoice",    
    "15Days" => "15 Days From Date of Invoice",    
    "30Days" => "30 Days From Date of Invoice",    
    "45Days" => "45 Days From Date of Invoice",    
]
@endphp
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sale Order Details</h4>
            <h6>View sale order details</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-sales-split" style="margin-bottom: 25px;">
                        <h2>Sale Detail : {{$SalesOrder->code}}</h2>
                    </div>
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Sale Order Date</li>
                                <li>{{ShowNewDateFormat($SalesOrder->order_date)}}</li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Invoice Terms and Conditions</li>
                                <li>{{br2nl($SalesOrder->description)}}</li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Order Status</li>
                                <li>{{$SalesOrder->status}}</li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Payment Terms</li>
                                <li>{{$PT[$SalesOrder->payment_terms]??'-'}}</li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Mode of Delivery</li>
                                <li>{{$SalesOrder->delivery_mode}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>QTY</th>
                                        <th>Rate (₹)</th>
                                        <th>Amount (₹)</th>
                                        <th>Tax (%)</th>
                                        <th>Total (₹)</th>
                                        <th>Delivery Date</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductList">
                                    @if(isset($SalesOrder) && !empty($SalesOrder->order_products))
                                    @php $tmpOrderProducts = json_decode($SalesOrder->order_products); @endphp
                                    @if(count($tmpOrderProducts) > 0)
                                    @foreach($tmpOrderProducts as $op1)
                                    <tr>
                                        <td>{{ProductDetail($op1->product_id)->name}}</td>
                                        <td>{{$op1->quantity}}</td>
                                        <td>{{$op1->base_price}}</td>
                                        <td>{{$op1->base_subtotal_withoutax}}</td>
                                        <td>{{$op1->base_tax_per}}</td>
                                        <td>{{$op1->base_total}}</td>
                                        <td>{{ShowNewDateFormat($op1->delivery_date)}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th><label id="tfootquantity">{{$SalesOrder->base_total_quantity}}</label></th>
                                        <th><label id="tfootrate">{{$SalesOrder->base_total_rate}}</label></th>
                                        <th><label id="tfootamt">{{$SalesOrder->base_subtotal}}</label></th>
                                        <th>-</th>
                                        <th><label id="tfoottotal">{{$SalesOrder->base_grandtotal}}</label></th>
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
                                    @if($SalesOrder->stateid == $companyState)
                                    <li class="total ordertax">
                                        <h4>IGST</h4>
                                        <h5>₹ {{($SalesOrder->base_tax_amount)/2}}</h5>
                                    </li>
                                    <li class="total ordertax">
                                        <h4>CGST</h4>
                                        <h5>₹ {{($SalesOrder->base_tax_amount)/2}}</h5>
                                    </li>
                                    @else
                                    <li class="total ordertax">
                                        <h4>SGST</h4>
                                        <h5>₹ {{$SalesOrder->base_tax_amount}}</h5>
                                    </li>
                                    @endif
                                    <li class="total ordertax">
                                        <h4>Order Tax</h4>
                                        <h5>₹ {{$SalesOrder->base_tax_amount}}</h5>
                                    </li>
                                    <li class="total subtotal">
                                    <h4>Sub Total (Tax excluded)</h4>
                                        <h5>₹ {{$SalesOrder->base_subtotal}}</h5>
                                    </li>
                                    <li class="total grandtotal">
                                        <h4>Grand Total</h4>
                                        <h5>₹ {{$SalesOrder->base_grandtotal}}</h5>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    $('#toggle_btn').click();
});
</script>

@endsection