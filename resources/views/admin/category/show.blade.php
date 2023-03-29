@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Category Details</h4>
            <h6>Full details of a category</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h4>Category Name</h4>
                                <h6>{{$Category->name}}</h6>
                            </li>
                            <li>
                                <h4>Category Description</h4>
                                <h6>{{$Category->description}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>@if($Category->status == "Active")
                                    <span class="badges bg-lightgreen"> {{$Category->status}}</span>
                                @else <span class="badges bg-lightred"> {{$Category->status}}</span>
                                @endif</h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection