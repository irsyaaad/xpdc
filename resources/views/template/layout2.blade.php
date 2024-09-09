<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="utf-8" />
	<title>
		Lsj Express @if(isset(Session("module")["nm_module"]))
		| {{ strtoupper(Session("module")["nm_module"]." - ".Request::segment(1)) }}
		@endif
		
	</title>
	<meta name="description" content="Latest updates and statistic charts">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	
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
			font-weight: bold;
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
			font-size: 10pt;
		}
	</style>
	@yield("style")

</head>

<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
	

	<div class="m-grid m-grid--hor m-grid--root m-page">
		
		@include('template.header')

		@include('template.breadcumb')
		
		@include('inc.delete')
	</body>
	<!-- for Data table-js -->
	<script type="text/javascript">
		// js for img-icon
		$(document).ready(function() {
			// when ready on load
			if ($(window).width() > 300 && $(window).width() < 1000) {
				$("#img-logo").css("width", "30%");
			}
			else {
				$("#img-logo").css("width", "100%");
			}

			// when ready but inframe inspect
			$(window).resize(function() {
				if ($(window).width() > 300 && $(window).width() < 1000) {
					$("#img-logo").css("width", "30%");
				}
				else {
					$("#img-logo").css("width", "100%");
				}
			});
		});
	</script>

	@yield('script')

	</html>