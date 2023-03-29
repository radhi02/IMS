@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Bank Details</h4>
            <h6>Full details of a bank</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h4>Bank  Name </h4>
                                <h6>{{$Banks->BName }}</h6>
                            </li>
                            <li>
                                <h4>FISC</h4>
                                <h6>{{ $Banks->BIFSC }}</h6>
                            </li>
                            <li>
                                <h4>SIFNO</h4>
                                <h6>{{ $Banks->BSWIFTCODE }}</h6>
                            </li>
                            <li>
                                <h4>Branch</h4>
                                <h6>{{ $Banks->Branch }}</h6>
                            </li>
                            <li>
                                <h4>MICR</h4>
                                <h6>{{ $Banks->BMICR }}</h6>
                            </li>
                            <li>
                                <h4>Status</h4>
                                <h6>
                                @if($Banks->status == "Active")
                                    <span class="badges bg-lightgreen"> {{$Banks->status}}</span>
                                @else <span class="badges bg-lightred"> {{$Banks->status}}</span>
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