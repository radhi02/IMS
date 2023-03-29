@extends('layouts.app')
@section('content')


<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">

            <section class="col-lg-12 connectedSortable">

                @if ($errors->any())
                <div class="alert alert-danger" id="errors_all_page">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <!-- Add Tender Form -->

                <form id="LeadForm" method="post"
                    action=" @if(!empty($Lead->id)!=0)  {{route('lead.update',$Lead->id)}}   @else {{route('lead.store')}}@endif"
                    enctype="multipart/form-data">

                    @if(!empty($Lead->id)) @method('PATCH') @endif @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{isset($Lead)?'Edit':"Add"}} Lead</h3>
                                <a href="{{route('lead.index')}}" class=" btn  my_btn  ml-auto"> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <label for="name">Select Customer <span class="text-danger">*</span></label>
                                        <select  name="customer_id" id="customer_id">
                                            <option value="">Select Customer</option>
                                            @foreach ($Customer as $data)
                                            <option value="{{$data->id}}"
                                                {{ old('customer_id', (isset($Lead)&& $Lead->customer_id == $data->id) ? 'selected' : ''   )}}>
                                                {{$data->customer_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label>Select Pump Type <span class="text-danger">*</span></label>
                                        <select class="select2bs4" multiple="multiple" data-placeholder="Select Pump Type"
                                            style="width: 100%;" name="category[]" id="categoryList">
                                                @foreach ($Category as $catdata)
                                                    <option value="{{$catdata->id}}" {{ old('cat_ids', (isset($Lead) && in_array($catdata->id, explode(',',$Lead->cat_ids))?'selected':'' )) }}> {{$catdata->name}} </option> 
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label>Select Pump Model <span class="text-danger">*</span></label>
                                        <select class="select2bs4" multiple="multiple" data-placeholder="Select Pump Model" style="width: 100%;" name="subcategory[]" id="subcategoryList">
                                            @foreach ($SubCategory as $subcatdata)
                                                <option value="{{$subcatdata->id}}" {{ old('subcat_ids', (isset($Lead) && in_array($subcatdata->id, explode(',',$Lead->subcat_ids))?'selected':'' )) }}> {{$subcatdata->name}} </option> 
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="comment">Comments <span class="text-danger">*</span></label>
                                        <textarea  name="comment"
                                            id="comment">{{ old('comment', isset($Lead) ? $Lead->comment : '' )  }}</textarea>
                                    </div>
                                    <div class="form-group col-sm-6 col-lg-4 col-xl-3">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select  name="status">
                                            <option value="Active"
                                                {{(isset($Lead->status) && $Lead->status=='Active')?'selected':'' }}>
                                                Active</option>
                                            <option value="In-Active"
                                                {{( isset($Lead->status) && $Lead->status=='In-Active')?'selected':'' }}>
                                                In-Active</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class=" m-w-105 btn btn-sm btn-success">@if(!empty($Lead->id))
                                    Update @else Save @endif</button>
                                <a href="{{route('lead.index')}}" type="submit"
                                    class=" m-w-105 btn btn-sm btn-danger">Cancel</a>
                            </div>
                        </div>
                    <!-- /.card -->
                </form>
            </section>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
$('#LeadForm').validate({
    rules: {
        customer_id: { required: true }, 
        comment: { required: true },
        'category[]': { required: true },
        'subcategory[]': { required: true },
        status: { required: true },
    },
    messages: {
        customer_id: { required: "Please select customer" },
        comment: { required: "Please enter customer comment" },
        'category[]': { required: "Please select pump type" },
        'subcategory[]': { required: "Please select pump model" },
        status: { required: "Please select lead status" },
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

$(function() {
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4',
    });
});

$('#categoryList').on('change', function() {
    var idCats = $("#categoryList").select2('val');
    $("#subcategoryList").html('');
    $.ajax({
        url: "{{ route('fetchSubCategory') }}",
        type: "POST",
        delay: 250,
        data: {
            cat_ids: idCats,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function(res) {
            $.each(res.subcategories, function (key, value) {
                $("#subcategoryList").append('<option value="' + value .id + '">' + value.name + '</option>'); 
            });
        }
    });
});

</script>

@endsection