@extends('template.document')
@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include("template.filter-collapse")
	<div class="row" style="font-weight: bold;">
		<div class="col-md-12">
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Kode</th>
						<th>Tipe Kirim</th>
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
							{{ strtoupper($value->kode_tipe_kirim) }}
						</td>
						<td>
							{{ strtoupper($value->nm_tipe_kirim) }}
						</td>
						<td>
							@if($value->is_aktif==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							{!! inc_edit($value->id_tipe_kirim) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include("template.paginate")
	</div>
</form>
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create") {{ url('tipekirim') }} @else{{ url('tipekirim', $data->id_tipe_kirim) }}@endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="form-group m-form__group">
		<label for="nm_tipe_kirim">
			<b>Nama Tipe Kirim</b>
		</label>
		<input type="text" class="form-control m-input m-input--square" name="nm_tipe_kirim" id="nm_tipe_kirim" value="@if(old('nm_tipe_kirim')!=null){{ old('nm_tipe_kirim') }}@elseif(isset($data->nm_tipe_kirim)){{$data->nm_tipe_kirim}}@endif" required="required" maxlength="64">
		@if ($errors->has('nm_tipe_kirim'))
		<label style="color: red">
			{{ $errors->first('nm_tipe_kirim') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="is_aktif">
			<b>Is Aktif </b>
		</label>
		<div class="row">
			<div class="col-md-2 checkbox">
				<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
			</div>
		</div>
	</div>
	
	<div class="text-right">
		@include('template.inc_action')
	</div>
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	
	$('#f_id_tipe_kirim').select2({
		placeholder: 'Cari Tipe Kirim ....',
		ajax: {
			url: '{{ url("getTipeKirim") }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_tipe_kirim').empty();
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.value,
							id: item.kode
						}
					})
				};
			},
			cache: true
		}
	});
	
	@if(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
	
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

	@if(isset($filter["id_tipe_kirim"]->id_tipe_kirim))
	$("#f_id_tipe_kirim").empty();
	$("#f_id_tipe_kirim").append('<option value="{{ $filter["id_tipe_kirim"]->id_tipe_kirim }}">{{ strtoupper($filter["id_tipe_kirim"]->nm_tipe_kirim) }}</option>');
	@endif

</script>
@endsection