@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Category {{isset($Category)?'Edit':"Add"}}</h4>
            <h6>{{isset($Category)?'Update':"Create new"}} category</h6>
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
    <form id="categoryForm" method="post" action="@if(!empty($Category->id)!=0){{route('category.update',$Category->id)}}@else{{route('category.store')}}@endif" enctype="multipart/form-data">
        @if(!empty($Category->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name">Category Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Category Name"
                                value="{{ old('name', isset($Category) ? $Category->name : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select name="status" class="select">
                                <option value="Active"
                                    {{(isset($Unit->status) && $Unit->status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($Unit->status) && $Unit->status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-6">
                        <div class="form-group">
                            <label for="name">Description</label>
                            <textarea type="text" name="description" id="description"
                                placeholder="Description">{{ old('name', isset($Category) ? $Category->description : '' )  }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('category.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$('#CategoryForm').validate({
    rules: {
        name: {
            required: true
        },
        status: {
            required: true
        },
    },
    messages: {
        name: {
            required: "Please enter Category name "
        },
        status: {
            required: "Please select status "
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