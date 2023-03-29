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
<?php $bageStatus = ['Pending'=>'bg-lightyellow','Complete'=>'bg-lightgreen','Processing'=>'bg-inprocess','Cancelled'=>'bg-lightgrey','Instore'=>'bg-lightyellow'] ?>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Manufacturing List</h4>
            <h6>Consume material for manufacturing order</h6>
        </div>
        <!-- <div class="page-btn">
            <a href="{{route('consumption.create')}}" class="btn btn-added"><img src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Create New Manufacturing Order</a>
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
                </div>
            </div>
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>COnsumption Code</th>
                            <th>Mfg.Code</th>
                            <th>Sales Order</th>
                            <!-- <th>Order Date</th> -->
                            <th>Product</th>
                            <th>Delivery Date</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ManufactureOrder as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$values->code}}</td>
                            <td>{{$values->mocode}}</td>
                            <td>{{$values->s_code}}</td>
                            <!-- <td>{{ShowNewDateFormat($values->s_order_date)}}</td> -->
                            <td>{{$values->p_name}} </td>
                            <td>{{ShowNewDateFormat($values->delivery_date)}} </td>
                            <td>{{$values->quantity}} </td>
                            <td id="status{{$values->id}}"><span
                                    class="badges {{$bageStatus[$values->status]}}">{{$values->status}}</span></td>
                            <td>
                                @if($values->status == "Pending")
                                <a class="me-3" href="{{route('consumption.create',['id'=>$values->id])}}" title="Demand Note">
                                    <img src="{{URL::asset('admin_asset/img/icons/consumption.svg')}}" alt="img" style=" height: 24px; width: 24px; ">
                                </a>
                                @endif
                                <a class="me-3" href="{{route('consumption.show',$values->id)}}" title="Consume">
                                    <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="img">
                                </a>
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