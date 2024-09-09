@extends('template.document')

@section('data')
@if(Request::segment(1)=="role" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-responsive table-striped">
		<thead  style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>
					{{ $key+1 }}
				</td>
				<td>
					{{ strtoupper($value->nm_role) }}
				</td>
				<td>
					{!! inc_edit($value->id_role) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form method="POST" action="@if(Request::segment(2)=="create"){{ url('role') }}@else{{ route('role.update', $data->id_role) }}@endif" enctype="multipart/form-data">
	
	@if(Request::segment(3)=="edit" )
	{{ method_field("PUT") }}
	@endif

	@csrf
	<div class="form-group m-form__group">
		<label for="nm_role">
			<b>Nama Role</b>
		</label>
		<input type="text" class="form-control m-input m-input--square" name="nm_role" id="nm_role" placeholder="Masukan Nama role" value="@if(isset($data->nm_role)){{ $data->nm_role }}@else{{ old('nm_role') }}@endif" required="required" maxlength="50">
		
		@if ($errors->has('nm_role'))
		<label style="color: red">
			{{ $errors->first('nm_role') }}
		</label>
		@endif
	</div>
	@include('template.inc_action')
</form>
@endif
@endsection