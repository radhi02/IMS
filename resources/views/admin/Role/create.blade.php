@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Role {{isset($edit_role)?'Edit':"Add"}}</h4>
            <h6>{{isset($edit_role)?'Update':"Create new"}} role</h6>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Opps!</strong>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <form id="RoleForm" method="post" action=" {{isset($edit_role)?route('role.update',$edit_role->id) :route('role.store')}} ">
        @if(!empty($edit_role->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Role Name <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Role Name" name="name" maxlength="20" minlength="3" value="{{ old('name') || isset($edit_role->name)?$edit_role->name:''}}" />
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="Status" class="select">
                                <option value="Active"
                                    {{ old('name') || isset($edit_role->Status) && $edit_role->Status=="Active"    ? $edit_role->Status:''}}>
                                    Active</option>
                                <option value="In-Active"
                                    {{ old('name') || isset($edit_role->Status) && $edit_role->Status=="Active"   ?$edit_role->Status:''}}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-6">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="Description"> {{old('Description') || isset($edit_role->description)    ? $edit_role->description:''}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="{{route('role.index')}}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
</div>
</form>
</div>

<script>
$(document).ready(function() {

    // alert($('#RoleForm').val());
    $('#RoleForm').validate({
        rules: {
            name: {
                required: true
            },
            Status: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Please enter a Role name "
            },
            Status: {
                required: "Please select status"
            },

        },

        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endsection