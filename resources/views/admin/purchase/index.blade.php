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
<?php $bageStatus = ['Pending'=>'bg-lightyellow','Complete'=>'bg-lightgreen','Partial Recieve'=>'bg-inprocess','Cancelled'=>'bg-lightgrey','Instore'=>'bg-lightyellow'] ?>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Purchase Order List</h4>
            <h6>Manage your purchase order</h6>
        </div>
        <div class="page-btn">
            <a href="{{route('purchase.create')}}" class="btn btn-added"><img
                    src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Add New Purchase Order</a>
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
                    <div class="col-lg col-sm-6 col-12">
                        <div style="margin-left: 10px; width: 200px">
                            <select class="select statuschange" id="statuschange">
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Complete">Complete</option>
                                <option value="Processing">Processing</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
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
                            <th>Sr. No</th>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($PurchaseOrder as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox" class="checkbox select-all-sub" data-id="{{$values->id}}"
                                        name="checks[]">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>{{$loop->iteration}}</td>
                            <td>{{ShowNewDateFormat($values->date)}} </td>
                            <td>{{$values->code}} </td>
                            <td>{{$values->vendor_name}} </td>
                            <td id="status{{$values->id}}"><span
                                    class="badges {{$bageStatus[$values->status]}}">{{$values->status}}</span></td>
                            <td>
                                <div class="action_btn_box">
                                    <a class="action_icon" href="{{route('purchase.show',$values->id)}}"
                                        title="View">
                                        <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="Purchase Order">
                                    </a>
                                    <a title="pdf" href="{{route('purchase.download',$values->id)}}" target="_blank" ><img src="{{URL::asset('admin_asset/img/icons/pdf.svg')}}" alt="img"></a>
                                    @if($values->status == "Pending" || $values->status == "Partial Recieve")
                                    <a class="btn btn-block btn-warning btn-sm" href="{{route('materialinventory.create',['id'=>$values->id])}}" title="Receive Material">
                                        Receive Material
                                    </a>
                                    @endif
                                    @if($values->status != "Pending")
                                    <a class="btn btn-block btn-success btn-sm" href="{{route('materialinventory.show',$values->id)}}"
                                    title="View">
                                        Received Material
                                    </a>
                                    @endif
                                    <!-- <a class=" btn btn-block btn-warning btn-sm" href="{{route('manufacture.create',['id'=>$values->id])}}"title="Manufacture">
                                        Manufacture
                                    </a>
                                    <a class="action_icon" href="{{route('purchase.show',$values->id)}}"
                                        title="View">
                                        <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="img">
                                    </a>
                                    <a class="action_icon" href="{{route('purchase.edit',$values->id)}}"
                                        title="Edit">
                                        <img src="{{URL::asset('admin_asset/img/icons/edit.svg')}}" alt="img">
                                    </a>
                                    <a class="action_icon Delete" data-id="{{$values->id}}" href="javascript:void(0);"
                                        title="Delete">
                                        <img src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="img">
                                    </a> -->
                                </div>
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
    removethis($(this).data('id'), "{{url('purchase_destroy')}}", "{{ csrf_token() }}");
});
$('.statuschange').change(function() {
    changeAllStatus($(this).val(), "{{route('purchase.status')}}", "{{ csrf_token() }}");
});
</script>
@endsection