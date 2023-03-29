@extends('layouts.app')
@section('content')
<script>
@if(Session::has('msg') !='')
Swal.fire(
    'Success!',
    '{{ Session::has("msg") ? Session::get("msg") : '' }}',
    'success'
)
@endif
</script>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Customer List</h4>
            <h6>Manage your customers</h6>
        </div>
        <div class="page-btn">
            <a href="{{route('customer.create')}}" class="btn btn-added"><img src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Add New Customer</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="{{URL::asset('admin_asset/img/icons/search-white.svg')}}" alt="img"></a>
                    </div>
                    <div class="col-lg col-sm-6 col-12">
                        <div style="margin-left: 10px; width: 200px">
                            <select class="select statuschange" id="statuschange">
                                <option value="">Select Status</option>
                                <option value="Active">Active</option>
                                <option value="In-Active">In-Active</option>
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
                        <th>Name</th>
                        <th>Code</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($customer as $k=>$values)
                    <tr id="id_rmv{{$values->id}}">
                        <td>
                            <label class="checkboxs">
                                <input type="checkbox" class="checkbox select-all-sub" data-id="{{$values->id}}" name="checks[]">
                                <span class="checkmarks"></span>
                            </label>
                        </td>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$values->customer_name}} </td>
                        <td>{{$values->customer_code}} </td>
                        <td>{{$values->customer_email}} </td>
                        <td>{{$values->customer_phone}} </td>
                        <td id="status{{$values->id}}"><span class="badges {{($values->customer_status == 'Active') ? 'bg-lightgreen' :'bg-lightred'}}">{{$values->customer_status}}</span></td>
                        <td>
                            <a class="me-3" href="{{route('customer.show',$values->id)}}">
                            <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="img">
                            </a>
                            <a class="me-3" href="{{route('customer.edit',$values->id)}}">
                            <img src="{{URL::asset('admin_asset/img/icons/edit.svg')}}" alt="img">
                            </a>
                            <a class="Delete" data-id="{{$values->id}}" href="javascript:void(0);">
                                <img src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="img">
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
    removethis($(this).data('id'),"{{url('customer_destroy')}}","{{ csrf_token() }}");
});

$('.statuschange').change(function() {
    changeAllStatus($(this).val(),"{{route('customer.status')}}","{{ csrf_token() }}");
});

</script>
@endsection