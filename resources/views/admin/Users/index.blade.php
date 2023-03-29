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
            <h4>User List</h4>
            <h6>Manage your users</h6>
        </div>
        <div class="page-btn">
            <a href="{{route('user.create')}}" class="btn btn-added"><img src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Add New User</a>
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
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Code</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr id="id_rmv{{$user->id}}">
                        <td>
                            <label class="checkboxs">
                                <input type="checkbox" class="checkbox select-all-sub" data-id="{{$user->id}}" name="checks[]">
                                <span class="checkmarks"></span>
                            </label>
                        </td>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->emp_code}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->Phone}}</td>
                        <td id="status{{$user->id}}"><span class="badges {{($user->user_status == 'Active') ? 'bg-lightgreen' :'bg-lightred'}}">{{$user->user_status}}</span></td>
                        <td>
                            <a class="me-3" href="{{route('user.show',$user->id)}}">
                            <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="img">
                            </a>
                            <a class="me-3" href="{{route('user.edit',$user->id)}}">
                            <img src="{{URL::asset('admin_asset/img/icons/edit.svg')}}" alt="img">
                            </a>
                            <a class="Delete" data-id="{{$user->id}}" href="javascript:void(0);">
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
    removethis($(this).data('id'),"{{url('user_destroy')}}","{{ csrf_token() }}");
});
$('.statuschange').change(function() {
    changeAllStatus($(this).val(),"{{route('User.status')}}","{{ csrf_token() }}");
});


</script>
@endsection