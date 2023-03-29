@extends('layouts.app')
@section('content')
<section class="content">
    <div class="container-fluid">
        <section class="col-lg-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Show Lead</h3>
                    <a href="{{route('lead.index')}}" class=" btn  my_btn  ml-auto"> Back</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><b>Customer Name</b></td>
                            <td>:</td>
                            <td>{{getcustomer($Lead->customer_id)}}</td>
                        </tr>
                        <tr>
                            <td><b>Customer comment</b></td>
                            <td>:</td>
                            <td>{{$Lead->comment}}</td>
                        </tr>
                    </table>
                    @if($CategoryDetails != '')
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Pump Type</th>
                                <th>Pump Model</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($CategoryDetails as $k=>$v)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$v->CName}}</td>
                                <td>{{$v->SName}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </section>
    </div>
    </div>
</section>
@endsection