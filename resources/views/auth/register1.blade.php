<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
        LSJ-Express | Register
    </title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

<link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="{{ asset('assets/demo/default/media/img/logo/favicon.ico') }}" />

</head>
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-1" id="m_login" style="background-image: url({{ asset('assets/app/media/img//bg/bg-1.jpg') }});">
            <div class="m-grid__item m-grid__item--fluid    m-login__wrapper">
                <div class="m-login__container">
                    <div class="m-login__logo">
                        <a href="#">
                            <img src="{{ asset('assets/app/media/img//logos/logo-1.png') }}">
                        </a>
                    </div>
                    <div class="m-login__signin">
                        <div class="m-login__head">
                            <h3 class="m-login__title">
                                Sign In To Admin
                            </h3>
                        </div>

                        @include('template.notif')

                        <form method="POST" class="m-login__form m-form" action="{{ url('auth/register') }}">
                            @csrf
                            
                            <div class="form-group m-form__group">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Masukan Username" required autocomplete="username" autofocus>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group m-form__group">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Masukan Email" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group m-form__group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Masukan Password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group m-form__group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  placeholder="Confirm password" required autocomplete="new-password">
                            </div>

                            <div class="form-group m-form__group">
                                <select class="form-control" id="id_perush" name="id_perush">
                                    <option>-- Pilih Perusahaan --</option>
                                    @foreach($perusahaan as $key => $value)
                                    <option value="{{ $value->id_perush }}">{{ $value->nm_perush }}</option>
                                    @endforeach
                                </select>

                                @error('id_perush')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="row m-login__form-sub">
                                <div class="col m--align-left m-login__form-left">
                                    <input class="form-check-input" type="checkbox" name="show" id="show" {{ old('show') ? 'checked' : '' }}>
                                    <label>
                                        Show Password
                                    </label>
                                </div>

                                <div class="col m--align-right m-login__form-right">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>

                            <div class="m-login__form-action" style="margin-top: -4%">

                                <button type="submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary" id="m_login_signin_submit">
                                    {{ __('Register') }}
                                </button>

                                <div>
                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password ?') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="m-login__account">
                        <span class="m-login__account-msg">
                            Do you have an account yet ?
                        </span>
                        &nbsp;&nbsp;
                        <a href="{{ url('/') }}" class="m-link m-link--light m-login__account-link">
                            Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(function(){
              $('#show').change(function()
              {
                  if($(this).is(':checked')) {
                    $("#password").attr("type", "text");
                    $("#password-confirm").attr("type", "text");
                }else{   
                    $("#password").attr("type", "password");
                    $("#password-confirm").attr("type", "password");
                }
            });
          });
        });
    </script>
</body>
</html>

