<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="utf-8" />
    <title>Lsj Express</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/base/style.custom.css') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" />
    <script src="{{ asset('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/base/scripts.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/app/js/dashboard.js') }}" type="text/javascript"></script>
    <style type="text/css">
        .span-required{
            color: red;
        }
        
        .span-success{
            color: green;
        }
        
        .class-edit{
            text-decoration: underline;
            /* font-weight: bold; */
        }
        
        @font-face {
            font-family: poppins;
            src: url("{{ asset('font/Poppins-Light.otf') }}");
            font-weight: bold;
        }
        
        body {
            width: 100% !important;
            height: 100% !important;
            font-family: 'poppins' !important;
            font-size: 10pt !important;
            
        }
        
        .m-subheader__title {
            font-family: 'poppins' !important;
        }
        
        .m-portlet__head-text{
            font-family: 'poppins' !important;
        }
        
        .select2 {
            width:100%!important;
        }
        
        .badge.badge-md {
            font-size: 9pt;
        }
        
        table tbody tr td{
            text-align: center;
        }
        table thead tr th{
            text-align: center;
        }
        .td-nama{
            text-align: left;
        }
        .td-garis{
            border: 1px solid rgb(0, 0, 0);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">
                @yield('content')
            </div>
        </div>
    </div>
</body>
<script>
    if ($(window).width() <= 768) {
        $("#collapseOne").removeClass("show");
        $("#headingOne").show();
    }else{
        $("#collapseOne").addClass("show");
        $("#headingOne").hide();
    }
    
    $(window).resize(function() {
        if ($(window).width() <= 768) {
            $("#collapseOne").removeClass("show");
            $("#headingOne").show();
        }else{
            $("#collapseOne").addClass("show");
            $("#headingOne").hide();
        }
    });
</script>
@yield('script')