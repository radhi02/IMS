@extends('layouts.app')
@section('content')
@include('admin.layout.commmonjs')

<?php $BloodGroup = ['A+','A-','B+','B-','O+','O-','AB+','AB-']; ?>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>User {{isset($edit_users)?'Edit':"Add"}}</h4>
            <h6>{{isset($edit_users)?'Update':"Create new"}} user</h6>
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
    <form id="UserForm" method="post"
        action=" @if(!empty($edit_users->id)!=0)  {{route('user.update',$edit_users->id)}}   @else {{route('user.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($edit_users->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" name="firstName"
                                value="{{ old('firstName', isset($edit_users->first_name) ?  $edit_users->first_name  : '' ) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="lastName"
                                value="{{  old('lastName', isset($edit_users->last_name) ?  $edit_users->last_name  : '' ) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                value="{{  old('email', isset($edit_users->email) ?  $edit_users->email  : '' ) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="Phone">Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="Phone" data-inputmask="'mask': ['99999 99999', '99999 99999']"
                                data-mask="" inputmode="text"
                                value="{{  old('Phone', isset($edit_users->Phone) ?  $edit_users->Phone  : '' ) }}">
                        </div>
                    </div>
                    @if(isset($edit_users))
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>User Code</label>
                            <input type="text" disabled value="{{ old('emp_code', isset($edit_users) ? $edit_users->emp_code : '' )  }}">
                        </div>
                    </div>
                    @endif
                    @if(!isset($edit_users->password))
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" name="password" id="password">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Confirm Password<span class="text-danger">*</span></label>
                            <div class="pass-group">
                                <input type="password" class="pass-inputs" name="cnfpassword">
                                <span class="fas toggle-passworda fa-eye-slash"></span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Roles <span class="text-danger">*</span></label> @php $id =
                            auth()->user()->id; @endphp
                            <select class="select" name="UserType">
                                <option value="">User Role</option>
                                @foreach($roles as $role)
                                <option value=" {{$role->id}}"
                                    {{  old('UserType', (isset($edit_users->Role) && $edit_users->Role == $role->id ) ? 'selected' : '') }}>
                                    {{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Select Department <span class="text-danger">*</span></label>
                            <select class="select" name="department_id">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                <option value=" {{$dept->id}}"
                                    {{  old('department_id', (isset($edit_users->department_id) && $edit_users->department_id == $dept->id ) ? 'selected' : '') }}>
                                    {{$dept->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="Status">Status <span class="text-danger">*</span></label>
                            <select class="select" name="Status">
                                <option value="Active"
                                    {{(isset($edit_users->user_status) && $edit_users->user_status=='Active')?'selected':'' }}>
                                    Active</option>
                                <option value="In-Active"
                                    {{( isset($edit_users->user_status) && $edit_users->user_status=='In-Active')?'selected':'' }}>
                                    In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Select Gender <span class="text-danger">*</span></label>
                            <select class="select" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male"
                                    {{  old('gender', (isset($edit_users->gender) && $edit_users->gender == "Male" ) ? 'selected' : '') }}>
                                    Male</option>
                                <option value="Female"
                                    {{  old('gender', (isset($edit_users->gender) && $edit_users->gender == "Female" ) ? 'selected' : '') }}>
                                    Female</option>
                                <option value="Transgender"
                                    {{  old('gender', (isset($edit_users->gender) && $edit_users->gender == "Transgender" ) ? 'selected' : '') }}>
                                    Transgender</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Marital Status <span class="text-danger">*</span></label>
                            <select class="select" name="marital_status">
                                <option value="">Select Marital Status</option>
                                <option value="Married"
                                    {{  old('marital_status', (isset($edit_users->marital_status) && $edit_users->marital_status == "Married" ) ? 'selected' : '') }}>
                                    Married</option>
                                <option value="Unmarried"
                                    {{  old('marital_status', (isset($edit_users->marital_status) && $edit_users->marital_status == "Unmarried" ) ? 'selected' : '') }}>
                                    Unmarried</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label>Blood Group <span class="text-danger">*</span></label>
                            <select class="select" name="blood_group">
                                <option value="">Select Blood Group</option>
                                @foreach($BloodGroup as $B)
                                <option value="{{$B}}"
                                    {{  old('blood_group', (isset($edit_users->blood_group) && $edit_users->blood_group == $B ) ? 'selected' : '') }}>
                                    {{$B}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class=" col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="doj">Date of Joining</label>
                            <input type="date"
                                value="{{  old('doj', isset($edit_users->doj)?date('Y-m-d', strtotime($edit_users->doj)): '' ) }}" name="doj"
                                id="doj" />
                        </div>
                    </div>
                    <div class="col-sm-12" style="border-bottom: 1px solid lightgrey; margin-bottom: 10px;">
                        <h5>Present Address</h5>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="form-group">
                            <label for="address">Street <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="address"
                                value="{{  old('Address', isset($edit_users->Address) ?  $edit_users->Address  : '' ) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="country"> Country <span class="text-danger">*</span></label>
                            <select name="user_country" id="user_country" class="select" style='width: 200px;'
                                value="1">
                                <option value="">Select Country</option>
                                @foreach ($Countries as $con)
                                <option value="{{$con->id}}"
                                    {{  old('Country', (isset($edit_users->Country) && $edit_users->Country == $con->id ) ? 'selected' : '') }}>
                                    {{$con->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="state"> State <span class="text-danger">*</span></label>
                            <select id="user_state" name="user_state" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="user_city"> City <span class="text-danger">*</span></label>
                            <select name="user_city" id="user_city" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="pincode">Pincode <span class="text-danger">*</span></label>
                            <input type="number" name="pincode" id="pincode"
                                value="{{  old('pincode', isset($edit_users->pincode) ?  $edit_users->pincode  : '' ) }}">
                        </div>
                    </div>

                    <div class="col-sm-12" style="border-bottom: 1px solid lightgrey; margin-bottom: 10px;">
                        <h5>Permanent Address</h5>
                    </div>
                    <div class="col-sm-12">
                        <input type="checkbox" name="filladdress" id="filladdress" onclick="fillAddress()" />Parmanent
                        address same as Present address.<br />
                    </div>

                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="form-group">
                            <label for="peraddress">Street <span class="text-danger">*</span></label>
                            <input type="text" name="peraddress" id="peraddress"
                                value="{{  old('Per_Address', isset($edit_users->Per_Address) ?  $edit_users->Per_Address  : '' ) }}">
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="Per_Country"> Country <span class="text-danger">*</span></label>
                            <select name="Per_Country" id="Per_Country" class="select" style='width: 200px;' value="1">
                                <option value="">Select Country</option>
                                @foreach ($Countries as $con)
                                <option value="{{$con->id}}"
                                    {{  old('Per_Country', (isset($edit_users->Per_Country) && $edit_users->Per_Country == $con->id ) ? 'selected' : '') }}>
                                    {{$con->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="Per_State"> State <span class="text-danger">*</span></label>
                            <select id="Per_State" name="Per_State" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="Per_city"> City <span class="text-danger">*</span></label>
                            <select name="Per_city" id="Per_city" class="select" style='width: 200px;'>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="perpincode">Pincode <span class="text-danger">*</span></label>
                            <input type="number" name="perpincode" id="perpincode"
                                value="{{  old('Per_pincode', isset($edit_users->Per_pincode) ?  $edit_users->Per_pincode  : '' ) }}">
                        </div>
                    </div>

                    <div class="col-sm-12" style="border-bottom: 1px solid lightgrey; margin-bottom: 10px;">
                        <h5>Bank Details</h5>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" name="bank_name"
                                value="{{  old('bank_name', isset($edit_users->bank_name) ?  $edit_users->bank_name  : '' ) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="bank_swiftcode">Bank Swift Code</label>
                            <input type="text" name="bank_swiftcode"
                                value="{{  old('bank_swiftcode', isset($edit_users->bank_swiftcode) ?  $edit_users->bank_swiftcode  : '' ) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="bank_branch">Bank Branch Name</label>
                            <input type="text" name="bank_branch"
                                value="{{  old('bank_branch', isset($edit_users->bank_branch) ?  $edit_users->bank_branch  : '' ) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="acc_number">Account No.</label>
                            <input type="number" name="acc_number"
                                value="{{  old('acc_number', isset($edit_users->acc_number) ?  $edit_users->acc_number: '' ) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="acc_name">Account Type</label>
                            <input type="text" name="acc_name"
                                value="{{  old('acc_name', isset($edit_users->acc_name) ?  $edit_users->acc_name: '' ) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="acc_ifsccode">IFSC Code</label>
                            <input type="text" name="acc_ifsccode"
                                value="{{  old('acc_ifsccode', isset($edit_users->acc_ifsccode) ?  $edit_users->acc_ifsccode: '' ) }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="user_PAN">PAN Number :</label>
                            <input type="text" name="user_PAN" id="user_PAN"
                                value="{{ old('user_PAN', isset($edit_users) ? $edit_users->user_PAN : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="form-group">
                            <label for="user_ADHAR">Aadhaar Number :</label>
                            <input type="text" name="user_ADHAR" id="user_ADHAR"
                                value="{{ old('user_ADHAR', isset($edit_users) ? $edit_users->user_ADHAR : '' )  }}">
                        </div>
                    </div>
                    <div class="col-sm-12" style="border-bottom: 1px solid lightgrey; margin-bottom: 10px;">
                        <h5>User Image</h5>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="image-upload">
                                <input type="file" id="check_img" name="image">
                                <div class="image-uploads">
                                    <img src="{{URL::asset('admin_asset/img/icons/upload.svg')}}" alt="img">
                                    <h4>Drag and drop a file to upload</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="image_2" value=@if(!empty($edit_users->Image)) {{$edit_users->Image}}
                    @endif>
                    @php
                    $img="no_preview.png";
                    if(isset($edit_users->Image))
                    {
                        $filename = public_path('Admin/Users/'. $edit_users->Image);
                        if($edit_users->Image != '' && file_exists($filename)) $img=$edit_users->Image;
                    }
                    @endphp
                    <div class="col-12">
                        <div class="product-list">
                            <ul class="row">
                                <li class="ps-0">
                                    <div class="productviewset">
                                        <div class="productviewsimg">
                                            <img src="{{ URL::asset('Admin/Users/'. $img) }}" id="showMe" alt="img">
                                        </div>
                                        <div class="productviewscontent">
                                            <a href="javascript:void(0);" class="removeImg"><i
                                                    class="fa fa-trash-alt"></i></a>
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
<script type="text/javascript">
$('#UserForm').validate({
    rules: {
        firstName: { required: true, minlength: 3, maxlength: 12 },
        lastName: { required: true, minlength: 3, maxlength: 12 },
        email: { required: true, minlength: 3, maxlength: 40 },
        password: { required: true, minlength: 8, maxlength: 40 },
        cnfpassword: { required: true, minlength: 8, maxlength: 40, equalTo: "#password" },
        UserType: { required: true },
        Phone: { required: true, minlength: 10, maxlength: 10 },
        address: { required: true },
        user_country: { required: true },
        user_state: { required: true },
        user_city: { required: true },
        pincode: { required: true, minlength: 6, maxlength: 6 },
        peraddress: { required: true },
        Per_Country: { required: true },
        Per_State: { required: true },
        Per_city: { required: true },
        perpincode: { required: true, minlength: 6, maxlength: 6 },
        department_id: { required: true },
        Status: { required: true },
        gender: { required: true },
        marital_status: { required: true },
        blood_group: { required: true },
        acc_ifsccode: { pattern: '[A-Z]{4}0[A-Z0-9]{6}' },
        user_PAN: { pattern: '[A-Za-z]{5}[0-9]{4}[A-Z]{1}' },
        user_ADHAR: { pattern: '[2-9]{1}[0-9]{3}\s{1}[0-9]{4}\s{1}[0-9]{4}' },
    },
    messages: {
        firstName: { required: "Please enter first name " },
        lastName: { required: "Please enter last name " },
        email: { required: "Please enter email address" },
        password: { required: "Please enter password" },
        cnfpassword: { required: "Please enter confirm password", equalTo: "Please enter confirm password same as password" },
        UserType: { required: "Please select user role" },
        Phone: { required: "Please enter mobile no." },
        address: { required: "Please enter street" },
        user_country: { required: "Please enter country" },
        user_state: { required: "Please enter state" },
        user_city: { required: "Please enter city" },
        pincode: { required: "Please enter pincode" },
        peraddress: { required: "Please enter street" },
        Per_Country: { required: "Please enter country" },
        Per_State: { required: "Please enter state" },
        Per_city: { required: "Please enter city" },
        perpincode: { required: "Please enter pincode" },
        department_id: { required: "Please select department" },
        Status: { required: "Please select status" },
        gender: { required: "Please select gender" },
        marital_status: { required: "Please select marital status" },
        blood_group: { required: "Please select blood group" },
        acc_ifsccode: { pattern: "Please enter valid IFSC Code" },
        user_PAN: { pattern: "Please enter valid PAN Number" },
        user_ADHAR: { pattern: "Please enter valid Aadhaar Number" },
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

$('#check_img').change(function() {
    const file = this.files[0];
    console.log(file);
    if (file) {
        let reader = new FileReader();
        reader.onload = function(event) {
            $('#showMe').attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
    }
});
$(".removeImg").click(function(){

    var checkImg = <?php if(isset($edit_users->Image) && !empty($edit_users->Image)) echo "true"; else echo 'false' ?>;
    console.log(checkImg);
    if(checkImg =="true") {
        $.ajax({
            url: "{{route('User.deleteImg')}}",
            type: "post",
            data: {
                userid: '@if(!empty($edit_users->id)) {{$edit_users->id}} @endif',
                imgname: '@if(!empty($edit_users->Image)) {{$edit_users->Image}} @endif',
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                Swal.fire(
                    'Success!',
                    result.success,
                    'msg'
                )
            },
        });
    }
    $('#showMe').attr('src', "{{ URL::asset('Admin/Users/no_preview.png') }}");

});
function fillAddress() {
    if (document.getElementById('filladdress').checked) {
        $("#peraddress").val($("#address").val());
        $("#perpincode").val($("#pincode").val());
        $("#Per_Country").val($("#user_country").val()).trigger('change');
        setTimeout(function() {
            $("#Per_State").val($("#user_state").val()).trigger('change');
        }, 1000);
        setTimeout(function() {
            $("#Per_city").val($("#user_city").val()).trigger('change');
        }, 2000);
    } else {
        $("#peraddress").val('');
        $("#Per_Country").val('');
        $("#Per_State").html('');
        $("#Per_city").html('');
        $("#perpincode").val('');
    }
}
</script>

@endsection