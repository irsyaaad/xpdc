@extends('template.document')

@section('data')

@if(Request::segment(1)=="mesinfinger" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-striped table-responsive" width="100%">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Perusahaan </th>
				<th>Nama Mesin</th>
				<th>Authorization</th>
				<th>Cloud ID</th>
				<th>
					<center>Action</center>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>
					@if(isset($value->perusahaan->nm_perush))
					{{ $value->perusahaan->nm_perush }}
					@endif
				</td>
				<td>
					{{ $value->nm_mesin }}
				</td>
				<td>
					{{ $value->authorization }}
				</td>
				<td>
					{{ $value->cloud_id }}
				</td>
				<td>
					{!! inc_edit($value->id_mesin) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create" ){{ url(Request::segment(1)) }}@else{{ route('mesinfinger.update', $data->id_mesin) }}@endif" enctype="multipart/form-data">
	
	@if(Request::segment(3)=="edit" )
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
		<div class="form-group m-form__group col-md-3" style="margin-top: 1%">
			<label for="id_perush">
				<b>Perusahaan</b> <span class="span-required"> *</span>
			</label>
			
			<select id="id_perush" name="id_perush" class="form-control" required>
				<option>-- Pilih --</option>
				@foreach($perush as $key => $value)
				<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
				@endforeach
			</select>
			
			@if ($errors->has('id_perush'))
			<label style="color: red">
				{{ $errors->first('id_perush') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-3">
			<label for="nm_mesin">
				<b>Nama Mesin</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text"  id="nm_mesin" name="nm_mesin" maxlength="100" required class="form-control" value="@if(isset($data->nm_mesin)){{ $data->nm_mesin }}@else{{ old('nm_mesin') }}@endif">
			
			@if ($errors->has('nm_mesin'))
			<label style="color: red">
				{{ $errors->first('nm_mesin') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-3">
			<label for="authorization">
				<b>Authorization</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text"  id="authorization" name="authorization" maxlength="100" required class="form-control" value="@if(isset($data->authorization)){{ $data->authorization }}@else{{ old('authorization') }}@endif">
			
			@if ($errors->has('authorization'))
			<label style="color: red">
				{{ $errors->first('authorization') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group col-md-3">
			<label for="cloud_id">
				<b>Cloud ID</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text"  id="cloud_id" name="cloud_id" maxlength="100" class="form-control" required value="@if(isset($data->cloud_id)){{ $data->cloud_id }}@else{{ old('cloud_id') }}@endif">
			
			@if ($errors->has('cloud_id'))
			<label style="color: red">
				{{ $errors->first('cloud_id') }}
			</label>
			@endif
		</div>
		
		<div class=" col-md-12 text-right">
			@include('template.inc_action')
		</div>
	</div>
	
</form>
@endif
@endsection

@section('script')
<script>
	@if(isset($data->id_perush))
	$("#id_perush").val("{{ $data->id_perush }}");
	@endif
</script>
@endsection