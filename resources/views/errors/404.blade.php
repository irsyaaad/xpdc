@extends('template.document')

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,700" rel="stylesheet">
<link rel="shortcut icon" href="{{ asset('img/logo.png') }}" />

<!-- Custom stlylesheet -->
<link type="text/css" rel="stylesheet" href="{{ url('error/css/style.css') }}" />

@section('data')
<center>
	<div class="notfound">
		<div class="notfound-404">
			<h1>4 <span></span> 4</h1>
		</div>
		<h2>Oops! Anda tidak punya akses</h2>
		<p>Maaf browser tidak bisa menemukan halaman yang anda cari, silahkan hubungi administrator untuk info lebih lanjut</p>
		<a href="{{ url()->previous() }}">Kembali</a>
		
		<a href="{{ url('dashboard') }}">Dashboard</a>
	</div>
</center>
@endsection

@section("script")

@endsection