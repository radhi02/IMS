@extends('layouts.app')
@section('content')
@include('admin.layout.commmonjs')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Company  Details</h4>
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
    <form id="Company_Form" method="post" action="{{route('company.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="company_name">Company Name <span class="text-danger">*</span></label> <input type="text"
                                name="company_name" id="company_name" placeholder="Company  Name"
                                value="{{ old('company_name', isset($edit_company) ? $edit_company->company_name : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="email">Company Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" placeholder="Email"
                                value="{{ old('email', isset($edit_company) ? $edit_company->email : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="contact_no">Phone No. <span class="text-danger">*</span></label>
                            <input type="text" name="contact_no" id="contact_no"
                                data-inputmask="'mask': ['99999 99999', '99999 99999']" data-mask="" inputmode="text"
                                placeholder="Phone No"
                                value="{{ old('contact_no', isset($edit_company) ? $edit_company->contact_no : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-6">
                        <div class="form-group">
                            <label>Company Office Address <span class="text-danger">*</span></label>
                            <textarea name="reg_off_add"> {{old('reg_off_add') || isset($edit_company->reg_off_add) ? br2nl($edit_company->reg_off_add):''}}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-6">
                        <div class="form-group">
                            <label>Factory Address <span class="text-danger">*</span></label>
                            <textarea name="factory_add"> {{old('factory_add') || isset($edit_company->factory_add) ? br2nl($edit_company->factory_add):''}}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Country <span class="text-danger">*</span></label>
                            <select name="edit_company_country" id="edit_company_country" class="select" style='width: 200px;'>
                                <option value="">Select Country</option>
                                @foreach ($Countries as $con)
                                <option value="{{$con->id}}"
                                    {{  old('country', (isset($edit_company->country) && $edit_company->country == $con->id ) ? 'selected' : '') }}>
                                    {{$con->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>State <span class="text-danger">*</span></label>
                            <select id="edit_company_state" name="edit_company_state" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>City <span class="text-danger">*</span></label>
                            <select name="edit_company_city" id="edit_company_city" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="pincode">Pincode <span class="text-danger">*</span></label>
                            <input type="number" name="pincode" id="pincode" placeholder="Pincode"
                                value="{{ old('pincode', isset($edit_company) ? $edit_company->pincode : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="gst_no">GST Number :</label>
                            <input type="text" name="gst_no" id="gst_no" placeholder="GST Number"
                                value="{{ old('gst_no', isset($edit_company) ? $edit_company->gst_no : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="pan_no">PAN Number : </label>
                            <input type="text" name="pan_no" id="pan_no" placeholder="PAN Number"
                                value="{{ old('pan_no', isset($edit_company) ? $edit_company->pan_no : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="website" id="website" placeholder="Website" value="{{ old('website', isset($edit_company) ? $edit_company->website : '' )  }}" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label for="otherdetails">Other Details</label>
                            <textarea type="text" name="otherdetails" id="otherdetails" placeholder="Other Details">{{ old('otherdetails', isset($edit_company) ? $edit_company->otherdetails : '' )  }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="otherdetails">Company Logo</label> 
                            <div class="image-upload">
                                <input type="file" id="check_img" name="Logoimage">
                                <div class="image-uploads">
                                    <img src="{{URL::asset('admin_asset/img/icons/upload.svg')}}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="comp_id" value=@if(!empty($edit_company->id)) {{$edit_company->id}} @endif>
                    <input type="hidden" name="image_2" value=@if(!empty($edit_company->logo)) {{$edit_company->logo}} @endif>
                    @php
                    $img="companylogo.png";
                    if(isset($edit_company->logo))
                    {
                        $filename = public_path('Admin/Company/'. $edit_company->logo);
                        if($edit_company->logo != '' && file_exists($filename)) $img=$edit_company->logo;
                    }
                    @endphp
                    <div class="col-12">
                        <div class="product-list">
                            <ul class="row">
                                <li class="ps-0">
                                    <div class="productviewset">
                                        <div class="productviewsimg">
                                            <img src="{{ URL::asset('Admin/Company/'. $img) }}" id="showMe" alt="img">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>       
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('user.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>             
                </div>
            </div>
        </div>
    </form>
</div>
<script>

// Demo GST No  : 06BZAHM6385P6Z2, AZBZAHM6385P6Z2, 06BZ63AHM85P6Z2, 06BZAHM6385P6F2
$('#Company_Form').validate({
    rules: {
        company_name: { required: true },
        email: { required: true },
        contact_no: { required: true },
        reg_off_add: { required: true },
        factory_add: { required: true },
        country: { required: true },
        state: { required: true },
        city: { required: true },
        pincode: { required: true },
        gst_no: { required: false, pattern: '[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}' },
        pan_no: { required: false, pattern: '[A-Za-z]{5}[0-9]{4}[A-Z]{1}' },
        Logoimage: { required: false, accept: "image/jpeg, image/jpg, image/png" },
    },
    messages: {
        company_name: { required: "Please enter Company  Name" },
        email: { required: "Please enter Company Email" },
        contact_no: { required: "Please enter Contact Number" },
        reg_off_add: { required: "Please enter Registered Office Address" },
        factory_add: { required: "Please enter Factory Address" },
        country: { required: "Please select Country" },
        state: { required: "Please select State" },
        city: { required: "Please select City" },
        pincode: { required: "Please enter pincode" },
        gst_no: { pattern: "Please enter valid GST Number" },
        pan_no: { pattern: "Please enter valid PAN Number"},
        Logoimage: { accept: "Only jpeg,jpg and png files are allowed." },
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

$('#check_img').change(function() {
    const file = this.files[0];
    var fileType = file["type"];
    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, validImageTypes) < 0) {
        // invalid file type code goes here.
    } else {
        let reader = new FileReader();
        reader.onload = function(event) {
            $('#showMe').attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
    }
});
</script>

@endsection