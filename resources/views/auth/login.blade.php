<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Growing Up &amp; With Bpee">
    <meta name="keywords" content="Aplikasi Ekspedisi Terbaik di Indonesia">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Bpee">
    <meta property="og:description" content="Growing Up &amp; With Bpee">
    <meta property="og:site_name" content="https://aplikasi.bpee.tech/">
    <meta property="og:locale" content="id-id">
    <meta property="og:url" content="https://aplikasi.bpee.tech">
    <meta property="og:image" content="{{ asset('img/logo-bpee.png') }}">
    <meta property="og:image:type" content="image/jpg" />
    <meta property="og:image:width" content="200" />
    <meta property="og:image:height" content="200" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo-bpee.png') }}">
    <meta http-equiv="refresh" content="1500">
    
    <title>Bpee | 
    Growing &amp; Aplikasi Ekspedisi Terbaik yang pernah ada</title>
    <!--begin::Web font -->
    <script src="{{ asset('assets/app/js/font-loader.js') }}"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
     <link rel="icon" type="image/png" href="{{ asset('img/logo-bpee.png') }}"/>
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="{{ asset('asset-login/vendor/bootstrap/css/bootstrap.min.css') }}">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="{{ asset('asset-login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="{{ asset('asset-login/vendor/animate/animate.css') }}">
 
     <link rel="stylesheet" type="text/css" href="{{ asset('asset-login/vendor/css-hamburgers/hamburgers.min.css') }}">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="{{ asset('asset-login/vendor/select2/select2.min.css') }}">
     <!--===============================================================================================-->
     <link rel="stylesheet" type="text/css" href="{{ asset('asset-login/css/util.css') }}">
     <link rel="stylesheet" type="text/css" href="{{ asset('asset-login/css/main.css') }}">
    
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" style="margin-top: -5%" data-tilt>
                    <a href="/">
                        <img src="{{ asset('img/logo-bpee.png') }}" alt="IMG">
                    </a>
                </div>
                
                <form class="login100-form" method="POST" action="{{ url('auth/login') }}" style="margin-top: -10%">
                    @csrf
                    <div>
                        @include("template.notif")
                    </div>
                    <span class="login100-form-title">
                        Login Bpee
                    </span>
                    <div class="wrap-input100 " style="margin-top: -10%">
                        <input class="input100" type="text" id="email" name="email" placeholder="Email atau Username" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>
                    
                    @error('email')
                    <label><strong style="color: red">{{ $message }}</strong></label>
                    @enderror
                    
                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100 @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password" id="password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    
                    @error('password')
                    <label><strong style="color: red">{{ $message }}</strong></label>
                    @enderror
                    
                    <center>
                        <label class="checkbox-inline">
                            <input type="checkbox" value="" id="chek"> 
                            Show Password
                        </label>
                    </center>
                    
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>
                    
                    {{-- <div class="text-center">
                        <a class="txt2" href="{{ url('register') }}">
                            <b>Create your Account</b>
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div> --}}

                </form>
            </div>
        </div>
    </div>
</body>

<script src="{{ asset('asset-login/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('asset-login/vendor/bootstrap/js/popper.js') }}"></script>
  <script src="{{ asset('asset-login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('asset-login/vendor/select2/select2.min.js') }}"></script>
  <!--===============================================================================================-->
  <script src="{{ asset('asset-login/vendor/tilt/tilt.jquery.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(function(){
            $('#chek').change(function()
            {
                if($(this).is(':checked')) {
                    $("#password").attr("type", "text");
                }else{   
                    $("#password").attr("type", "password");
                }
            });
        });
    });
</script>
</html>

