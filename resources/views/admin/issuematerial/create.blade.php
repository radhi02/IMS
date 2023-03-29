@extends('layouts.app')
@section('content')
<script>
@if(Session::has('msg') != '')
Swal.fire(
    'Success!',
    '{{ Session::has("msg") ? Session::get("msg") : '
    ' }}',
    'success'
)
@endif
$(function() {
    $('#select-all').prop('checked', true);
});
</script>
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Issue Material</h4>
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
    <form id="IssueMaterialForm" method="post" action="{{route('issuematerial.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="card-sales-split" style="margin-bottom: 25px;">
                    <h2>Demand Note : {{$DemandNote->code}}</h2>
                </div>
                <div class="row">
                    <div class="table-responsive mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr. No</th></th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Demanded QTY</th>
                                    <th>Issued QTY</th>
                                    <th>Remained QTY</th>
                                    <th>Available QTY</th>
                                    <th>QTY</th>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id='select-all'>
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductList">
                                @if(isset($DemandNote) && !empty($DemandNote->note))
                                @php $tmpNote = json_decode($DemandNote->note,true); $i=1; @endphp
                                @if(count($tmpNote) > 0)
                                @foreach($tmpNote as $nt1)
                                @if($nt1['RemainedQun'] > 0) 
                                @php $maxQ =  ($nt1['RemainedQun']>(RawMaterialName($nt1['RawId'])->quantity))?(RawMaterialName($nt1['RawId'])->quantity):$nt1['RemainedQun']; @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{RawMaterialName($nt1['RawId'])->name}}</td>
                                    <td>{{RawMaterialName($nt1['RawId'])->code}}</td>
                                    <td>{{$nt1['demandQun']}}</td>
                                    <td>{{$nt1['demandQun'] - $nt1['RemainedQun']}}</td>
                                    <td>{{$nt1['RemainedQun']}}</td>
                                    <td>{{RawMaterialName($nt1['RawId'])->quantity}}</td>
                                    <td><div class="form-group" style="margin-bottom: 0px;"><input type="number" style="padding:5px 5px" name="inote[{{$nt1['RawId']}}][IssueQun]" min="0" max="{{$maxQ}}" class="txtqun" value="{{$nt1['RemainedQun']}}"></div></td>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" class="checkbox select-all-sub" data-id="{{$nt1['RawId']}}" name="inote[{{$nt1['RawId']}}][chk]">
                                            <span class="checkmarks"></span>
                                        </label>
                                        <input type="hidden" name="inote[{{$nt1['RawId']}}][requiredQun]" value="{{$nt1['RemainedQun']}}">
                                        <input type="hidden" name="inote[{{$nt1['RawId']}}][RawId]" value="{{$nt1['RawId']}}">
                                        <input type="hidden" name="txtValidateRaw[{{$nt1['RawId']}}]" value="{{RawMaterialName($nt1['RawId'])->code}}">
                                    </td>
                                </tr>
                                <?php $i++ ?> 
                                @endif
                                @endforeach
                                @endif
                                @endif
                                <input type="hidden" name="dId" value="{{$DemandNote->id}}">
                                <input type="hidden" name="mId" value="{{$DemandNote->mo_id}}">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="{{route('issuematerial.index')}}" class="btn btn-cancel">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$(function() {
    $('#select-all').prop('checked', true);
    $('.select-all-sub').prop('checked', true);
});

$('#IssueMaterialForm').validate({
    rules: {
        'inote[]': { required: true },
    },
    messages: {
        'inote[]': { required: "Please select customer" },
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
