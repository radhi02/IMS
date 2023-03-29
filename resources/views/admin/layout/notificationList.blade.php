<div class="topnav-dropdown-header">
    <span class="notification-title">Notifications</span>
    <a href="javascript:void(0)" class="clear-noti" id="mark-all"> Mark all as read </a>
</div>
<div class="noti-content">
    <ul class="notification-list">
        @forelse($notifications as $notification)
        @php
        $img="no_preview.png";
        $Usrimg = getUserData($notification->notifiable_id);
        if(!empty($Usrimg))
        {
        $filename = public_path('Admin/Users/'. $Usrimg);
        if($Usrimg != '' && file_exists($filename)) $img=$Usrimg;
        }
        @endphp

        <li class="notification-message">
            <a href="{{route('notifications.show')}}">
                <div class="media d-flex">
                    <span class="avatar flex-shrink-0">
                        <img src="{{ URL::asset('Admin/Users/'. $img) }}" alt="img">
                    </span>
                    <div class="media-body flex-grow-1">
                        <p class="noti-details">
                            <span class="noti-title">{{$notification->data['username']}}</span> 
                            <?php echo htmlspecialchars_decode($notification->data['body']); ?>
                            <span class="noti-title"> {{$notification->data['code']}}</span>
                        </p>
                        <p class="noti-time">
                            <span class="notification-time">
                                {{time_elapsed_string($notification->created_at, true)}}
                            </span>
                        </p>
                    </div>
                    <!-- <div class="media-body flex-grow-1">
                        <p class="noti-details"><?php echo htmlspecialchars_decode($notification->data['body']); ?></p>
                        <p class="noti-time"><span
                                class="notification-time">{{time_elapsed_string($notification->created_at, true)}}</span>
                        </p>
                        <a href="sdsdfsdf.html"> Mark as Read</a> 
                    </div> -->
                </div>
            </a>
        </li>
        @empty
        There are no new notifications
        @endforelse
    </ul>
</div>
<div class="topnav-dropdown-footer">
    <a href="{{route('notifications.show')}}">View all Notifications</a>
</div>

<script>
function sendMarkRequest(id = null, _token = '{{csrf_token()}}') {
    return $.ajax({
        url: "{{ route('markNotification') }}",
        type: "POST",
        data: {
            _token,
            id
        },
        dataType: 'json',
        success: function(res) {
            $(".noti-link span").text(res.count);
            if (res.count == 0) {
                $(".notifications").html(
                    ' <div class="topnav-dropdown-header"> <span class="notification-title">Notifications</span> </div> <div class="noti-content"> <ul class="notification-list"> <li class="notification-message"> <a href="javascript:void(0);"> <div class="media d-flex"> <div class="media-body flex-grow-1"> <p class="noti-details"><span class="noti-title">There are no new notifications</span></p> </div> </div> </a> </li> </ul> </div> <div class="topnav-dropdown-footer"> <a href="{{route('notifications.show')}}">View all Notifications</a> </div>');
            }
            $("#noticount").val(res.count);
        }
    });
}
$(function() {
    $('.mark-as-read').click(function() {
        let request = sendMarkRequest($(this).data('id'));
        request.done(() => {
            $(this).parents('div.alert').remove();
        });
    });
    $('#mark-all').click(function() {
        let request = sendMarkRequest();
        request.done(() => {
            $('div.alert').remove();
        })
    });
});
</script>