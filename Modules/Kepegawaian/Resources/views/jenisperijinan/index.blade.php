@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-hover table-responsive">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Kode </th>
				<th>Jenis Perijinan</th>
				<th>Format</th>
				<th>Admin</th>
				<th>
					<center>Action</center>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ strtoupper($value->id_jenis) }}</td>
				<td>{{ strtoupper($value->nm_jenis) }}</td>
				<td>
					@if($value->format==1)
					Perjam
					@else
					Perhari
					@endif
				</td>
				<td>
					@if(isset($value->user->nm_user))
					{{ strtoupper($value->user->nm_user) }}
					@endif
				</td>
				<td>
					{!! inc_edit($value->id_jenis) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create" ){{ url(Request::segment(1)) }}@else{{ route('jenisperijinan.update', $data->id_jenis) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit" )
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="form-group m-form__group">
		<label for="id_jenis">
			<b>Kode Perijinan</b> <span class="span-required"> *</span>
		</label>
		
		<input type="text"  id="id_jenis" name="id_jenis" maxlength="4" class="form-control" value="@if(isset($data->id_jenis)){{ $data->id_jenis }}@else{{ old('id_jenis') }}@endif">
		
		@if ($errors->has('id_jenis'))
		<label style="color: red">
			{{ $errors->first('id_jenis') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="nm_jenis">
			<b>Nama Perijinan</b> <span class="span-required"> *</span>
		</label>
		
		<input type="text" id="nm_jenis" name="nm_jenis" maxlength="32" class="form-control" value="@if(isset($data->nm_jenis)){{ $data->nm_jenis }}@else{{ old('nm_jenis') }}@endif">
		
		@if ($errors->has('nm_jenis'))
		<label style="color: red">
			{{ $errors->first('nm_jenis') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="format">
			<b>Format Jenis</b> <span class="span-required"> *</span>
		</label>
		
		<select id="format" name="format" maxlength="1" class="form-control" >
			<option>-- Format Jenis Izin --</option>
			<option value="1">Per jam</option>
			<option value="2">Per hari</option>
		</select>
		
		@if ($errors->has('format'))
		<label style="color: red">
			{{ $errors->first('format') }}
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
<script>
	@if(isset($data->format))
	$("#format").val('{{ $data->format }}');
	@endif
	
	@if(isset($data->id_jenis))
	$("#id_jenis").attr("readonly", true);
	@endif
</script>
@endsection