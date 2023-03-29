@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Department {{isset($Department)?'Edit':"Add"}}</h4>
            <h6>{{isset($Department)?'Update':"Create new"}} department</h6>
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
    <form id="DepartmentForm" method="post"
        action=" @if(!empty($Department->id)!=0){{route('department.update',$Department->id)}}@else{{route('department.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($Department->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="department_name">Department Name<span class="text-danger">*</span></label>
                            <input type="text" name="department_name" id="department_name" placeholder="Name"
                                value="{{ old('department_name', isset($Department) ? $Department->department_name : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="department_status" class="select">
                                <option value="Active"
                                    {{(isset($Department->status) && $Department->status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($Department->status) && $Department->status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    @if(isset($Department) && !empty($Department->department_code))
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="department_code">Department Code</label>
                            <input type="text" disabled name="department_code" id="department_code"
                                placeholder="customer Code"
                                value="{{ old('department_code', isset($Department) ? $Department->department_code : '' )  }}">
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('department.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$('#CustomerForm').validate({
    rules: {
        department_name: {
            required: true
        },
        department_status: {
            required: true
        },
    },
    messages: {
        department_name: {
            required: "Please enter Department name "
        },
        department_status: {
            required: "Please select Department status "
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
</script>
@endsection