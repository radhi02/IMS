@extends('layouts.app')
@section('content')
@include('admin.layout.commmonjs')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Customer {{isset($customer)?'Edit':"Add"}}</h4>
            <h6>{{isset($customer)?'Update':"Create new"}} customer</h6>
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
    <form id="CustomerForm" method="post" action=" @if(!empty($customer->id)!=0)  {{route('customer.update',$customer->id)}}   @else {{route('customer.store')}}@endif"  enctype="multipart/form-data">
        @if(!empty($customer->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_name">Customer Name <span class="text-danger">*</span></label> <input type="text" name="customer_name" id="customer_name" placeholder="Name"
                                value="{{ old('customer_name', isset($customer) ? $customer->customer_name : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_email">Customer Email <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" id="customer_email" placeholder="Email"
                                value="{{ old('customer_email', isset($customer) ? $customer->customer_email : '' )  }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_contactperson">Contact Person Name<span class="text-danger">*</span></label>
                            <input type="text" name="customer_contactperson" id="customer_contactperson"
                                placeholder="Contact Person Name"
                                value="{{ old('customer_contactperson', isset($customer) ? $customer->customer_contactperson : '' )  }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_phone">Phone No. <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                data-inputmask="'mask': ['99999 99999', '99999 99999']" data-mask="" inputmode="text"
                                placeholder="Phone No"
                                value="{{ old('customer_phone', isset($customer) ? $customer->customer_phone : '' )  }}">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_street">Location <span class="text-danger">*</span></label>
                            <input type="text" name="Location" id="Location" placeholder="Location"
                                value="{{ old('Location', isset($customer) ? $customer->Location : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_street">Street <span class="text-danger">*</span></label>
                            <input type="text" name="customer_street" id="customer_street" placeholder="Street"
                                value="{{ old('customer_street', isset($customer) ? $customer->customer_contactperson : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Country <span class="text-danger">*</span></label>
                            <select name="customer_country" id="customer_country" class="select" style='width: 200px;'>
                                <option value="">Select Country</option>
                                @foreach ($Countries as $con)
                                <option value="{{$con->id}}"
                                    {{  old('Country', (isset($customer->country) && $customer->country == $con->id ) ? 'selected' : '') }}>
                                    {{$con->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>State <span class="text-danger">*</span></label>
                            <select id="customer_state" name="customer_state" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>City <span class="text-danger">*</span></label>
                            <select name="customer_city" id="customer_city" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_zipcode">Zipcode <span class="text-danger">*</span></label>
                            <input type="number" name="customer_zipcode" id="customer_zipcode" placeholder="Zipcode"
                                value="{{ old('customer_zipcode', isset($customer) ? $customer->customer_zipcode : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_GST">GST Number :</label>
                            <input type="text" name="customer_GST" id="customer_GST" placeholder="GST Number"
                                value="{{ old('customer_GST', isset($customer) ? $customer->customer_GST : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_PAN">PAN Number :</label>
                            <input type="text" name="customer_PAN" id="customer_PAN" placeholder="PAN Number"
                                value="{{ old('customer_PAN', isset($customer) ? $customer->customer_PAN : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_status">Status <span class="text-danger">*</span></label>
                            <select name="customer_status" class="select">
                                <option value="Active"
                                    {{(isset($customer->customer_status) && $customer->customer_status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($customer->customer_status) && $customer->customer_status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    @if(isset($customer))
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="customer_code">Customer Code</label>
                            <input type="text" disabled name="customer_code" id="customer_code" placeholder="customer Code"
                                value="{{ old('customer_code', isset($customer) ? $customer->customer_code : '' )  }}">
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('customer.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$('#CustomerForm').validate({
    rules: {
        customer_name: { required: true, minlength: 3, maxlength: 20 }, 
        customer_contactperson: { required: true, minlength: 3, maxlength: 20 },
        customer_email: { required: true, minlength: 3, maxlength: 40 },
        customer_phone: { required: true, minlength: 10, maxlength: 10 },
        Location: { required: true, minlength: 10, maxlength: 20 },
        customer_street: { required: true, minlength: 5, maxlength: 20 },
        city: { required: true },
        state: { required: true },
        country: { required: true },
        customer_zipcode: { required: true, minlength: 6, maxlength: 6 },
        customer_PAN: { pattern: '[A-Za-z]{5}[0-9]{4}[A-Z]{1}' }, 
        customer_GST: { pattern: '[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}' }, 
    },
    messages: {
        customer_name: { required: "Please enter customer name " }, 
        customer_contactperson: { required: "Please enter customer contact person " }, 
        customer_email: { required: "Please enter email" }, 
        customer_phone: { required: "Please enter number " }, 
        Location: { required: "Please enter Location " }, 
        customer_street: { required: "Please enter street " }, 
        city: { required: "Please Select city  " }, 
        state: { required: "Please Select state  " }, 
        country: { required: "Please Select country  " }, 
        customer_zipcode: { required: "Please enter zip number " }, 
        customer_PAN: { pattern: "Please enter valid PAN Number" }, 
        customer_GST: { pattern: "Please enter valid GST Number" }, 
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