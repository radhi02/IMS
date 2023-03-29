@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Material Category Details</h4>
            <h6>Full details of a material category</h6>
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
                                <h6>{{$MaterialCategory->name}}</h6>
                            </li>
                            <li>
                                <h4>Category Description</h4>
                                <h6>{{$MaterialCategory->description}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>@if($MaterialCategory->status == "Active")
                                    <span class="badges bg-lightgreen"> {{$MaterialCategory->status}}</span>
                                @else <span class="badges bg-lightred"> {{$MaterialCategory->status}}</span>
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