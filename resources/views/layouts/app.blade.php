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
    <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/owlcarousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/fontawesome/css/all.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('admin_asset/css/style.css')}}">

    <script src="{{URL::asset('admin_asset/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/jquery-validation/additional-methods.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>

    <script src="{{URL::asset('admin_asset/js/feather.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/js/jquery.slimscroll.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/owlcarousel/owl.carousel.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/apexchart/apexcharts.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/apexchart/chart-data.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/select2/js/custom-select.js')}}"></script>
    <script src="{{URL::asset('admin_asset/js/moment.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/sweetalert/sweetalerts.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/js/script.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>

    <script src="{{URL::asset('admin_asset/plugins/apexchart/apexcharts.min.js')}}"></script>
    <script src="{{URL::asset('admin_asset/plugins/apexchart/chart-data.js')}}"></script>

</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        @include('admin.layout.header')
        @include('admin.layout.menu')
        <div class="page-wrapper">
            @yield('content')
        </div>
        <!-- @include('admin.layout.footer') -->
        @if(Auth::check())
        @else
        @yield('content')
        @endif
    </div>

    <!-- Data Table js -->
    <script type="text/javascript">
    $(document).ready(function() {
        // $('[data-mask]').inputmask()

        // $("#errors_all_page").fadeOut(10000);

        // // $("#update").fadeOut(10000);

        // $('#example2').DataTable();
    });

    function removethis(removeID, removeURL, removeToken) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: removeURL,
                    data: {
                        "_token": removeToken,
                        id: removeID
                    },
                    dataType: 'json',
                    success: function(res) {
                        Swal.fire({
                            type: "success",
                            title: "Deleted!",
                            text: res.msg,
                            confirmButtonClass: "btn btn-success"
                        });
                        $("#id_rmv" + removeID).fadeOut('slow');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your record is safe :)",
                    type: "error",
                    confirmButtonClass: "btn btn-success"
                })
            }
        })
    }


    function changeAllStatus(Statusflag, StatusURL, StatusToken, optionalParameter = null) {
        if(Statusflag == '') return false;
        var idsArr = [];
        $(".checkbox:checked").each(function() {
            idsArr.push($(this).attr('data-id'));
        });

        if (idsArr.length <= 0) {
            alert("Please select atleast one record to status change.");
        } else {
            $.ajax({
                type: "POST",
                url: StatusURL,
                data: {
                    id: idsArr,
                    value: Statusflag,
                    "_token": StatusToken,
                },
                dataType: 'json',
                success: function(res) {
                    if (res.msg == 1) {
                        $.each(idsArr, function(index, value) {

                            if(optionalParameter == "salesorder")  location.reload();

                            if (Statusflag == 'Pending') {
                                var Classes = "badges bg-lightyellow";
                            } else if (Statusflag == 'Complete') {
                                var Classes = "badges bg-lightgreen";
                            } else if (Statusflag == 'Approve') {
                                var Classes = "badges bg-lightgreen";
                            } else if (Statusflag == 'Processing') {
                                var Classes = "badges bg-inprocess";
                            } else if (Statusflag == 'Cancelled') {
                                var Classes = "badges bg-lightgrey";
                            } else if (Statusflag == 'Instore') {
                                var Classes = "badges bg-lightyellow";
                            } else if (Statusflag == 'Active') {
                                var Classes = "badges bg-lightgreen";
                            } else {
                                var Classes = "badges bg-lightred";
                            }
                            $("#status" + value).html('<span class="' + Classes + '">' +
                                Statusflag + '</span>');

                            // $('#statuschange').prop('selectedIndex',0);

                            $('#statuschange').val($('#statuschange option:first-child').val()).trigger('change');

                            $('input:checkbox').each(function(){ //iterate all listed checkbox items
                              this.checked = status; //change ".checkbox" checked status
                            });
                        });
                    }
                }
            });
        }
    }
    function pad (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
    }

    </script>
</body>

</html>