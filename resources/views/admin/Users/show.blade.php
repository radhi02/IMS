@extends('layouts.app')
@section('content')

@php
    $img="no_preview.png";
    if(!empty($edit_users->Image))
    {
        $filename = public_path('Admin/Users/'. $edit_users->Image);
        if($edit_users->Image != '' && file_exists($filename)) $img=$edit_users->Image;
    }
@endphp
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>User Details</h4>
            <h6>Full details of a user</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h6 colspan="3"><strong>Personal Details :</strong></h6>
                            </li>
                            <li>
                                <h4>First Name</h4>
                                <h6>{{$edit_users->first_name}}</h6>
                            </li>
                            <li>
                                <h4>Last Name</h4>
                                <h6>{{$edit_users->last_name}}</h6>
                            </li>
                            <li>
                                <h4>Email</h4>
                                <h6>{{$edit_users->email}}</h6>
                            </li>
                            <li>
                                <h4>Role</h4>
                                <h6>{{checkRole($edit_users->Role)}}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>
                                @if($edit_users->user_status == "Active")
                                    <span class="badges bg-lightgreen"> {{$edit_users->user_status}}</span>
                                @else <span class="badges bg-lightred"> {{$edit_users->user_status}}</span>
                                @endif
                                </h6>
                            </li>
                            <li>
                                <h4>Phone</h4>
                                <h6>{{$edit_users->Phone}}</h6>
                            </li>
                            <li>
                                <h4>Date of Joining</h4>
                                <h6>{{$edit_users->doj}}</h6>
                            </li>
                            <li>
                                <h4>Gender</h4>
                                <h6>{{$edit_users->gender}}</h6>
                            </li>
                            <li>
                                <h4>Marital Status</h4>
                                <h6>{{$edit_users->marital_status}}</h6>
                            </li>
                            <li>
                                <h4>Blood Group</h4>
                                <h6>{{$edit_users->blood_group}}</h6>
                            </li>
                            <li>
                                <h6 colspan="3"><strong>Temporary Address :</strong></h6>
                            </li>
                            <li>
                                <h4>Country</h4>
                                <h6>{{getcountry($edit_users->Country)}}</h6>
                            </li>
                            <li>
                                <h4>State</h4>
                                <h6>{{getstate($edit_users->State)}}</h6>
                            </li>
                            <li>
                                <h4>City</h4>
                                <h6>{{getcities($edit_users->city)}}</h6>
                            </li>
                            <li>
                                <h4>Address</h4>
                                <h6>{{$edit_users->Address}}</h6>
                            </li>
                            <li>
                                <h4>Pincode</h4>
                                <h6>{{$edit_users->pincode}}</h6>
                            </li>
                            <li>
                                <h6 colspan="3"><strong>Permanent Address :</strong></h6>
                            </li>
                            <li>
                                <h4>Country</h4>
                                <h6>{{getcountry($edit_users->Per_Country)}}</h6>
                            </li>
                            <li>
                                <h4>State</h4>
                                <h6>{{getstate($edit_users->Per_State)}}</h6>
                            </li>
                            <li>
                                <h4>City</h4>
                                <h6>{{getcities($edit_users->Per_city)}}</h6>
                            </li>
                            <li>
                                <h4>Address</h4>
                                <h6>{{$edit_users->Per_Address}}</h6>
                            </li>
                            <li>
                                <h4>Pincode</h4>
                                <h6>{{$edit_users->Per_pincode}}</h6>
                            </li>
                            <li>
                                <h4>Department</h4>
                                <h6>{{getdepartment($edit_users->department_id)}}</h6>
                            </li>
                            <li>
                                <h6 colspan="3"><strong>Bank Details :</strong></h6>
                            </li>
                            <li>
                                <h4>Bank Name</h4>
                                <h6>{{$edit_users->bank_name}}</h6>
                            </li>
                            <li>
                                <h4>Bank Swift Code</h4>
                                <h6>{{$edit_users->bank_swiftcode}}</h6>
                            </li>
                            <li>
                                <h4>Branch Name</h4>
                                <h6>{{$edit_users->bank_branch}}</h6>
                            </li>
                            <li>
                                <h4>Account No.</h4>
                                <h6>{{$edit_users->acc_number}}</h6>
                            </li>
                            <li>
                                <h4>Account Name.</h4>
                                <h6>{{$edit_users->acc_name}}</h6>
                            </li>
                            <li>
                                <h4>Account IFSC Code</h4>
                                <h6>{{$edit_users->acc_ifsccode}}</h6>
                            </li>
                            <li>
                                <h4>PAN Card No.</h4>
                                <h6>{{$edit_users->user_PAN}}</h6>
                            </li>
                            <li>
                                <h4>Aadhaar Card No.</h4>
                                <h6>{{$edit_users->user_ADHAR}}</h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="slider-product-details">
                        <div class="owl-carousel owl-theme product-slide">
                            <div class="slider-product">
                                <img src="{{ URL::asset('Admin/Users/'. $img) }}" alt="img">
                                <h4>{{$edit_users->first_name}} {{$edit_users->last_name}}</h4>
                                <h6>{{checkRole($edit_users->Role)}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection