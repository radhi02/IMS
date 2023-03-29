<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>:: Inventory Management System ::</title>

  <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('admin_asset/img/favicon.png')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/css/animate.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/css/style.css')}}">


    <script src="{{URL::asset('admin_asset/js/jquery-3.6.0.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/feather.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/jquery.slimscroll.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/jquery.dataTables.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/dataTables.bootstrap4.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/bootstrap.bundle.min.js')}}">
    <script src="{{URL::asset('admin_asset/plugins/apexchart/apexcharts.min.js')}}">
    <script src="{{URL::asset('admin_asset/plugins/apexchart/chart-data.js')}}">
    <script src="{{URL::asset('admin_asset/plugins/select2/js/select2.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/moment.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/bootstrap-datetimepicker.min.js')}}">
    <script src="{{URL::asset('admin_asset/plugins/sweetalert/sweetalert2.all.min.js')}}">
    <script src="{{URL::asset('admin_asset/plugins/sweetalert/sweetalerts.min.js')}}">
    <script src="{{URL::asset('admin_asset/js/script.js')}}">

</head>
<body class="hold-transition sidebar-mini layout-fixed @if($errors->has('email')) modal-open  @endif  ">
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
<div class="wrapper login_section">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{URL::asset('admin_asset/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div>

  <button type="button" class="my_login_btn  btn btn-danger clickWhenError" data-toggle="modal" data-target="#myModal">
    Reset Password 
  </button>


  <!-- The Modal -->
<div class="modal fade  @if($errors->has('email')) show  @endif" id="myModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal body -->
      <div class="modal-body p-0">

        <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>

        <div class="login_box">
          <div class="login_left">
            <img src="{{URL::asset('admin_asset/dist/images/logo_white.png')}}">
          </div>
          <div class="login_form_box">
                

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <form method="POST"  class="login_form active" action="{{ route('password.email') }}">
                        @csrf
                    <h1 class="text-center">   Reset   </h1>
                    <div class="form-group data">
                        <label for="email">Email address:</label>
                        <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert" id="custome_error">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                   
                    
                    
                    
                    <button type="submit" class="btn login_btn btn-block ">
                        {{ __('Reset Password') }}
                        </button>
                

                </form>


             

          </div>
        </div>
      </div>

    </div>
  </div>
</div>


  
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright ©  2022. Designed by :   <a href="https://thesanfinity.com/" target="_blank">Sanfinity Creative Solution</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>


</div>
<!-- ./wrapper -->




<!-- jQuery -->
<script src="{{URL::asset('admin_asset/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{URL::asset('admin_asset/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{URL::asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{URL::asset('admin_asset/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{URL::asset('admin_asset/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{URL::asset('admin_asset/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{URL::asset('admin_asset/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{URL::asset('admin_asset/plugins/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{URL::asset('admin_asset/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{URL::asset('admin_asset/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{URL::asset('admin_asset/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>


<!-- DataTables  admin_asset/Plugins -->
<script src="{{URL::asset('admin_asset/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables/dataTables.select.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('admin_asset/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{URL::asset('admin_asset/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{URL::asset('admin_asset/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{URL::asset('admin_asset/dist/js/pages/dashboard.js')}}"></script>


<script>
$(document).ready(function(){
  $(".login_form_btn").click(function(){
    $(".login_form").removeClass("active");
    $(".forgot_password_form").removeClass("active");
    $(".register_form").addClass("active");

  });

  $(".register_form_btn").click(function(){
    $(".register_form").removeClass("active");
    $(".forgot_password_form").removeClass("active");
    $(".login_form").addClass("active");

  });

  $(".forgot_form_btn").click(function(){
    $(".register_form").removeClass("active");
    $(".login_form").removeClass("active");
    $(".forgot_password_form").addClass("active");

  });

  popup();
  function popup()
  {
    
   
        var errorhave = "@error('email') @if(isset($message)) <?php echo $message; ?> @endif @enderror";
   
          if(isNaN(errorhave)== true)
          {
          
            $(".clickWhenError").click();
          }
          else
          {
            $("#custome_error").html("");
          }

    if("<?php echo Route::currentRouteName(); ?>" =="password.request")
    {
      $(".clickWhenError").click();

    }
  }

 
});

$(".close_btn").on("click",function(){

  $("#custome_error").html(" ");
  $("#email").html(" ");
});
</script>


</body>

</html>
