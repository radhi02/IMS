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
                                <h6>{{$edit_vendor->vendor_name}}</h6>
                            </li>
                            <li>
                                <h4>code</h4>
                                <h6>{{$edit_vendor->vendor_code}}</h6>
                            </li>
                            <li>
                                <h4>Email</h4>
                                <h6>{{$edit_vendor->vendor_email}}</h6>
                            </li>
                            <li>
                                <h4>Number</h4>
                                <h6>{{$edit_vendor->vendor_phone}}</h6>
                            </li>
                            <li>
                                <h4>Contact Person Name</h4>
                                <h6>{{$edit_vendor->vendor_contactperson}}</h6>
                            </li>
                            <li>
                                <h4>Location</h4>
                                <h6>{{$edit_vendor->Location}}</h6>
                            </li>
                            <li>
                                <h4> Street</h4>
                                <h6>{{$edit_vendor->vendor_street}}</h6>
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
                                <h4>zipcode </h4>
                                <h6>{{$edit_vendor->vendor_zipcode}}</h6>
                            </li>
                            <li>
                                <h4>GST No.</h4>
                                <h6>{{$edit_vendor->vendor_GST}}</h6>
                            </li>
                            <li>
                                <h4>PAN No.</h4>
                                <h6>{{$edit_vendor->vendor_PAN}}</h6>
                            </li>
                            <li>
                                <h4>Type</h4>
                                <h6>{{$edit_vendor->vendor_type}}</h6>
                            </li>
                            <li>
                                <h4>MSME No.</h4>
                                <h6>{{$edit_vendor->vendor_MSME_number}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>
                                @if($edit_vendor->vendor_status == "Active")
                                    <span class="badges bg-lightgreen"> {{$edit_vendor->vendor_status}}</span>
                                @else <span class="badges bg-lightred"> {{$edit_vendor->vendor_status}}</span>
                                @endif
                                </h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection