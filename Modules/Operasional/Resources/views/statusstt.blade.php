@extends('template.document')

@section('data')

@if(Request::segment(1)=="statusstt" && Request::segment(2)==null)
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-responsive table-hover"  width="100%">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>Kode Status</th>
				<th>Nama Status</th>
				<th>Nama Status Transit</th>
				<th>Nama Status DM</th>
				<th>Is Aktif</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>
					{{ strtoupper($value->id_ord_stt_stat) }}
				</td>
				<td>
					{{ strtoupper($value->nm_ord_stt_stat) }}
				</td>
				<td>
					{{ strtoupper($value->nm_alias) }}
				</td>
				<td>
					@if(isset($value->dm->nm_status)){{ strtoupper($value->dm->nm_status) }}@endif
				</td>
				<td>
					@if($value->is_aktif==1)
					<i class="fa fa-check" style="color: green"></i>
					@else
					<i class="fa fa-times" style="color: red"></i>
					@endif
				</td>
				<td>
					{!! inc_edit($value->kode_status) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('statusstt') }}@else{{ url('statusstt', $data->kode_status) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	
	@csrf
	<div class="row">
		<div class="form-group col-md-3">
			<label for="id_ord_stt_stat">
				<b>Kode Status</b>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="id_ord_stt_stat" id="id_ord_stt_stat"   required="required" maxlength="3">
			
			@if ($errors->has('id_ord_stt_stat'))
			<label style="color: red">
				{{ $errors->first('id_ord_stt_stat') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-3">
			<label for="nm_ord_stt_stat">
				<b>Nama Status</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_ord_stt_stat" id="nm_ord_stt_stat" required="required" maxlength="100">
			
			@if ($errors->has('nm_ord_stt_stat'))
			<label style="color: red">
				{{ $errors->first('nm_ord_stt_stat') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-3">
			<label for="nm_alias">
				<b>Nama Status Transit</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_alias" id="nm_alias" maxlength="100">
			
			@if ($errors->has('nm_alias'))
			<label style="color: red">
				{{ $errors->first('nm_alias') }}
			</label>
			@endif
		</div>

		<div class="form-group col-md-3">
			<label for="id_status">
				<b>Status DM</b>
			</label>
			
			<select class="form-control m-input m-input--square" name="id_status" id="id_status">
				<option value="">-- Pilih Status DM --</option>
				@foreach($dm as $key => $value)
				<option value="{{ $value->id_status }}">{{ $value->nm_status }}</option>	
				@endforeach
			</select>
			
			@if ($errors->has('id_status'))
			<label style="color: red">
				{{ $errors->first('id_status') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group">
			<label for="is_aktif">
				<b>Is Aktif </b>
			</label>
			<br>
			<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
		</div>
		
		<div class="col-md-11 text-right">
			@include('template.inc_action')
		</div>
	</div>
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	@if(old("is_aktif")!=null and old("is_aktif")==1)
	$("#is_aktif").prop("checked", true);
	@elseif(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif

	@if(old("id_status")!=null)
	$("#id_status").val('{{ old("id_status") }}');
	@elseif(isset($data->id_status))
	$("#id_status").val('{{ $data->id_status }}');
	@endif

	@if(old("nm_alias")!=null)
	$("#nm_alias").val('{{ old("nm_alias") }}');
	@elseif(isset($data->nm_alias))
	$("#nm_alias").val('{{ $data->nm_alias }}');
	@endif

	@if(old("nm_ord_stt_stat")!=null)
	$("#nm_ord_stt_stat").val('{{ old("nm_ord_stt_stat") }}');
	@elseif(isset($data->nm_ord_stt_stat))
	$("#nm_ord_stt_stat").val('{{ $data->nm_ord_stt_stat }}');
	@endif

	@if(old("id_ord_stt_stat")!=null)
	$("#id_ord_stt_stat").val('{{ old("id_ord_stt_stat") }}');
	@elseif(isset($data->id_ord_stt_stat))
	$("#id_ord_stt_stat").val('{{ $data->id_ord_stt_stat }}');
	@endif

</script>
@endsection