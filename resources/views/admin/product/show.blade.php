@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Raw Material Details</h4>
            <h6>Full details of a raw material</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h4>Name</h4>
                                <h6>{{$Product->name}}</h6>
                            </li>
                            <li>
                                <h4>Code</h4>
                                <h6>{{$Product->sku}}</h6>
                            </li>
                            <li>
                                <h4>Description</h4>
                                <h6>{{$Product->description}}</h6>
                            </li>
                            <li>
                                <h4>Category Name</h4>
                                <h6>{{$Category->name}}</h6>
                            </li>
                            <li>
                                <h4>SubCategory Name</h4>
                                <h6>{{$SubCategory->name}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>
                                @if($Product->status == "Active")
                                    <span class="badges bg-lightgreen"> {{$Product->status}}</span>
                                @else <span class="badges bg-lightred"> {{$Product->status}}</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4>Product BOM</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr. No</th></th>
                                    <th>Name</th>
                                    <th>QTY</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyRawMaterialList">
                                @if(isset($Product) && !empty($Product->product_BOM)) 
                                    @php $tmpBOM = json_decode($Product->product_BOM,true); @endphp
                                    @if(count($tmpBOM) > 0)
                                        @foreach($tmpBOM as $b1)
                                        <tr>
                                            <td><label>{{$loop->iteration}}</label></td>
                                            <td>{{RawMaterialName($b1['id'])->name}}</td>
                                            <td>{{$b1['quantity']}}</td>
                                            <td>{{UnitName($b1['unitid'])->unit_name}}</td>
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
@endsection