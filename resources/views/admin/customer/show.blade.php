@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Customer Details</h4>
            <h6>Full details of a customer</h6>
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
                                <h6>{{$customer->customer_name}}</h6>
                            </li>
                            <li>
                                <h4>Code</h4>
                                <h6>{{$customer->customer_code}}</h6>
                            </li>
                            <li>
                                <h4>Email</h4>
                                <h6>{{$customer->customer_email}}</h6>
                            </li>
                            <li>
                                <h4>Number</h4>
                                <h6>{{$customer->customer_phone}}</h6>
                            </li>
                            <li>
                                <h4>Contact Person Name</h4>
                                <h6>{{$customer->customer_contactperson}}</h6>
                            </li>
                            <li>
                                <h4>Location</h4>
                                <h6>{{$customer->Location}}</h6>
                            </li>
                            <li>
                                <h4> Street</h4>
                                <h6>{{$customer->customer_street}}</h6>
                            </li>
                            <li>
                                <h4>City</b> </h6>
                                <h6>{{$Cities->name}}</h6>
                            </li>
                            <li>
                                <h4>State</h4>
                                <h6>{{$States->name}}</h6>
                            </li>
                            <li>
                                <h4>Country </h4>
                                <h6>{{$Countries->name}}</h6>
                            </li>
                            <li>
                                <h4>Zipcode</h4>
                                <h6>{{$customer->customer_zipcode}}</h6>
                            </li>
                            <li>
                                <h4>GST NO.</h4>
                                <h6>{{$customer->customer_GST}}</h6>
                            </li>
                            <li>
                                <h4>PAN Card NO.</h4>
                                <h6>{{$customer->customer_PAN}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>
                                @if($customer->customer_status == "Active")
                                    <span class="badges bg-lightgreen"> {{$customer->customer_status}}</span>
                                @else <span class="badges bg-lightred"> {{$customer->customer_status}}</span>
                                @endif
                                </h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="slider-product-details">
                        <div class="owl-carousel owl-theme product-slide">
                            <div class="slider-product">
                                <img src="assets/img/product/product69.jpg" alt="img">
                                <h4>macbookpro.jpg</h4>
                                <h6>581kb</h6>
                            </div>
                            <div class="slider-product">
                                <img src="assets/img/product/product69.jpg" alt="img">
                                <h4>macbookpro.jpg</h4>
                                <h6>581kb</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>
@endsection