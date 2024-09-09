@extends('template.document')

@section('data')

@if(Request::segment(1)=="carabayar" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-responsive table-striped">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Kode Cara Bayar</th>
				<th>Cara Bayar</th>
				<th>Is Aktif</th>
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
					{{ strtoupper($value->kode_cr_byr_o) }}
				</td>
				<td>
					{{ strtoupper($value->nm_cr_byr_o) }}
				</td>
				<td>
					@if($value->is_aktif==1)
					<i class="fa fa-check" style="color: green"></i>
					@else
					<i class="fa fa-times" style="color: red"></i>
					@endif
				</td>
				<td>
					{!! inc_edit($value->id_cr_byr_o) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('carabayar') }}@else{{ url('carabayar', $data->id_cr_byr_o) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
		
		<div class="form-group col-md-3">
			<label for="id_cr_byr_o">
				<b>Nama Cara Bayar</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="id_cr_byr_o" id="id_cr_byr_o" maxlength="4" value="@if(old('id_cr_byr_o')!=null){{ old('id_cr_byr_o') }}@elseif(isset($data->kode_cr_byr_o)){{$data->kode_cr_byr_o}}@endif" required="required" maxlength="64">
			
			@if ($errors->has('id_cr_byr_o'))
			<label style="color: red">
				{{ $errors->first('id_cr_byr_o') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-3">
			<label for="nm_cr_byr_o">
				<b>Nama Cara Bayar</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_cr_byr_o" id="nm_cr_byr_o" value="@if(old('nm_cr_byr_o')!=null){{ old('nm_cr_byr_o') }}@elseif(isset($data->nm_cr_byr_o)){{$data->nm_cr_byr_o}}@endif" required="required" maxlength="64">
			
			@if ($errors->has('nm_cr_byr_o'))
			<label style="color: red">
				{{ $errors->first('nm_cr_byr_o') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-1">
			<label for="is_aktif">
				<b>Is Aktif </b>
			</label>
			<br>
			<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
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
	@if(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
</script>
@endsection