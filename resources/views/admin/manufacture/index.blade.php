@extends('layouts.app')
@section('content')
<script>
@if(Session::has('msg') != '')
Swal.fire(
    'Success!',
    '{{ Session::has("msg") ? Session::get("msg") : '
    ' }}',
    'success'
)
@endif
</script>
<?php $bageStatus = ['Pending'=>'bg-darkgrey','Open'=>'bg-darkgreen','Finish'=>'bg-darkorange','Instore'=>'bg-darkblue','Inprocess'=>'bg-darkyellow'] ?>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Manufacturing List</h4>
            <h6>Manage your manufacturing order</h6>
        </div>
        <!-- <div class="page-btn">
            <a href="{{route('manufacture.create')}}" class="btn btn-added"><img src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Create New Manufacturing Order</a>
        </div> -->
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="{{URL::asset('admin_asset/img/icons/search-white.svg')}}"
                                alt="img"></a>
                    </div>
                    <!-- <div class="col-lg col-sm-6 col-12">
                        <div style="margin-left: 10px; width: 200px">
                            <select class="select statuschange" id="statuschange">
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Complete">Complete</option>
                                <option value="Processing">Processing</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkboxs">
                                    <input type="checkbox" id='select-all'>
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Code</th>
                            <th>Sales Order</th>
                            <!-- <th>Order Date</th> -->
                            <th>Product</th>
                            <th>Delivery Date</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ManufactureOrder as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox" class="checkbox select-all-sub" data-id="{{$values->id}}"
                                        name="checks[]">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>{{$values->code}}</td>
                            <td><a class="me-3" href="{{route('manufacture.create',['id'=>$values->s_id])}}">{{$values->s_code}} </a> </td>
                            <!-- <td>{{ShowNewDateFormat($values->s_order_date)}}</td> -->
                            <td>{{$values->p_name}} </td>
                            <td>{{ShowNewDateFormat($values->delivery_date)}} </td>
                            <td>{{$values->quantity}} </td>
                            <td id="status{{$values->id}}"><span
                                    class="badges {{$bageStatus[$values->status]}}">{{$values->status}}</span></td>
                            <td>
                                <a class="me-3" href="{{route('manufacture.show',$values->id)}}" title="BOM">
                                    <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="img">
                                </a>
                                @if( ($values->demand_status == "Not Started" || $values->demand_status == "In Progress") && ($values->status == "Pending" || $values->status == "Open"))
                                <a class="me-3" href="{{route('demandnote.create',['id'=>$values->id])}}" title="Demand Note">
                                    <img src="{{URL::asset('admin_asset/img/icons/dashred.svg')}}" alt="img" style=" height: 24px; width: 24px; ">
                                </a>
                                @elseif($values->status == "Processing")
                                <a class="me-3" href="{{route('demandnote.show',$values->id)}}" title="Demand Note">
                                    <img src="{{URL::asset('admin_asset/img/icons/dashgreen.svg')}}" alt="img" style=" height: 24px; width: 24px; ">
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(".statuschange").select2({
    placeholder: "Select a status",
    allowClear: true
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(".Delete").click(function() {
    removethis($(this).data('id'), "{{url('salesorder_destroy')}}", "{{ csrf_token() }}");
});
$('.statuschange').change(function() {
    changeAllStatus($(this).val(), "{{route('salesorder.status')}}", "{{ csrf_token() }}");
});
</script>
@endsection