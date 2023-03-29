@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Product {{isset($Product)?'Edit':"Add"}}</h4>
            <h6>{{isset($Product)?'Update':"Create new"}} product</h6>
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
    <form id="ProductForm" method="post"
        action=" @if(!empty($Product->id)!=0){{route('product.update',$Product->id)}}@else{{route('product.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($Product->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Name"
                                value="{{ old('name', isset($Product) ? $Product->name : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Category<span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="select"
                                data-placeholder="Select Category">
                                <option value="">Select Category</option>
                                @foreach ($Category as $data)
                                <option value="{{$data->id}}"
                                    {{ old('category_id', (isset($Product)&& $Product->category_id == $data->id) ? 'selected' : ''   )}}>
                                    {{$data->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Sub Category<span class="text-danger">*</span></label>
                            <select name="sub_category_id" id="sub_category_id" class="select"
                                data-placeholder="Select Sub Category">
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="sku">Code<span class="text-danger">*</span></label>
                            <input type="text" name="sku" id="sku" placeholder="Code"
                                value="{{ old('sku', isset($Product) ? $Product->sku : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select name="status" class="select">
                                <option value="Active"
                                    {{(isset($Product->status) && $Product->status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($Product->status) && $Product->status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-9 col-lg-9 col-xl-9">
                        <div class="form-group">
                            <label>Select Material Category<span class="text-danger">*</span></label>
                            <select id="material_category_id" class="tagging" multiple="multiple">
                                <option value="">Select Material Category</option>
                                @foreach ($MaterialCategory as $data)
                                <option value="{{$data->id}}">
                                    {{$data->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label>Select Raw Material<span class="text-danger">*</span></label>
                            <select id="rawmaterial_id" class="tagging" data-placeholder="Select Raw Material">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label>Product BOM<span class="text-danger">*</span></label>
                    <div class="table-responsive mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr. No</th></th>
                                    <th>Name</th>
                                    <th>QTY</th>
                                    <th>Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyRawMaterialList">
                                @if(isset($Product) && !empty($Product->product_BOM)) 
                                    @php $tmpBOM = json_decode($Product->product_BOM,true); @endphp
                                    @if(count($tmpBOM) > 0)
                                        @foreach($tmpBOM as $b1)
                                        <tr>
                                            <td><label>{{$loop->iteration}}</label></td>
                                            <td>{{RawMaterialName($b1['id'])->name}}</td>
                                            <td>
                                                <div class="form-group" style="margin-bottom: 0px;"><input type="number" name="rawqun[]" value="{{$b1['quantity']}}"></div>
                                            </td>
                                            <td>
                                                <div class="form-group" style="margin-bottom: 0px;">
                                                    <select name="rawunit[]" class="select">
                                                        @foreach ($Unit as $u)
                                                        <option value="{{$u->id}}" {{ ($b1['unitid'] == $u->id) ? 'selected' : ''}}>
                                                            {{$u->unit_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0);" class="delete-set"><img class="deleteraw"
                                                        src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="svg"></a>
                                            </td> <input type="hidden" name="rawids[]" value="{{$b1['id']}}">
                                        </tr>
                                        @endforeach
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea type="text" name="description" id="description" placeholder="Description">{{ old('description', isset($Product) ? $Product->description : '' )  }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('product.index')}}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div><!-- /.content -->

<script>
$(document).ready(function(){
    var id = "{{isset($Product)?$Product->id:'null'}}";
    if(id!=null) getSubCategory(id);

    $('#ProductForm').validate({
        rules: {
            name: { required: true },
            category_id: { required: true },
            sub_category_id: { required: true },
            sku: { required: true },
            status: { required: true },
            'rawunit[]' : { required: true },
            'rawqun[]': { required: true, minlength: 1, maxlength: 5 },
        },
        messages: {
            name: { required: "Please enter name" },
            category_id: { required: "Please select category" },
            sub_category_id: { required: "Please select sub category" },
            sku: { required: "Please enter sku" },
            status: { required: "Please select status" },
            "rawunit[]": { required: "Please select unit" },
            "rawqun[]":  { required: "Please enter quantity" },
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
        },
    });
    $('#category_id').on('change', function() {
        var id = $("#category_id").select2('val');
        if (id == '') return false;
        $("#sub_category_id").html('');
        getSubCategory(id);
    });
    function getSubCategory(id){
        $.ajax({
            url: "{{ route('fetchSubCategory') }}",
            type: "POST",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(res) {
                $.each(res.subcategories, function(key, value) {
                    $("#sub_category_id").append('<option value="' + value.id + '">' + value
                        .name + '</option>');
                });
            }
        });
    }

    $('#material_category_id').on('change', function() {
        var id = $("#material_category_id").select2('val');
        if (id == '') return false;
        $("#rawmaterial_id").html('');
        $.ajax({
            url: "{{ route('fetchRawMaterial') }}",
            type: "POST",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(res) {
                $("#rawmaterial_id").append('<option value="">Select Raw Material</option>')
                $.each(res.rawmaterials, function(key, value) {
                    $("#rawmaterial_id").append('<option value="' + value.id + '">' + value
                        .name + '</option>');
                });
            }
        });
    });
    
    $(document).on("click",".deleteraw",function () {
        $(this).closest('tr').remove();
        var cnt = 1;
        setTimeout(function () {
            $("#tbodyRawMaterialList tr td:first-child").each(function(){ 
                $(this).find('label').text(cnt);
                cnt++;
            });
        }, 1000);
    });
    
    $('#rawmaterial_id').on('change', function() {
        var count = $('#tbodyRawMaterialList tr').length + 1;
        var id = $("#rawmaterial_id").select2('val');
        if (id == '') return false;
        $.ajax({
            url: "{{ route('addRawMaterial') }}",
            type: "POST",
            data: {
                id: id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(res) {
                // return true;
                $.each(res.rawmaterials, function(key, value) {
                    $("#tbodyRawMaterialList").append('<tr> <td><label>'+count+'</label></td> <td>'+value.name+'</td> <td><div class="form-group" style="margin-bottom: 0px;"><input type="number" name="rawqun[]"></div></td><td>'+value.unit_name+'</td><td><a href="javascript:void(0);" class="delete-set"><img class="deleteraw" src="{{URL::asset('admin_asset/img/icons/delete.svg')}}" alt="svg"></a> </td> <input type="hidden" name="rawids[]" value="'+value.id+'"><input type="hidden" name="rawunit[]" value="'+value.unit_id+'"></tr>'); 
                });
                count++;
                $('.select').select2();
            }
        });
    });

});

</script>

@endsection