@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Module {{isset($edit_role)?'Edit':"Add"}}</h4>
            <h6>{{isset($edit_role)?'Update':"Create new"}} module</h6>
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
    <form method="post" action="{{route('Module.store')}}" id="permission">
    @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3 master">
                        <div class="form-group">
                            <label> Master </label>
                            <input type="text" class="master " name="master" maxlength="30" minlength="3" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>
                                Route Type
                            </label>
                            <select name="RouteType" id="RouteTypeId" class="select">
                                <option value="" Selcted>Select Route Type</option>
                                <option value="resource">Resource</option>
                                <option value="custome">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3 custome">
                        <div class="form-group">
                            <label>Module Name</label>
                            <input type="text" name="ModuleName" maxlength="30" minlength="3" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="Status" id="StatusID" class="select">
                                <option value="Active">Active</option>
                                <option value="In-Active">In-Active</option>
                            </select>
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