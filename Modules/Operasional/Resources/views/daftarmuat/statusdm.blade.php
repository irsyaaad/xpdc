@extends('template.document')

@section('data')

@if(Request::segment(1)=="statusdm" && Request::segment(2)==null)
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-striped table-hover">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>Kode Status</th>
				<th>Nama Status</th>
				<th>Jenis Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>
					{{ strtoupper($value->id_status) }}
				</td>
				<td>
					{{ strtoupper($value->nm_status) }}
				</td>
				<td>
					@if($value->tipe=="1")
					DM
					@else
					Handling
					@endif
				</td>
				<td>
					{!! inc_edit($value->id_status) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('statusdm') }}@else{{ url('statusdm', $data->id_status) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
		<div class="form-group col-md-3">
			<label for="id_status">
				<b>Kode Status</b> <span class="span-required"> * </span>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="id_status" id="id_status" value="@if(isset($data->id_status)){{$data->id_status}}@else{{ old('id_status') }}@endif" required="required" maxlength="3">
			
			@if ($errors->has('id_status'))
			<label style="color: red">
				{{ $errors->first('id_status') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-3">
			<label for="nm_status">
				<b>Nama Status</b> <span class="span-required"> * </span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_status" id="nm_status" value="@if(isset($data->nm_status)){{$data->nm_status}}@else{{ old('nm_status') }}@endif" required="required" maxlength="32">
			
			@if ($errors->has('nm_status'))
			<label style="color: red">
				{{ $errors->first('nm_status') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-3">
			<label for="tipe">
				<b>Tipe Status</b> <span class="span-required"> * </span>
			</label>
			
			<select  class="form-control m-input m-input--square" name="tipe" id="tipe">
				<option value="1">DM</option>
				<option value="2">Handling</option>
			</select>
			
			@if ($errors->has('tipe'))
			<label style="color: red">
				{{ $errors->first('tipe') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-3 text-left">
			@include('template.inc_action')
		</div>
	</div>
	
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	@if(isset($data->tipe))
	$("#tipe").val("{{$data->tipe}}");
	@else
	$("#tipe").val("{{ old('tipe') }}");
	@endif
</script>
@endsection