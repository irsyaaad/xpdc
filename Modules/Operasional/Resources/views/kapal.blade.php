
@extends('template.document')

@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include("template.filter-collapse")
	<div class="row" style="font-weight: bold;">
		<div class="col-md-12">
			<table class="table table-striped table-responsive">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama Kapal</th>
						<th>Kapal Perusahaan</th>
						<th>Dari Rute</th>
						<th>Ke Rute</th>
						<th>Tarif</th>
						<th>Is Aktif</th>
						<th>Action</th>
					</tr>
				</thead>
				
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $value->nm_kapal }}</td>
						<td>@if(isset($value->nm_kapal_perush)){{ strtoupper($value->nm_kapal_perush) }}@endif</td>
						<td>{{ $value->dr_rute }}</td>
						<td>{{ $value->ke_rute }}</td>
						<td>{{ $value->def_tarif }}</td>
						<td>
							@if($value->is_aktif==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							{!! inc_edit($value->id_kapal) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('kapal') }}@else{{ url('kapal', $data->id_kapal) }} @endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
		<div class="col-md-4 form-group">
			<label for="nm_kapal">
				<b>Nama Kapal</b><span class="span-required"> *</span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_kapal" id="nm_kapal" value="@if(old('nm_kapal')!=null){{ old('nm_kapal') }}@elseif(isset($data->nm_kapal)){{$data->nm_kapal}}@endif" required="required" maxlength="128">
			
			@if ($errors->has('nm_kapal'))
			<label style="color: red">
				{{ $errors->first('nm_kapal') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-4 form-group">
			<label for="id_kapal_perush">
				<b>Kapal Perusahaan</b><span class="span-required"> *</span>
			</label>
			
			<select id="id_kapal_perush" name="id_kapal_perush" class="form-control m-input m-input--square"></select>
			
			@if ($errors->has('id_kapal_perush'))
			<label style="color: red">
				{{ $errors->first('id_kapal_perush') }}
			</label>
			@endif
		</div>

		<div class="col-md-4 form-group">
			<label for="def_tarif">
				<b>Definisi Tarif</b>
			</label>
			
			<input type="number" step="any" class="form-control m-input m-input--square" name="def_tarif" id="def_tarif" value="@if(old('def_tarif')!=null){{ old('def_tarif') }}@elseif(isset($data->def_tarif)){{$data->def_tarif}}@endif">
			
			@if ($errors->has('def_tarif'))
			<label style="color: red">
				{{ $errors->first('def_tarif') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-4 form-group">
			<label for="dr_rute">
				<b>Dari Rute</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="dr_rute" id="dr_rute" value="@if(old('dr_rute')!=null){{ old('dr_rute') }}@elseif(isset($data->dr_rute)){{$data->dr_rute}}@endif" maxlength="64">
			
			@if ($errors->has('dr_rute'))
			<label style="color: red">
				{{ $errors->first('dr_rute') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-4 form-group">
			<label for="ke_rute">
				<b>Dari Rute</b>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="ke_rute" id="ke_rute" value="@if(old('ke_rute')!=null){{ old('ke_rute') }}@elseif(isset($data->ke_rute)){{$data->ke_rute}}@endif" maxlength="64">
			
			@if ($errors->has('ke_rute'))
			<label style="color: red">
				{{ $errors->first('ke_rute') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-4 form-group">
			<label for="is_aktif">
				<b>Is Aktif </b>
			</label>
			
			<div class="row">
				<div class="col-md-2 checkbox">
					<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
				</div>
			</div>
		</div>
		<div class="col-md-10 text-right">
			@include('template.inc_action')
		</div>
	</div>
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	$("#f_id_perush").select2();
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	@if(isset($filter["f_id_perush"]))
	$("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
	@endif
	
	@if(isset($filter["id_kapal"]->nm_kapal))
	$("#f_id_kapal").empty();
	$("#f_id_kapal").append('<option value={{ $filter["id_kapal"]->id_kapal }}>'+"{{ strtoupper($filter["id_kapal"]->nm_kapal) }}"+'</option>');
	@endif
	
	$('#f_id_kapal').select2({
		placeholder: 'Cari Kapal ....',
		ajax: {
			url: '{{ url('getKapal') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_kapal').empty();
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
	
	$('#id_kapal_perush').select2({
		placeholder: 'Cari Kapal Perusahaan ....',
		ajax: {
			url: '{{ url('getKapalPerush') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_kapal_perush').empty();
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
	
	@if(isset($data->kapalperush->nm_kapal_perush))
	$("#id_kapal_perush").empty();
	$("#id_kapal_perush").append('<option value={{ $data->id_kapal_perush }}>'+"{{ strtoupper($data->kapalperush->nm_kapal_perush) }}"+'</option>');
	@endif
	
	@if(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
	
</script>
@endsection