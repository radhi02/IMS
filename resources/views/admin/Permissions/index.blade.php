@extends('layouts.app')
@section('content')


<style>
.checkbox_other_list {
    margin: 10px 2% 0 !important;
    width: 10%;
}

.checkbox_list {
    margin: 10px 2% 0 !important;
    width: 10%;
}

@media only screen and (max-width: 767px) {
    .checkbox_list {
        margin: 10px 0 0 !important;
        width: 100%;
        white-space: normal;
    }

    .checkbox_other_list {
        margin: 10px 0% 0 !important;
        width: 50%;
        white-space: normal;
    }
}
</style>
<script>
@if(Session::has('msg') != '')
Swal.fire(
    'Success!',
'{{ Session::has("msg") ? Session::get("msg") : '
    ' }}',
    'success'
)
@endif
</script>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Module List</h4>
            <h6>Manage {{$role->name}} permission</h6>
        </div>
        <div class="page-btn">
            <a href="{{route('Module.new.creates')}}" class="btn btn-added"><img
                    src="{{URL::asset('admin_asset/img/icons/plus.svg')}}" alt="img" class="me-1">Add New Module</a>
        </div>
    </div>
    <form method="post" action="{{route('Module.GivePermission')}}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @foreach($perant as $per)
                            <tr>
                                <td>
                                    @php $cheked_id= CheckPermissionExitOrNot($per['id'],$role_id); @endphp
                                    <input type="checkbox" class="checkval_parent  customeparent{{$per['id']}}"
                                        @if(isset($cheked_id[$per['id']])) checked @endif data-parent="{{$per['id']}}">
                                    <b>{{$per['Name']}}<b>
                                            <div style="display: flex; flex-wrap: wrap; margin-top: 15px;">
                                                @foreach($permisson as $child)
                                                @if($child->moduleName == $per['Name'])
                                                @php $module = explode("-",$child->name); @endphp
                                                @if($per['Name'] =="OrderBook")
                                                <div class="checkbox checkbox_list" style="">
                                                    <label>
                                                        <input type="hidden" name="role" value="{{$role_id}}">
                                                        <input type="checkbox" name="child[]"
                                                            class="child{{$per['id']}}" id="{{$per['id']}}"
                                                            value="{{$child->name}}" @if(isset($cheked_id[$child->id]))
                                                        checked @endif > @php echo ucfirst( str_replace("_","
                                                        ",$module[1])); @endphp</label>
                                                </div>
                                                @else
                                                @php $cheked_id= CheckPermissionExitOrNot($child->id,$role_id); @endphp
                                                <div class="checkbox checkbox_other_list" style="">
                                                    <label>
                                                        <input type="hidden" name="role" value="{{$role_id}}">
                                                        <input type="checkbox" name="child[]"
                                                            class="child{{$per['id']}}" id="{{$per['id']}}"
                                                            value="{{$child->name}}" @if(isset($cheked_id[$child->id]))
                                                        checked @endif >
                                                        @php echo ucfirst( str_replace("_"," ",$module[1])); @endphp
                                                    </label>
                                                </div>
                                                @endif
                                                @endif
                                                @endforeach
                                            </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                    <!-- <a href="{{route('category.index')}}" class="btn btn-cancel">Cancel</a> -->
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$(document).ready(function() {
    $('#RoleForm').validate({
        rules: {
            name: {
                required: true
            },
            Status: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Please enter a Role name "
            },
            Status: {
                required: "Please select status"
            },
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
});

$(".checkval_parent").click(function() {
    var parent = $(this).attr('data-parent');
    var checkparent = $(".customeparent" + parent).is(':checked');
    if (checkparent == true) {
        $($(".child" + parent)).each(function() {
			this.checked = true;
		});
    } else if (checkparent == false) {
        $($(".child" + parent)).each(function() {
			this.checked = false;
		});
    }
});
</script>
@endsection