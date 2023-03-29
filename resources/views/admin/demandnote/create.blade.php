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
            <h4>Create Demand Note</h4>
            <h6>Manage demand note</h6>
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
    <form id="DemandNoteForm" method="post" action="{{route('demandnote.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="card-sales-split" style="margin-bottom: 25px;">
                    <h2>Manufacturing Order : {{$ManufactureOrder->code}}</h2>
                </div>
                <div class="row">
                    <div class="table-responsive mb-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr. No</th></th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Required QTY</th>
                                    <th>Remained QTY</th>
                                    <th>Demand QTY</th>
                                    <th>UNIT</th>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id='select-all'>
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbodyProductList">
                                @if(isset($ManufactureOrder) && !empty($ManufactureOrder->bom_detail))
                                @php $tmpOrderBOM = json_decode($ManufactureOrder->bom_detail,true); $i=1; @endphp
                                @if(count($tmpOrderBOM) > 0)
                                @foreach($tmpOrderBOM as $bom1)
                                @if($bom1['remained_quantity'] > 0) 
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{RawMaterialName($bom1['id'])->name}}</td>
                                    <td>{{RawMaterialName($bom1['id'])->code}}</td>
                                    <td>{{$bom1['quantity']}}</td>
                                    <td>{{$bom1['remained_quantity']}}</td>
                                    <td><div class="form-group" style="margin-bottom: 0px;"><input type="number" style="padding:5px 5px" name="dnote[{{$bom1['id']}}][demandQun]" min="0" class="txtqun" value="{{$bom1['remained_quantity']}}" max="{{$bom1['remained_quantity']}}"></div></td>
                                    <td>{{UnitName($bom1['unitid'])->unit_name}}</td>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" class="checkbox select-all-sub" data-id="{{$bom1['id']}}"
                                                name="dnote[{{$bom1['id']}}][chk]">
                                            <span class="checkmarks"></span>
                                        </label>
                                        <input type="hidden" name="dnote[{{$bom1['id']}}][requiredQun]" value="{{$bom1['remained_quantity']}}">
                                        <input type="hidden" name="dnote[{{$bom1['id']}}][RawId]" value="{{$bom1['id']}}">
                                        <input type="hidden" name="txtValidateRaw[{{$bom1['id']}}]" value="{{RawMaterialName($bom1['id'])->code}}">
                                    </td>
                                </tr>
                                <?php $i++ ?> 
                                @endif
                                @endforeach
                                @endif
                                @endif
                                <input type="hidden" name="mId" value="{{$ManufactureOrder->id}}">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <a href="{{route('manufacture.index')}}" class="btn btn-cancel">Cancel</a>
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
</script>
@endsection
