<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>:: Inventory Management System ::</title>
        
        <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('admin_asset/img/favicon.png')}}">
        <link rel="stylesheet" href="{{URL::asset('admin_asset/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/fontawesome/css/fontawesome.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('admin_asset/plugins/fontawesome/css/all.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('admin_asset/css/style.css')}}">

        <script src="{{URL::asset('admin_asset/js/jquery-3.6.0.min.js')}}"></script>
        <script src="{{URL::asset('admin_asset/js/feather.min.js')}}"></script>
        <script src="{{URL::asset('admin_asset/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{URL::asset('admin_asset/js/script.js')}}"></script>

    </head>
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
    <body class="account-page">
        <div class="main-wrapper">
            <div class="account-content">
                <div class="login-wrapper">
                    <div class="login-content">
                        <div class="login-userset">
                            <form method="POST" action="{{ route('login') }}">
                            @csrf
                                <div class="login-logo">
                                    <!-- <img src="{{ URL::asset($companyimg) }}" alt="img"> -->
                                </div>
                                <div class="login-userheading">
                                    <h3>Sign In</h3>
                                    <h4>Please login to your account</h4>
                                </div>
                                <div class="form-login">
                                    <label>Email</label>
                                    <div class="form-addons">
                                        <input type="text" placeholder="Enter your email address" name="email">
                                        <img src="{{URL::asset('admin_asset/img/icons/mail.svg')}}" alt="img">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-login">
                                    <label>Password</label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-input" placeholder="Enter your password" name="password">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- <div class="form-login">
                                    <div class="alreadyuser">
                                        <h4><a href="forgetpassword.html" class="hover-a">Forgot Password?</a></h4>
                                    </div>
                                </div> -->
                                <div class="form-login">
                                    <button type="submit" class="btn btn-login" href="{{route('home')}}">{{ __('Sign In') }}</button>
                                </div>
                                <!-- <div class="signinform text-center">
                                    <h4>Don’t have an account? <a href="signup.html" class="hover-a">Sign Up</a></h4>
                                </div> -->
                            </form>
                        </div>
                    </div>
                    <div class="login-img">
                        <img src="{{URL::asset('admin_asset/img/login.jpg')}}" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>