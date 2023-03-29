@extends('layouts.app')
@section('content')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>RawMaterial {{isset($RawMaterial)?'Edit':"Add"}}</h4>
            <h6>{{isset($RawMaterial)?'Update':"Create new"}} RawMaterial</h6>
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
    <form id="RawMaterialForm" method="post"
        action=" @if(!empty($RawMaterial->id)!=0){{route('rawmaterial.update',$RawMaterial->id)}}@else{{route('rawmaterial.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($RawMaterial->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Name"
                                value="{{ old('name', isset($RawMaterial) ? $RawMaterial->name : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Category<span class="text-danger">*</span></label>
                            <select name="material_category_id" class="select">
                                @foreach ($Category as $data)
                                    <option value="{{$data->id}}"
                                        {{ old('material_category_id', (isset($RawMaterial)&& $RawMaterial->material_category_id == $data->id) ? 'selected' : ''   )}}>
                                        {{$data->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="quantity">Quantity<span class="text-danger">*</span></label>
                            <input type="text" name="quantity" id="quantity" placeholder="Quantity"
                                value="{{ old('quantity', isset($RawMaterial) ? $RawMaterial->quantity : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Status<span class="text-danger">*</span></label>
                            <select name="status" class="select">
                                <option value="Active"
                                    {{(isset($RawMaterial->status) && $RawMaterial->status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($RawMaterial->status) && $RawMaterial->status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Unit<span class="text-danger">*</span></label>
                            <select name="unit_id" class="select">
                            @foreach ($Unit as $data)
                                <option value="{{$data->id}}"
                                    {{ old('unit_id', (isset($RawMaterial)&& $RawMaterial->unit_id == $data->id) ? 'selected' : ''   )}}>
                                    {{$data->unit_name}}
                                </option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="code">CODE<span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" placeholder="CODE"
                                value="{{ old('code', isset($RawMaterial) ? $RawMaterial->code : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="HSN_CODE">HSN CODE<span class="text-danger">*</span></label>
                            <input type="text" name="HSN_CODE" id="HSN_CODE" placeholder="HSN CODE"
                                value="{{ old('HSN_CODE', isset($RawMaterial) ? $RawMaterial->HSN_CODE : '' )  }}">
                        </div>
                    </div>
                    <!-- <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="GST">GST (%)<span class="text-danger">*</span></label>
                            <input type="number" name="GST" id="GST" placeholder="GST (%)"
                                value="{{ old('GST', isset($RawMaterial) ? $RawMaterial->GST : '' )  }}">
                        </div>
                    </div> -->
                    @php
                        $Local = array("R1","R2","R3","R4","R5","R6","R7","R7","R8","R9","R10","R11","R12","R13","R14","R15","R16","R17","R18","R19","20","R21","R22","R23","R24","R25","R26","R27","R28","R29","R30");
                    @endphp

                    @php 
                        $location = [];
                        if(isset($RawMaterial['location'])) {
                            $location = json_decode($RawMaterial['location']);
                        }
                    @endphp
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Location<span class="text-danger">*</span></label>
                            <select name="location[]" id="location" data-placeholder="Location" class="form-control tagging" multiple="multiple">
                                @foreach ($Local as $l)
                                <option value="{{ $l }}" {{ old('location', (isset($RawMaterial) && (in_array($l,$location)) ?'selected':'' )) }}>
                                    {{ $l }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea type="text" name="description" id="description"
                                placeholder="Description">{{ old('description', isset($RawMaterial) ? $RawMaterial->description : '' )  }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('rawmaterial.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div><!-- /.content -->
<script>
$('#RawMaterialForm').validate({
    rules: {
        name: { required: true },
        material_category_id: { required: true },
        unit_id: { required: true },
        location: { required: true },
        quantity: { required: true,minlength: 1, maxlength: 5 },
        status: { required: true },
        code: { required: true, minlength: 1, maxlength: 20 },
        HSN_CODE: { required: true, minlength: 1, maxlength: 20 },
        // GST: { required: true, maxlength: 4 },
    },
    messages: {
        name: { required: "Please enter Raw Material Name" },
        material_category_id: { required: "Please select material category" },
        unit_id: { required: "Please select unit" },
        location: { required: "Please purchase unit" },
        quantity: { required: "Please enter material quantity" },
        status: { required: "Please select status" },
        code: { required: "Please enter raw material code" },
        HSN_CODE: { required: "PLease enter HSN Code" },
        // GST: { required: "Please enter GST" },
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