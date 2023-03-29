@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Issue Note Details</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(!empty($IssueList))
                    @if(!empty($IssueList->materialnote))
                    @php $tmpNote = json_decode($IssueList->materialnote,true); @endphp
                        <div class="table-top">
                            <div class="wordset">
                                <ul>
                                    <li>
                                        Issue Note : <span style="color: #7367F0;">{{$IssueList['code']}}</span> 
                                    </li>
                                    <li>
                                        Issue Date : <span style="color: #7367F0;">{{ShowNewDateFormat($IssueList['issue_date'])}}</span>    
                                    </li>
                                    <li>
                                        <a title="pdf" href="{{route('issuematerial.download',[$IssueList['id']])}}" ><img src="http://localhost:8000/admin_asset/img/icons/pdf.svg" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
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
                                            <th>Issued QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyProductList">
                                        @if(count($tmpNote) > 0)
                                        @foreach($tmpNote as $nt1)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{RawMaterialName($nt1['RawId'])->name}}</td>
                                            <td>{{RawMaterialName($nt1['RawId'])->code}}</td>
                                            <td>{{$nt1['requiredQun']}}</td>
                                            <td>{{$nt1['IssueQun']}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


