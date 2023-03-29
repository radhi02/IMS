@extends('layouts.app')
@section('content')
@include('admin.layout.commmonjs')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Vendor {{isset($edit_vendor)?'Edit':"Add"}}</h4>
            <h6>{{isset($edit_vendor)?'Update':"Create new"}} vendor</h6>
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
    <form id="VendorForm" method="post"
        action=" @if(!empty($edit_vendor->id)!=0)  {{route('vendor.update',$edit_vendor->id)}}   @else {{route('vendor.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($edit_vendor->id)) @method('PATCH') @endif
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if(isset($edit_vendor))
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_code">Vendor Code</label>
                            <input type="text" disabled name="vendor_code" id="vendor_code" placeholder="Vendor Code"
                                value="{{ old('vendor_code', isset($edit_vendor) ? $edit_vendor->vendor_code : '' )  }}">
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_name">Vendor Name<span class="text-danger">*</span></label>
                            <input type="text" name="vendor_name" id="vendor_name" placeholder="Name"
                                value="{{ old('vendor_name', isset($edit_vendor) ? $edit_vendor->vendor_name : '' )  }}"
                                >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_name">Vendor Email<span class="text-danger">*</span></label>
                            <input type="email" name="vendor_email" id="vendor_email" placeholder="Email"
                                value="{{ old('vendor_email', isset($edit_vendor) ? $edit_vendor->vendor_email : '' )  }}"
                                >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_contactperson">Contact Person Name<span
                                    class=" text-danger">*</span></label>
                            <input type="text" name="vendor_contactperson" id="vendor_contactperson"
                                placeholder="Contact Person Name"
                                value="{{ old('vendor_contactperson', isset($edit_vendor) ? $edit_vendor->vendor_contactperson : '' )  }}"
                                >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_phone">Phone No.<span class="text-danger">*</span></label>
                            <input type="number" name="vendor_phone" id="vendor_phone" placeholder="Phone No"
                                value="{{ old('vendor_phone', isset($edit_vendor) ? $edit_vendor->vendor_phone : '' )  }}"
                                >
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="Location">Location<span class="text-danger">*</span></label>
                            <input type="text" name="Location" id="Location"
                                value="{{ old('Location', isset($edit_vendor) ? $edit_vendor->Location : '' )  }}"
                                placeholder="Location">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_street">Street<span class="text-danger">*</span></label>
                            <input type="text" name="vendor_street" id="vendor_street"
                                value="{{ old('vendor_street', isset($edit_vendor) ? $edit_vendor->vendor_street : '' )  }}"
                                placeholder="Street">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Country<span class="text-danger">*</span></label>
                            <select name="vendor_country" id="vendor_country" class="select" style='width: 200px;'>
                                <option value="">Select Country</option>
                                @foreach ($Countries as $con)
                                <option value="{{$con->id}}"
                                    {{  old('Country', (isset($edit_vendor->country) && $edit_vendor->country == $con->id ) ? 'selected' : '') }}>
                                    {{$con->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>State<span class="text-danger">*</span></label>
                            <select id="vendor_state" name="vendor_state" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>City<span class="text-danger">*</span></label>
                            <select name="vendor_city" id="vendor_city" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_zipcode">Zipcode<span class="text-danger">*</span></label>
                            <input type="number" name="vendor_zipcode" id="vendor_zipcode"
                                value="{{ old('vendor_street', isset($edit_vendor) ? $edit_vendor->vendor_zipcode : '' )  }}"
                                placeholder="Zipcode">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_zipcode">GST Number :</label>
                            <input type="text" name="vendor_GST" id="vendor_GST" placeholder="GST Number"
                                value="{{ old('vendor_GST', isset($edit_vendor) ? $edit_vendor->vendor_GST : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="vendor_PAN">PAN Number :</label>
                            <input type="text" name="vendor_PAN" id="vendor_PAN" placeholder="PAN Number"
                                value="{{ old('vendor_PAN', isset($edit_vendor) ? $edit_vendor->vendor_PAN : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Vendor Type<span class="text-danger">*</span></label>
                            <select name="vendor_type" class="select" style='width: 200px;'>
                                <option
                                    {{ old('vendor_type', (isset($edit_vendor)&& $edit_vendor->vendor_type == 'Material') ? 'selected' : ''   )}}
                                    value="Material">Material</option>
                                <option
                                    {{ old('vendor_type', (isset($edit_vendor)&& $edit_vendor->vendor_type == 'Service') ? 'selected' : ''   )}}
                                    value="Service">Service</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>MSME</label>
                            <select name="vendor_MSME" id="vendor_MSME" class="select" style='width: 200px;'>
                                <option
                                    {{ old('vendor_MSME', (isset($edit_vendor)&& $edit_vendor->vendor_MSME == 1) ? 'selected' : ''   )}}
                                    \ value="1">Yes</option>
                                <option
                                    {{ old('vendor_MSME', (isset($edit_vendor)&& $edit_vendor->vendor_MSME == 0) ? 'selected' : ''   )}}
                                    value="0">No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group col-sm-6 col-lg-4 col-xl-3 div_msme">
                        <label for="vendor_MSME_number">MSME Number :</label>
                        <input type="text" name="vendor_MSME_number" id="vendor_MSME_number" placeholder="MSME Number"
                            value="{{ old('vendor_MSME', isset($edit_vendor) ? $edit_vendor->vendor_MSME : '' )  }}">
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="vendor_status" class="select" style='width: 200px;'>
                                <option value="Active"
                                    {{(isset($edit_vendor->vendor_status) && $edit_vendor->vendor_status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($edit_vendor->vendor_status) && $edit_vendor->vendor_status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('vendor.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function() {
        $('#vendor_MSME').change(function(){
            $('.div_msme').hide();
            $('#' + $(this).val()).show();
            if($(this).val() == '1') {
                $('.div_msme').show();
            }
        });
    });
    $('#VendorForm').validate({
        rules: {
            vendor_name: { required: true,minlength:3,maxlength:20  }, 
            vendor_contactperson: { required: true,minlength:3,maxlength:20  }, 
            vendor_email: { required: true, minlength: 3, maxlength: 40 }, 
            vendor_phone: { required: true, minlength: 10, maxlength: 10 }, 
            Location: { required: true, minlength:3,maxlength:20 }, 
            vendor_street	: { required: true, minlength:3,maxlength:20 }, 
            vendor_city: { required: true }, 
            vendor_state: { required: true }, 
            vendor_country: { required: true }, 
            vendor_zipcode: { required: true, minlength:6,maxlength:6 }, 
            vendor_type: { required: true }, 
            vendor_PAN: { pattern: '[A-Za-z]{5}[0-9]{4}[A-Z]{1}' },
            vendor_GST: { pattern: '[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}' },
        },
        messages: {
            vendor_name: { required: "Please enter vendor name " },
            vendor_contactperson: { required: "Please enter vendor contact person " },
            vendor_email: { required: "Please enter email" },
            vendor_phone: { required: "Please enter number " },
            Location: { required: "Please enter Location " },
            vendor_street: { required: "Please enter street " },
            vendor_city: { required: "Please Select city  " },
            vendor_state: { required: "Please Select state  " },
            vendor_country: { required: "Please Select country  " },
            vendor_zipcode: { required: "Please enter zip number " },
            vendor_type: { required: "Please enter vendor type " },
            vendor_PAN: { pattern: "Please enter valid PAN Number" },
            vendor_GST: { pattern: "Please enter valid GST Number" },
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