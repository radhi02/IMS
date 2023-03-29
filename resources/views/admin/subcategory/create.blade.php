@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Sub Category {{isset($SubCategory)?'Edit':"Add"}}</h4>
            <h6>{{isset($SubCategory)?'Update':"Create new"}} subcategory</h6>
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
    <form id="subcategoryForm" method="post"
        action="@if(!empty($SubCategory->id)!=0){{route('subcategory.update',$SubCategory->id)}}@else{{route('subcategory.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($SubCategory->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name">SubCategory Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Sub Category Name"
                                value="{{ old('name', isset($SubCategory) ? $SubCategory->name : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name">Category Name<span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="select">
                                <option value="">Select Category</option>
                                @foreach ($Category as $data)
                                <option value="{{$data->id}}"
                                    {{ old('category_id', (isset($SubCategory)&& $SubCategory->category_id == $data->id) ? 'selected' : ''   )}}>
                                    {{$data->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select name="status" class="select">
                                <option value="Active"
                                    {{(isset($SubCategory->status) && $SubCategory->status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($SubCategory->status) && $SubCategory->status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name">Description</label>
                            <textarea type="text" name="description" id="description"
                                placeholder="Description">{{ old('name', isset($SubCategory) ? $SubCategory->description : '' )  }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('subcategory.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$('#subcategoryForm').validate({
    rules: {
        name: { required: true },
        category_id: { required: true },
    },
    messages: {
        name: { required: "Please enter Sub Category name" },
        category_id: { required: "Please select Category " },
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