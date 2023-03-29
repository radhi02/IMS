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
<?php $bageStatus = ['Pending'=>'bg-lightyellow','Finish'=>'bg-lightgreen','Inissue'=>'bg-lightblue','Indemand'=>'bg-lightpurple','Redemand'=>'bg-lightpurple','WIP'=>'bg-lightgrey','Instore'=>'bg-lightyellow','QCPending'=>'bg-lightyellow','Inprocess'=>'bg-lightblue'] ?>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Rejected Manufactured Order List</h4>
            <h6>Manage your rejected order</h6>
        </div>
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
                            <!-- <th>
                                <label class="checkboxs">
                                    <input type="checkbox" id='select-all'>
                                    <span class="checkmarks"></span>
                                </label>
                            </th> -->
                            <th>Sr. No</th>
                            <th>Code</th>
                            <th>Product</th>
                            <th>Delivery Date</th>
                            <th>Quantity</th>
                            <th style="width: 30%;">Reason for rejection</th>
                            <!-- <th>Approve Quantity</th> -->
                            <th>Status</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ManufactureOrder as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <form action="{{ route('rejectedmo.approveProduct') }}" method="POST">
                                @csrf
                                <!-- <td>
                                    <label class="checkboxs">
                                        <input type="checkbox" class="checkbox select-all-sub" data-id="{{$values->id}}"
                                            name="checks[]">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td> -->
                                <td>{{$loop->iteration}}</td>
                                <td>{{$values->code}}</td>
                                <td>{{$values->p_name}} </td>
                                <td>{{ShowNewDateFormat($values->delivery_date)}} </td>
                                <td>{{$values->check_quantity}} </td>
                                <td>
                                    <?php 
                                    $list = json_decode($values->reason, true);   ?>
                                    @if(!empty($list) )
                                        @if(is_array($list))
                                            @foreach($list as $l)
                                            <ul class="list-unstyled mb-0">
                                                <li  style="border-bottom: 1px solid #E9ECEF;">
                                                    <span style="color: #7367F0;">Reason : </span> {{$l['reason']}}</br>
                                                    <span style="color: #7367F0;">Date : </span> {{$l['created']}}</br>
                                                    <span style="color: #7367F0;">Quantity : </span> {{$l['quantity']}}</br>
                                                </li>
                                            </ul>
                                            @endforeach
                                        @else        
                                            <ul class="list-unstyled mb-0">
                                                <li>
                                                    <span style="color: #7367F0;">Reason : </span> {{$list['reason']}}</br>
                                                    <span style="color: #7367F0;">Date : </span> {{$list['created']}}</br>
                                                    <span style="color: #7367F0;">Quantity : </span> {{$list['quantity']}}</br>
                                                </li>
                                            </ul>
                                        @endif
                                    @endif
                                </td>
                                <!-- <td>
                                    <div class="form-group">
                                        <input type="number" style="padding:5px 5px" name="checkqun" id="checkqun" min="1" max="{{$values->check_quantity}}" value="{{$values->check_quantity}}">
                                        <input type="hidden" name="mo_id" value="{{$values->moid}}">
                                        <input type="hidden" name="product_id" value="{{$values->product_id}}">
                                        <input type="hidden" name="so_id" value="{{$values->s_id}}">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm" name="btnstatus" value="approve">Approve</button>
                                </td> -->
                                <td id="status{{$values->id}}"><span
                                        class="badges {{$bageStatus[$values->mo_status]}}">{{$values->mo_status}}</span></td>
                                <td>
                                    @if($values->mo_status == "Pending")
                                    <a class="me-3" href="{{route('demandnote.recreate',[$values->id])}}" title="Demand Note">
                                        <img src="{{URL::asset('admin_asset/img/icons/dashred.svg')}}" alt="img" style=" height: 24px; width: 24px; ">
                                    </a>
                                    @endif
                                </td>
                            </form>
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