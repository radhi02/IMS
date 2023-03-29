@extends('layouts.app')
@section('content')
<section class="content">
    <div class="container-fluid">




        <section class="col-lg-12 connectedSortable">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Show Department</h3>
                    <a href="{{route('department.index')}}" class=" btn  my_btn  ml-auto"> Back</a>
                </div>
                <div class="card-body">
                    <table class="table">

                        <tr>
                            <td><b>Department Name</b></td>
                            <td>:</td>
                            <td>{{$Department->department_name}}</td>
                        </tr>

                        <tr>
                            <td><b>Department code</b></td>
                            <td>:</td>
                            <td>{{$Department->department_code}}</td>
                        </tr>
                       
                    </table>
                </div>
            </div>
        </section>
    </div>
    </div>
</section>
@endsection