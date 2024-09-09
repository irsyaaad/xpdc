
@extends('template.layout')

@section("style")
<style type="text/css">
	.label-form{
		font-weight: bold; color: black
	}
</style>
@endsection

@section('content')

<div class="m-content" style="margin-top: -25px">
	<div class="m-portlet m-portlet--mobile">
		
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						DATA {{ strtoupper(get_menu(Request::segment(1))) }}
					</h3>
				</div>
			</div>
			
			{{-- @if(Request::segment(2)==null or Request::segment(2)=="filter" or Request::segment(2)=="page")
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
						@include("template.plus")
					</li>
				</ul>
			</div>
			@endif --}}
			
		</div>
		
		<!-- For Body Data Content -->
		<div class="m-portlet__body">
			
			@include('template.notif')
			
			@yield('data')
			
		</div>

	</div>
</div>

@endsection