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

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Raw Material Stock</h4>
            <h6>Manage your raw materials stock</h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="{{URL::asset('admin_asset/img/icons/search-white.svg')}}" alt="img"></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Code</th>
                            <th>HSN Code</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($RawMaterial as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$values->code}} </td>
                            <td>{{$values->HSN_CODE}} </td>
                            <td>{{$values->name}} </td>
                            <td>{{$values->quantity}} </td>
                            <!-- <td>
                                <a class="me-3 btnstockadd" data-id="{{$values->id}}" data-code="{{$values->code}}" href="javascript:void(0);"> <img src="{{URL::asset('admin_asset/img/icons/edit.svg')}}" alt="img">
                                </a>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal custom-modal fade" id="add_new_stock">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Stock For : <span id="rcode"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form id="MaterialForm" method="post" action="{{route('stockmaterial.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input class="form-control form-white" min="1" placeholder="Quantity" type="number" name="quantity" />
                    </div>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-submit">Submit</button>                    
                    </div>
                    <input type="hidden" name="mId" id="mId">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.btnstockadd').click(function () {
        $('#MaterialForm').trigger("reset");
        $("#rcode").html($(this).data('code'));
        $("#mId").val($(this).data('id'));
        $('#add_new_stock').modal('show');
    });

    $('body').on('click', '#edit-post', function () {
        var post_id = $(this).data('id');
        $.get('ajax-posts/'+post_id+'/edit', function (data) {
            $('#postCrudModal').html("Edit post");
            $('#btn-save').val("edit-post");
            $('#ajax-crud-modal').modal('show');
            $('#post_id').val(data.id);
            $('#title').val(data.title);
            $('#body').val(data.body);  
        })
    });
});
</script>
@endsection