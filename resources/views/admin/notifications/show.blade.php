@extends('layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>All Notifications</h4>
            <h6>View your all activities</h6>
        </div>
    </div>
    <div class="activity">
        <div class="activity-box">
            <ul class="activity-list">
            @forelse($notifications as $notification)
                @php
                    $img="no_preview.png";
                    $Usrimg = getUserData($notification->notifiable_id);
                    if(!empty($Usrimg))
                    {
                        $filename = public_path('Admin/Users/'. $Usrimg);
                        if($Usrimg != '' && file_exists($filename)) $img=$Usrimg;
                    }
                    $data = json_decode($notification->data,true);
                @endphp
                <li>
                    <div class="activity-user">
                        <a href="{{$data['Url']}}" title="" data-toggle="tooltip" data-original-title="Lesley Grauer">
                            <img alt="img" src="{{ URL::asset('Admin/Users/'. $img) }}" class=" img-fluid">
                        </a>
                    </div>
                    <div class="activity-content">
                        <div class="timeline-content">
                            <a href="{{$data['Url']}}" class="name">{{$data['username']}}</a> 
                            <?= htmlspecialchars_decode($data['body']); ?>
                            <a href="javascript:void(0);">{{$data['code']}}</a>
                            <span class="time">{{time_elapsed_string($notification->created_at, true)}}</span>
                        </div>
                    </div>
                </li>
                @empty
                    There are no new notifications
                @endforelse
            </ul>
        </div>
    </div>
</div>

@endsection