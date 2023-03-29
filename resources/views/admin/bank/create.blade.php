@extends('layouts.app')
@section('content')
@include('admin.layout.commmonjs')

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Bank {{isset($edit_bank)?'Edit':"Add"}}</h4>
            <h6>{{isset($edit_bank)?'Update':"Create new"}} bank</h6>
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
    <form id="BankForm" method="post"
        action=" @if(!empty($edit_bank->id)!=0)  {{route('bank.update',$edit_bank->id)}}   @else {{route('bank.store')}}@endif"
        enctype="multipart/form-data">
        @if(!empty($edit_bank->id)) @method('PATCH') @endif @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="BName">Bank Name <span class="text-danger">*</span></label> <input type="text"
                                name="BName" id="BName" placeholder="Bank Name"
                                value="{{ old('BName', isset($edit_bank) ? $edit_bank->BName : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="BIFSC">IFSC Code <span class="text-danger">*</span></label>
                            <input type="text" name="BIFSC" id="BIFSC" placeholder="Email"
                                value="{{ old('BIFSC', isset($edit_bank) ? $edit_bank->BIFSC : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="BSWIFTCODE">Swift Number </label>
                            <input type="text" name="BSWIFTCODE" id="BSWIFTCODE" placeholder="Swift Number"
                                value="{{ old('BSWIFTCODE', isset($edit_bank) ? $edit_bank->BSWIFTCODE : '' )  }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>MICR</label>
                            <input type="number" name="BMICR" id="BMICR"
                                value="{{ old('BMICR', isset($edit_bank) ? $edit_bank->BMICR : '' )  }}" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Branch <span class="required">*</span></label>
                            <input type="text" name="Branch" id="Branch"
                                value="{{ old('Branch', isset($edit_bank) ? $edit_bank->Branch : '' )  }}" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Account Number<span class="required">*</span></label>
                            <input type="number" name="Baccount" id="Baccount"
                            value="{{ old('Baccount', isset($edit_bank) ? $edit_bank->Baccount : '' )  }}" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status <span class="required">*</span></label>
                            <select id="status" name="status" class="select">
                                <option selected disabled>Select status</option>
                                <option value="Active"
                                    {{ old('edit_bank', isset($edit_bank) ? $edit_bank->status : '')=='Active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="Inactive"
                                    {{ old('status', isset($edit_bank) ? $edit_bank->status : '')=='Inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <a href="{{route('bank.index')}}" class="btn btn-cancel">Cancel</a>
                    </div>
                    </div>
                </div>
            </div>
    </form>
</div>
<script>
$('#BankForm').validate({
    rules: {
        BName: { required: true },
        BIFSC: { required: true },
        // BSWIFTCODE: { required: true },
        // BMICR: { required: true },
        Branch: { required: true },
        Baccount: { required: true },
        status: { required: true },
    },
    messages: {
        BName: { required: "Please enter Bank Name" },
        BIFSC: { required: "Please enter IFSC Code" },
        // BSWIFTCODE: { required: "Please enter Bank SWIFT Code" },
        // BMICR: { required: "Please enter MICR Code" },
        Branch: { required: "Please enter IFSC Code" },
        Baccount: { required: "Please enter Account Number" },
        status: { required: "Please select status" },
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

$("#name").on("keyup",function(){
     $(this).val($(this).val().toUpperCase());  
});
$('#ifsccode').change(function(){
    var inputvalues = $(this).val();
    
    var panformat = new RegExp("^[A-Z]{4}0[A-Z0-9]{6}$");

    if (panformat.test(inputvalues)) {
    return true;
    } else {
        alert('Please enter a valid IFSC CODe');
        $("#ifsccode").val('');
        $("#ifsccode").focus();
    }
});

</script>

@endsection