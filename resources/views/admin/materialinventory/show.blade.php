@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Received Raw Material Details</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-sales-split" style="margin-bottom: 25px;">
                        <h2>Purchase Order : {{$PurchaseOrder->code}}</h2>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th></th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Ordered QTY</th>
                                        <th>Received QTY</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductList">
                                    @if(!empty($PurchaseMaterialReceived))
                                    @foreach($PurchaseMaterialReceived as $nt1)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{RawMaterialName($nt1['raw_material_id'])->name}}</td>
                                        <td>{{RawMaterialName($nt1['raw_material_id'])->code}}</td>
                                        <td>{{$nt1['quantity']}}</td>
                                        <td>{{$nt1['received_quantity']}}</td>
                                        <td>{{ShowNewDateFormat($nt1['date'])}} </td>
                                    </tr>
                                    @endforeach
                                    @endif
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


