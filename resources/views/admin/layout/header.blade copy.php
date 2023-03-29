<!-- User Profile Image -->
@php
  $img="Admin/Users/no_preview.png";
  $tmp = Auth::user()->Image;
  $filename =  public_path('Admin/Users/'. $tmp);
  if($tmp != '' && file_exists($filename))
  {
      $img='Admin/Users/'.$tmp;
  }
 @endphp

 <!-- Company Logo -->
 @php
  $tmp1 = FetchCompanyLogo();
  $companyimg = 'Admin/Company/imslogo.png';
  $filename1 =  public_path('Admin/Company/'. $tmp1);
  if($tmp1 != '' && file_exists($filename1))
  {
      $companyimg ='Admin/Company/'.$tmp1;
  }
 @endphp

<div class="header">
   <div class="header-left active">
         <a href="{{route('home')}}" class="logo">
            <img src="{{ URL::asset($companyimg) }}" alt="img">
         </a>
         <a href="{{route('home')}}" class="logo-small">
            <img src="{{ URL::asset($companyimg) }}" alt="img">
         </a>
         <a id="toggle_btn" href="javascript:void(0);">
         </a>
   </div>
   <a id="mobile_btn" class="mobile_btn" href="#sidebar">
         <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
         </span>
   </a>
   <ul class="nav user-menu">
         <!-- <li class="nav-item dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link noti-link" data-bs-toggle="dropdown">
               <img src="{{URL::asset('admin_asset/img/icons/notification-bing.svg')}}" alt="img"> <span class="badge rounded-pill">{{$noticount}}</span>
               <input type="hidden" value="{{$noticount}}" id="noticount" name="noticount">
            </a>
            <div class="dropdown-menu notifications">
            </div>
         </li> -->
         <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
               <span class="user-img"><img src="{{ URL::asset($img) }}" alt="img">
                     <span class="status online"></span></span>
            </a>
            <div class="dropdown-menu menu-drop-user">
               <div class="profilename">
                     <div class="profileset">
                        <span class="user-img"><img src="{{ URL::asset($img) }}" alt="img">
                           <span class="status online"></span></span>
                        <div class="profilesets">
                           <h6>{{ucfirst(Auth::user()->first_name)}}</h6>
                           <h5>{{ucfirst(checkRole(Auth::user()->Role))}}</h5>
                        </div>
                     </div>
                     <hr class="m-0">
                     <a class="dropdown-item" href="profile.html"> <i class="me-2" data-feather="user"></i> My
                        Profile</a>
                     <a class="dropdown-item" href="{{route('company.create')}}"><i class="me-2"
                           data-feather="settings"></i>Settings</a>
                     <hr class="m-0">
                     <a class="dropdown-item logout pb-0" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="{{URL::asset('admin_asset/img/icons/log-out.svg')}}"
                           class="me-2" alt="img">Logout</a>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: one;"> 
                     {{ csrf_field() }} </form>
               </div>
            </div>
         </li>
   </ul>
   <div class="dropdown mobile-user-menu">
         <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
         <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="profile.html">My Profile</a>
            <a class="dropdown-item" href="{{route('company.create')}}">Settings</a>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
         </div>
   </div>
</div>
<script>
$(function() {
    $(".noti-link").click(function() {
         if($("#noticount").val() == 0) {
            $(".notifications").html(' <div class="topnav-dropdown-header"> <span class="notification-title">Notifications</span> </div> <div class="noti-content"> <ul class="notification-list"> <li class="notification-message"> <a href="javascript:void(0);"> <div class="media d-flex"> <div class="media-body flex-grow-1"> <p class="noti-details"><span class="noti-title">There are no new notifications</span></p> </div> </div> </a> </li> </ul> </div> <div class="topnav-dropdown-footer"> <a href="{{route('notifications.show')}}">View all Notifications</a> </div>');
         } else {
            $.ajax({
               url: "{{ route('fetchNotifications') }}",
               type: "POST",
               data: {
                     _token: '{{csrf_token()}}'
               },
               success: function(res) {
                  $(".notifications").html(res.html);
                  $("#noticount").val(res.count);
               }
            });
         }
   });
});
</script>