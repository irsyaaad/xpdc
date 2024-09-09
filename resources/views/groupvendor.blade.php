@extends('template.document')

@section('data')
@if(Request::segment(1)=="groupvendor" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-responsive table-striped">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Group Vendor</th>
				<th>Is Aktif</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>
					{{ $key+1 }}
				</td>
				<td>
					{{ strtoupper($value->nm_grup_ven) }}
				</td>
				<td>
					@if($value->is_aktif==1)
					<i class="fa fa-check" style="color: green"></i>
					@else
					<i class="fa fa-times" style="color: red"></i>
					@endif
				</td>
				<td class="text-center">
					{!! inc_edit($value->id_grup_ven) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('groupvendor') }}@else{{ url('groupvendor', $data->id_grup_ven) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
		
		<div class="form-group col-md-4">
			<label for="id_grup_ven">
				<b>Id Group Vendor</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="id_grup_ven" maxlength="5" id="id_grup_ven" value="@if(old('id_grup_ven')!=null){{ old('id_grup_ven') }}@elseif(isset($data->id_grup_ven)){{$data->id_grup_ven}}@endif" required="required" maxlength="32">
			
			@if ($errors->has('id_grup_ven'))
			<label style="color: red">
				{{ $errors->first('id_grup_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="nm_grup_ven">
				<b>Nama Group Vendor</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_grup_ven" maxlength="64" id="nm_grup_ven" value="@if(old('nm_grup_ven')!=null){{ old('nm_grup_ven') }}@elseif(isset($data->nm_grup_ven)){{$data->nm_grup_ven}}@endif" required="required" maxlength="32">
			
			@if ($errors->has('nm_grup_ven'))
			<label style="color: red">
				{{ $errors->first('nm_grup_ven') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-1">
			<label for="is_aktif">
				<b>Is Aktif</b>
			</label>
			<br>

			<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
			@if ($errors->has('is_aktif'))
			<label style="color: red">
				{{ $errors->first('is_aktif') }}
			</label>
			@endif
		</div>
		<div class="col-md-3">
			@include('template.inc_action')
		</div>
	</div>
</form>
@endif

@endsection

{{-- this for loading javascript data --}}
@section('script')
<script type="text/javascript">
	@if(Request::segment(3)=="edit" and isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
	
</script>
@endsection