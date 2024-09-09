<html lang="en" >
<head>
	<meta charset="utf-8" />
	<title>
		Metronic | Dashboard
	</title>
	<meta name="description" content="Latest updates and statistic charts">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<script src="https://cdn.bootcss.com/webfont/1.6.16/webfontloader.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<link href="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />

	<link rel="shortcut icon" href="{{ asset('assets/demo/default/media/img/logo/favicon.ico') }}" />
</head>

<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
	
<div class="m-grid m-grid--hor m-grid--root m-page">
	@include('header')
	@include('sidebar')
	@include('frame')
	
</body>

<script src="{{ asset('assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/app/js/dashboard.js') }}" type="text/javascript"></script>

<!-- for Data table-js -->
<script type="text/javascript">
	var DatatableHtmlTableDemo=function(){
	var e=function(){
		$(".m-datatable").mDatatable({
			search:{input:$("#generalSearch")},
			columns:[{field:"Deposit Paid",
			type:"number"},{field:"Order Date",
			type:"date",
			format:"YYYY-MM-DD"}]})};
		return{
			init:function(){e(
				)}}}();
	jQuery(document).ready(function(){
		DatatableHtmlTableDemo.init()
	});
</script>
</html>