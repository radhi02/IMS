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
</script>
<?php $bageStatus = ['Pending'=>'bg-lightyellow','Complete'=>'bg-lightgreen','Processing'=>'bg-inprocess','Cancelled'=>'bg-lightgrey','Instore'=>'bg-lightyellow'] ?>

<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Issue List</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="{{URL::asset('admin_asset/img/icons/search-white.svg')}}"
                                alt="img"></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Demand Note</th>
                            <th>Manufacturing Order</th>
                            <th>Demand Date</th>
                            <th>Issue Date</th>
                            <th>Consumption Status</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($IssueList as $k=>$values)
                        <tr id="id_rmv{{$values->id}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$values->dncode}}</td>
                            <td>{{$values->mocode}}</td>
                            <td>{{ShowNewDateFormat($values->dndate)}}</td>
                            <td>{{ShowNewDateFormat($values->issue_date)}}</td>
                            <td id="status{{$values->id}}"><span
                                    class="badges {{$bageStatus[$values->status]}}">{{$values->status}}</span></td>
                            <td>
                                @if($values->status == "Pending")
                                <a class="me-3" href="{{route('consumption.create',['id'=>$values->id])}}" title="Consume">
                                    <img src="{{URL::asset('admin_asset/img/icons/consumption.svg')}}" alt="img" style=" height: 24px; width: 24px; ">
                                </a>
                                @endif
                                <a class="me-3" href="{{route('issuematerial.show',$values->id)}}" title="Issue">
                                    <img src="{{URL::asset('admin_asset/img/icons/eye.svg')}}" alt="img">
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection