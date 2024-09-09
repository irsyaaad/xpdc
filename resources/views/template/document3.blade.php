
@extends('template.layout')

@section("style")
<style type="text/css">
	.label-form{
		font-weight: bold; color: black
	}
</style>
@endsection

@section('content')

<div class="m-content">
	@include('template.notif')
			
	@yield('data')
</div>

@endsection