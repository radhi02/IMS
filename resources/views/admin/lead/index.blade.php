@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">


        <div class="alert {{(Session::has('msg') !='')? 'alert-success':''}}" id="update">
            {!! Session::has('msg') ? Session::get("msg") : '' !!}
        </div>
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Lead List </h3>
                <a href="{{route('lead.create')}}" class="btn my_btn ml-auto btn btn-success">
                Create  Lead 
                </a>
                &nbsp;
                &nbsp;
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Status
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item status_check " onclick="status('Active')" href="#">Active</a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item status_check" onclick="status('In-Active')" href="#">In-Active</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered m-0" id="example2">
                    <thead>
                        <tr>
                            <th style="width: 1%"><input type='checkbox' id='checkAll'></th>
                            <th>Sr. No</th>
                            <th>Customer Name</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Lead as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <td><input type="checkbox" class="checkbox" data-id="{{$values->id}}" name="checks[]"></td>
                            <td>{{$loop->iteration}}</td>
                            <td>{{getcustomer($values->customer_id)}} </td>
                            <td>{{$values->comment}} </td>
                            <td id="status{{$values->id}}"><p class="badge {{($values->status == 'Active') ? 'bg-success' :'bg-danger'}}">{{$values->status}}</p></td>
                            <td>
                                <a class="btn  btn-outline-info" href="{{route('lead.show',$values->id)}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a class="btn  btn-outline-primary " href="{{route('lead.edit',$values->id)}}"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


<script>
$("#Updaste").removeClass('alert-success');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
/// Status ///

function status(flag) {

var idsArr = [];
$(".checkbox:checked").each(function() {

    idsArr.push($(this).attr('data-id'));
});

if (idsArr.length <= 0) {
    alert("Please select atleast one record to status change.");
} else {
    $.ajax({
        type: "POST",
        url: "{{ route('lead.status') }}",
        data: {
            id: idsArr,
            value: flag,
            "_token": "{{ csrf_token() }}",
        },
        dataType: 'json',
        success: function(res) {
            $("#update").addClass("alert-success")
            $("#update").fadeIn();

            if (res.msg == 1) {
                $("#update").html("<p >Lead Status " + flag + "  Successfully </p>")
                
                $.each(idsArr, function(index, value) {
                    if (flag == 'Active') {
                        var Classes = "badges bg-lightgreen";
                    } else {
                        var Classes = "badges bg-lightred";
                    }
                    
                    $("#status" + value).html('<p class="' + Classes + '" status">' + flag +
                    '</p>')
                });
            }
            $("#update").fadeOut(10000);
        }
    });
}
}
</script>
@endsection