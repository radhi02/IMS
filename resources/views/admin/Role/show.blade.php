@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Role Details</h4>
            <h6>Full details of a role</h6>
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
                                <h6>{{$show_role->name}}</h6>
                            </li>
                            <li>
                                <h4>Description</h4>
                                <h6>{{$show_role->description}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>@if($show_role->status == "Active")
                                    <span class="badges bg-lightgreen"> {{$show_role->status}}</span>
                                @else <span class="badges bg-lightred"> {{$show_role->status}}</span>
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