@extends('template.document')

@section('data')
@if(Request::segment(1)=="layanan" && Request::segment(2)==null)
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	@csrf
	<table class="table table-responsive table-striped" width="100%">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Nama Layanan</th>
				<th>Kode Layanan</th>
				<th>
					<center>Action</center>
				</th>
			</tr>
		</thead>
		
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<tr>
					<td>{{ $key+1 }}</td>
					<td>{{ strtoupper($value->nm_layanan) }}</td>
					<td>{{ strip_tags($value->kode_layanan) }}</td>
					<td>
						@if(get_admin())
						{!! inc_edit($value->id_layanan) !!}
						@endif
					</td>
				</tr>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('layanan') }}@else{{ route('layanan.update', $data->id_layanan) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	<div class="row">

	<div class="form-group col-md-6">
		<label for="kode_layanan">
			<b>Kode Layanan</b> <span class="text-danger">* </span>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" placeholder="Masukan Kode layanan" required id="kode_layanan" name="kode_layanan" maxlength="2" value="@if(old('kode_layanan')!=null)){{ old('kode_layanan') }}@elseif(isset($data->kode_layanan)){{ $data->kode_layanan }}@endif">
		
		@if ($errors->has('kode_layanan'))
		<label style="color: red">
			{{ $errors->first('kode_layanan') }}
		</label>
		@endif
	</div>
	
	<div class="form-group col-md-6">
		<label for="nm_layanan">
			<b>Nama Layanan</b> <span class="text-danger">* </span>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" placeholder="Masukan Nama Layanan" required id="nm_layanan" name="nm_layanan" maxlength="40" value="@if(old('nm_layanan')!=null)){{ old('nm_layanan') }}@elseif(isset($data->nm_layanan)){{ $data->nm_layanan }}@endif">
		
		@if ($errors->has('nm_layanan'))
		<label style="color: red">
			{{ $errors->first('nm_layanan') }}
		</label>
		@endif
	</div>

	<div class="col-md-12 text-right">
		@include('template.inc_action')
	</div>
	</div>
</form>
@endif

@endsection

@section('script')

@endsection