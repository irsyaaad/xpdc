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
				<th>Nama Jabatan </th>
				<th>Deskripsi</th>
				<th>
					<center>Action</center>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ strtoupper($value->nm_jabatan) }}</td>
				<td>{{ strtoupper($value->deskripsi) }}</td>
				<td>
					@if (get_admin())
					{!! inc_edit($value->id_jabatan) !!}
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create" ){{ url(Request::segment(1)) }}@else{{ route('jabatan.update', $data->id_jabatan) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit" )
	{{ method_field("PUT") }} 
	@endif
	
	@csrf
	<div class="form-group m-form__group">
		<label for="nm_jenis">
			<b>Nama Jabatan</b> <span class="span-required"> *</span>
		</label>
		
		<input type="text" id="nm_jabatan" name="nm_jabatan" maxlength="32" class="form-control" value="@if(isset($data->nm_jabatan)){{ $data->nm_jabatan }}@else{{ old('nm_jabatan') }}@endif">
		
		@if ($errors->has('nm_jabatan'))
		<label style="color: red">
			{{ $errors->first('nm_jabatan') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="nm_jenis">
			<b>Deskripsi</b> <span class="span-required"></span>
		</label>
		
		<input type="deskripsi" id="deskripsi" name="deskripsi" maxlength="32" placeholder="ex: Top Menegement" class="form-control" value="@if(isset($data->deskripsi)){{ $data->deskripsi }}@else{{ old('deskripsi') }}@endif">
		
		@if ($errors->has('deskripsi'))
		<label style="color: red">
			{{ $errors->first('deskripsi') }}
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