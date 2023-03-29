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
                                <h6>{{$RawMaterial->name}}</h6>
                            </li>
                            <li>
                                <h4>Description</h4>
                                <h6>{{$RawMaterial->description}}</h6>
                            </li>
                            <li>
                                <h4>Code</h4>
                                <h6>{{$RawMaterial->code}}</h6>
                            </li>
                            <li>
                                <h4>HSN Code</h4>
                                <h6>{{$RawMaterial->HSN_CODE}}</h6>
                            </li>
                            <li>
                                <h4>GST (%)</h4>
                                <h6>{{$RawMaterial->GST}}</h6>
                            </li>
                            <li>
                                <h4>Category</h4>
                                <h6>{{$Category->name}}</h6>
                            </li>
                            <li>
                                <h4>Unit</h4>
                                <h6>{{$Unit->unit_name}}</h6>
                            </li>
                            <li>
                                <h4>Quantity</h4>
                                <h6>{{$RawMaterial->quantity}}</h6>
                            </li>
                            <li>
                                <h4>Location</h4>
                                <h6>{{implode(',',json_decode($RawMaterial->location))}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>
                                @if($RawMaterial->status == "Active")
                                    <span class="badges bg-lightgreen"> {{$RawMaterial->status}}</span>
                                @else <span class="badges bg-lightred"> {{$RawMaterial->status}}</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection