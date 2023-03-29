@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Purchase Order Details</h4>
            <h6>View purchase order details</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-sales-split" style="margin-bottom: 25px;">
                        <h2>Purchase Detail : {{$PurchaseOrder->code}}</h2>
                    </div>
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Vendor Info</li>
                                <li>{{$PurchaseOrder->vendor_name}}</li>
                                <li>{{$PurchaseOrder->vendor_email}}</li>
                                <li>{{$PurchaseOrder->vendor_phone}}</li>
                                <li>{{$PurchaseOrder->vendor_street}} , {{$PurchaseOrder->cityname}}</li>
                                <li>{{$PurchaseOrder->statename}} , {{$PurchaseOrder->countryname}}</li>
                                <li>{{$PurchaseOrder->vendor_zipcode}}</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Purchase Order Date</li>
                                <li>{{ShowNewDateFormat($PurchaseOrder->date)}}</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Terms and Conditions</li>
                                <li>{{$PurchaseOrder->description}}</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li style="color: #7367F0;">Order Status</li>
                                <li>{{$PurchaseOrder->status}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Rate</th>
                                        <th>QTY</th>
                                        <th>Tax (₹)</th>
                                        <th>Sub Total</th>
                                        <th>Total</th>
                                        <!-- <th>Status</th> -->
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductList">
                                    @if(!empty($PurchaseOrderMaterials))
                                    @foreach($PurchaseOrderMaterials as $op1)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{RawMaterialName($op1['raw_material_id'])->name}}</td>
                                        <td>{{RawMaterialName($op1['raw_material_id'])->code}}</td>
                                        <td>{{$op1['base_price']}}</td>
                                        <td>{{$op1['quantity']}}</td>
                                        <td>{{$op1['base_tax']}}</td>
                                        <td>{{$op1['base_subtotal']}}</td>
                                        <td>{{$op1['base_total']}}</td>
                                        <!-- <td>{{$op1['status']}}</td> -->
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 float-md-right">
                            <div class="total-order">
                                <ul>
                                    @if($PurchaseOrder->stateid == $companyState)
                                    <li class="total ordertax">
                                        <h4>IGST</h4>
                                        <h5>₹ {{($PurchaseOrder->base_tax_amount)/2}}</h5>
                                    </li>
                                    <li class="total ordertax">
                                        <h4>CGST</h4>
                                        <h5>₹ {{($PurchaseOrder->base_tax_amount)/2}}</h5>
                                    </li>
                                    @else
                                    <li class="total ordertax">
                                        <h4>SGST</h4>
                                        <h5>₹ {{$PurchaseOrder->base_tax_amount}}</h5>
                                    </li>
                                    @endif
                                    <li class="total ordertax">
                                        <h4>Order Tax</h4>
                                        <h5>₹ {{$PurchaseOrder->base_tax_amount}}</h5>
                                    </li>
                                    <li class="total subtotal">
                                    <h4>Sub Total (Tax excluded)</h4>
                                        <h5>₹ {{$PurchaseOrder->base_subtotal}}</h5>
                                    </li>
                                    <li class="total grandtotal">
                                        <h4>Grand Total</h4>
                                        <h5>₹ {{$PurchaseOrder->base_grandtotal}}</h5>
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
@endsection