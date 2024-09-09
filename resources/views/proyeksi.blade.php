@extends('template.document')

@section('data')
@if(Request::segment(1)=="proyeksi" && Request::segment(2)==null)

<table class="table table-responsive table-striped" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
			<th>No</th>
			<th>Nama</th>
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
				{{ strtoupper($value->nm_proyeksi) }}
			</td>
			<td>
				@if($value->is_aktif==1)
				<i class="fa fa-check" style="color: green"></i>
				@else
				<i class="fa fa-times" style="color: red"></i>
				@endif
			</td>
			<td>
				{!! inc_dropdown($value->id_proyeksi) !!}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<div class="row" style="margin-top: 4%; font-weight: bold;">
	<div class="col-md-3">
		Halaman : <b>{{ $data->currentPage() }}</b>
	</div>
	<div class="col-md-3">
		Jumlah Data : <b>{{ $data->total() }}</b>
	</div>
	<div class="col-md-6" style="width: 100%">
		{{ $data->links() }}
	</div>
</div>


@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
@if(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('proyeksi') }}" enctype="multipart/form-data">
@else
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ route('proyeksi.update', $data->id_proyeksi) }}" enctype="multipart/form-data">
	{{ method_field("PUT") }} 
@endif	
@csrf	
	<div class="form-group m-form__group">
		<label for="nm_proyeksi">
			<b>Nama proyeksi</b>
		</label>

		<input type="text" class="form-control m-input m-input--square" name="nm_proyeksi" id="nm_proyeksi" placeholder="Masukan Nama Proyeksi" value="@if(isset($data->nm_proyeksi)){{ $data->nm_proyeksi }}@else{{ old('nm_proyeksi') }}@endif" required="required" maxlength="64">
		
		@if ($errors->has('nm_proyeksi'))
		<label style="color: red">
			{{ $errors->first('nm_proyeksi') }}
		</label>
		@endif
	</div>

	<div class="form-group m-form__group col-md-6">
		<label for="is_aktif">
			<b>Is Aktif</b>
		</label>

		<div class="row">
			<div class="col-md-12 checkbox">
				<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
			</div>
		</div>

		@if ($errors->has('is_aktif'))
		<label style="color: red">
			{{ $errors->first('is_aktif') }}
		</label>
		@endif
	</div>

	@include('template.inc_action')
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	@if(Request::segment(3)=="edit" and isset($data->is_aktif) and $data->is_aktif==1)
		$("#is_aktif").prop("checked", true);
	@endif
</script>
@endsection