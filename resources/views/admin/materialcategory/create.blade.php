@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Material Category {{isset($MaterialCategory)?'Edit':"Add"}}</h4>
            <h6>{{isset($MaterialCategory)?'Update':"Create new"}} material category</h6>
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
    <form id="materialcategoryForm" method="post" action="@if(!empty($MaterialCategory->id)!=0){{route('materialcategory.update',$MaterialCategory->id)}}@else{{route('materialcategory.store')}}@endif" enctype="multipart/form-data">
        @if(!empty($MaterialCategory->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name">Material Category Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="MaterialCategory Name"
                                value="{{ old('name', isset($MaterialCategory) ? $MaterialCategory->name : '' )  }}">
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
                                placeholder="Description">{{ old('name', isset($MaterialCategory) ? $MaterialCategory->description : '' )  }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('materialcategory.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$('#materialcategoryForm').validate({
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
            required: "Please enter Material Category name "
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