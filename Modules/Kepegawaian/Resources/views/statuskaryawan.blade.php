@extends('template.document')

@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-hover table-responsive" >
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Kode Status Karyawan</th>
				<th>Status Karyawan</th>
				<th>Durasi</th>
				<th>
					<center>Action</center>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ strtoupper($value->id_status_karyawan) }}</td>
				<td>{{ strtoupper($value->nm_status_karyawan) }}</td>
				@if($value->durasi > 12)
				<td> ~ </td>
				@else
				<td>{{ strtoupper($value->durasi) }} Bulan</td>
				@endif
				<td>
					@if (get_admin())
					{!! inc_edit($value->id_status_karyawan) !!}
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create" ){{ url(Request::segment(1)) }}@else{{ route('statuskaryawan.update', $data->id_status_karyawan) }}@endif" enctype="multipart/form-data">
	
	@if(Request::segment(3)=="edit" )
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="form-group m-form__group">
		<label for="id_jenis">
			<b>Kode Status Karyawan</b> <span class="span-required"> *</span>
		</label>
		
		<input type="text" id="id_status_karyawan" name="id_status_karyawan" maxlength="4" class="form-control" value="@if(isset($data->id_status_karyawan)){{ $data->id_status_karyawan }}@else{{ old('id_status_karyawan') }}@endif">
		
		@if ($errors->has('id_jenis'))
		<label style="color: red">
			{{ $errors->first('id_jenis') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="nm_jenis">
			<b>Nama Status Karyawan</b> <span class="span-required"> *</span>
		</label>
		
		<input type="text" id="nm_status_karyawan" name="nm_status_karyawan" maxlength="32" class="form-control" value="@if(isset($data->nm_status_karyawan)){{ $data->nm_status_karyawan }}@else{{ old('nm_status_karyawan') }}@endif">
		
		@if ($errors->has('nm_jenis'))
		<label style="color: red">
			{{ $errors->first('nm_jenis') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="nm_jenis">
			<b>Durasi (Dalam hitungan bulan)</b> <span class="span-required"> *</span>
		</label>
		
		<input type="number" id="durasi" name="durasi" maxlength="32" class="form-control" value="@if(isset($data->durasi)){{ $data->durasi }}@else{{ old('durasi') }}@endif">
		
		@if ($errors->has('nm_jenis'))
		<label style="color: red">
			{{ $errors->first('nm_jenis') }}
		</label>
		@endif
	</div>
	
	<div class="text-right">
		@include('template.inc_action')
	</div>
</form>
@endif

@endsection
@section('script')
@endsection