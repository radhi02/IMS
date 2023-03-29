@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Manufacture Order</h4>
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
                <h2>Manufacturing Order : {{saleForManuLastID($SalesOrder->id)}}</h2>
            </div>
            <div class="row" style="margin-bottom: 25px;">
                <div class="col-md-3">
                    <ul class="list-unstyled mb-0">
                        <li style="color: #7367F0;">Sale Order #</li>
                        <li>{{$SalesOrder->code}}</li>
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
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>Sr. No</th></th>
                                <th>Product</th>
                                <th>Ordered</th>
                                <th>Instock</th>
                                <th>Required</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <td><label>{{$loop->iteration}}</label></td>
                                <td>{{ProductDetail($op1->product_id)->name}}
                                    <?php $list = makelist($SalesOrder->id,$op1->product_id); $tmpt = 0; ?>
                                    @if(!empty($list))
                                    <ul class="list-unstyled mb-0">
                                        @foreach($list as $l)
                                        <li>
                                            <span style="color: #7367F0;">{{$l->code}}</span>
                                            <span>Date : {{$l->delivery_date}}</span>
                                            <span>| Quantity : {{$l->quantity}}</span>
                                        </li>
                                        <?php $tmpt = $tmpt + $l->quantity;?>
                                        @endforeach
                                    </ul>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-group" style="margin-bottom: 0px;">{{$oqun}}</div>
                                </td>
                                <td>
                                    <div class="form-group" style="margin-bottom: 0px;">{{$squn}}</div>
                                </td>
                                <td>
                                    <div class="form-group" style="margin-bottom: 0px;">@if($squn < $oqun){{$rqun}}@else
                                    0 @endif</div>
                                </td>
                                <td>@if($squn < $oqun && $tmpt < $rqun) <button type="button" data-modal-target="#createmanufacture"
                                        class="btn btn-primary btn-sm btn-make" data-qun="{{($tmpt!=0)?($rqun-$tmpt):$rqun}}"
                                        data-pid="{{$op1->product_id}}" data-unique="{{$op1->unique_id}}">
                                        Make</button>@endif
                                </td>
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
</div><!-- /.content -->


<div class="modal fade" id="createmanufacture" tabindex="-1" aria-labelledby="createmanufacture" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="ProductForm" method="post" action="{{route('manufacture.store')}}" enctype="multipart/form-data">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Manufacturing Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <strong>Product :</strong>
                        </div>
                        <div class="col-md-8" id="tblpname">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary btn-sm btn-addrow"                              data-scode="{{$SalesOrder->code}}">Add Row</button> </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive mt-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th></th>
                                        <th>QTY</th>
                                        <th>Delivery Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductList">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 float-md-right">
                            <div class="total-order">
                                <ul>
                                    <li>
                                        <h4>Required Quantity</h4>
                                        <h5 id="divtotal"></h5>
                                    </li>
                                    <li>
                                        <h4>Total Quantity</h4>
                                        <div class="form-group" style="margin-bottom: 0px;width: 50%;" >
                                            <input type="text" name="checkq" id="checkq" readonly="readonly" style="text-align: right; padding: 10px 10px; ">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="sales_order_id" value="{{$SalesOrder->id}}">
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="hidden" name="totalquantity" id="totalquantity">
                    <input type="hidden" name="uniqueid" id="uniqueid">
                    <button type="submit" class="btn btn-submit">Submit</button>
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>

</div>

<script>

</script>

@endsection