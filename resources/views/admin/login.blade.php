<!DOCTYPE html>
<html lang="en">
<head>
    <title>IPTV / Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--===============================================================================================-->
    <link rel="icon" href="{{asset('/images/favicon-32x32.png')}}"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/fonts/iconic/css/material-design-iconic-font.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/vendor/animate/animate.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/vendor/css-hamburgers/hamburgers.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/vendor/animsition/css/animsition.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/vendor/select2/select2.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/vendor/daterangepicker/daterangepicker.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/admin/login_asset/css/main.css')}}">
    <!--===============================================================================================-->
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            transition: background-color 5000s ease-in-out 0s;
            color:white !important;
            -webkit-text-fill-color: white !important;
        }
        .register-link{
            color:#0f35c5;
        }
        .register-link:hover{
            color:white !important;

        }

    </style>
</head>
<body>

<div class="limiter">
    <div class="container-login100" style="background-image: url({{asset('/images/login.jpg')}});background-color: rgba(255,255,255,0.5);">
        <div class="wrap-login100">
            {{--<form class="login100-form validate-form" autocomplete="off" method="post" action="{{route('login_asset')}}">--}}
            <form autocomplete="off" method="POST" action="{{route('admin.login')}}">
                @csrf
					<span class="login100-form-logo">
                        <img src="{{asset('/images/logo.png')}}" class="zmdi zmdi-landscape" style="width:120px; height:120px;border-radius:50%">
					</span>
                <span class="login100-form-title p-b-34 p-t-27">
						Log in
					</span>
                @if ($errors->any())
                    <div class="alert alert-danger" style="background:none;border:none">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="color:darkred">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="wrap-input100 validate-input" data-validate = "Enter email">

                    <input class="input100" type="email" name="email" placeholder="Email" required>
                    {{--@if ($errors->has('email'))--}}
                        {{--<span class="invalid-feedback" role="alert">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <input class="input100" type="password" name="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>

                <div style="display: flex; justify-content: space-between">
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">
                            Remember me
                        </label>
                    </div>
                    <div style="margin-top:5px;">
                        <a class="txt1 register-link" href="#">
                            Forgot Password?
                        </a>
                    </div>

                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit">
                        Login
                    </button>
                </div>

                <div class="text-center p-t-30" style="margin-top:30px">
                    <h6 class="txt1">Don't you have account?<span><a href="register" class="register-link" style="margin-left:10px;">Sign Up</a> </span> </h6>

                </div>
            </form>
        </div>
    </div>
</div>


<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
<script src="{{asset('/admin/login_asset/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('/admin/login_asset/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('/admin/login_asset/vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{asset('/admin/login_asset/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('/admin/login_asset/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('/admin/login_asset/vendor/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('/admin/login_asset/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('/admin/login_asset/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('/admin/login_asset/js/main.js')}}"></script>

</body>
</html>
