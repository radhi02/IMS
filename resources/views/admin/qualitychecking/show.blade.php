@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Manufacture Order Details</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-sales-split" style="margin-bottom: 25px;">
                        <h2>BOM Detail : {{$ManufactureOrder->code}}</h2>
                    </div>
                    <div class="row">
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th></th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>QTY</th>
                                        <th>UNIT</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductList">
                                    @if(isset($ManufactureOrder) && !empty($ManufactureOrder->bom_detail))
                                    @php $tmpOrderBOM = json_decode($ManufactureOrder->bom_detail,true); @endphp
                                    @if(count($tmpOrderBOM) > 0)
                                    @foreach($tmpOrderBOM as $bom1)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{RawMaterialName($bom1['id'])->name}}</td>
                                        <td>{{RawMaterialName($bom1['id'])->code}}</td>
                                        <td>{{$bom1['quantity']}}</td>
                                        <td>{{UnitName($bom1['unitid'])->unit_name}}</td>
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
        </div>
    </div>
</div>
@endsection