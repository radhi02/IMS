@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Unit Details</h4>
            <h6>Full details of a unit</h6>
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
                                <h6>{{$Unit->unit_name}}</h6>
                            </li>
                            <li>
                                <h4>Description</h4>
                                <h6>{{$Unit->description}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>
                                @if($Unit->status == "Active")
                                    <span class="badges bg-lightgreen"> {{$Unit->status}}</span>
                                @else <span class="badges bg-lightred"> {{$Unit->status}}</span>
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