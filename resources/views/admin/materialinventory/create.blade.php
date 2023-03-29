@extends('layouts.app')
@section('content')
<style>
    #tbodyProductList .form-group {
        margin-bottom:0px;
    }
</style>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Receive Raw Material</h4>
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
    <form id="PurchaseOrderForm" method="post" action="{{route('materialinventory.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="card-sales-split" style="margin-bottom: 25px;">
                    <h2>Purchase Order : {{$PurchaseOrder->code}}</h2>
                </div>
                <div class="row" style="margin-bottom: 25px;">
                    <div class="col-md-4">
                        <ul class="list-unstyled mb-0">
                            <li style="color: #7367F0;">Purchase Order Date</li>
                            <li>{{ShowNewDateFormat($PurchaseOrder->date)}}</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-unstyled mb-0">
                            <li style="color: #7367F0;">Purchase Order Description</li>
                            <li>{{$PurchaseOrder->description}}</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
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
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Ordered QTY</th>
                                    <th>Remained QTY</th>
                                    <th>Receive QTY</th>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id='select-all'>
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductList">
                                @if(count($PurchaseOrderMaterial) > 0)
                                @foreach($PurchaseOrderMaterial as $op1)
                                    @if($op1['remained_quantity'] > 0)
                                    <tr>
                                        <td>{{RawMaterialName($op1['raw_material_id'])->name}}</td>
                                        <td>{{RawMaterialName($op1['raw_material_id'])->code}}</td>
                                        <td>{{$op1['quantity']}}</td>
                                        <td>{{$op1['remained_quantity']}}</td>
                                        <td>
                                            <div class="form-group" style="margin-bottom: 0px;">
                                            <input type="number" style="padding:5px 5px" name="pmrdata[{{$op1['id']}}][received_quantity]" min="1" class="txtqun" value="{{$op1['remained_quantity']}}">
                                            </div>
                                        </td>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" class="checkbox select-all-sub" data-id="{{$op1['id']}}" name="pmrdata[{{$op1['id']}}][chk]">
                                                <span class="checkmarks"></span>
                                            </label>
                                            <input type="hidden" name="pmrdata[{{$op1['id']}}][quantity]" value="{{$op1['remained_quantity']}}">
                                            <input type="hidden" name="pmrdata[{{$op1['id']}}][purchase_order_material_id]" value="{{$op1['id']}}">
                                            <input type="hidden" name="pmrdata[{{$op1['id']}}][purchase_order_id]" value="{{$PurchaseOrder->id}}">
                                            <input type="hidden" name="pmrdata[{{$op1['id']}}][raw_material_id]" value="{{$op1['raw_material_id']}}">
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="purchaseOrderId" value="{{$PurchaseOrder->id}}">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="{{route('materialinventory.index')}}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div><!-- /.content -->
<script>
$(function() {
    $('#select-all').prop('checked', true);
    $('.select-all-sub').prop('checked', true);
});
</script>
@endsection