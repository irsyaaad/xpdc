
@extends('template.document')
@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include("template.filter-collapse")
	<div class="row" style="font-weight: bold;">
		<div class="col-md-12">
			<table class="table table-hover table-responsive">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama Kapal</th>
						<th>Alamat</th>
						<th>Telp</th>
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
							{{ strtoupper($value->nm_kapal_perush) }}
						</td>
						<td>
							{{ $value->alamat }}
						</td>
						<td>
							{{ $value->telp }}
						</td>
						<td>
							{!! inc_edit($value->id_kapal_perush) !!}
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
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('kapalperush') }} @else{{ url('kapalperush', $data->id_kapal_perush) }}@endif" enctype="multipart/form-data">
	
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif

	@csrf
	
	<div class="form-group ">
		<label for="nm_kapal_perush">
			<b>Nama Perusahaan Kapal</b><span class="span-required"> *</span>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="nm_kapal_perush" id="nm_kapal_perush" value="@if(old('nm_kapal_perush')!=null){{ old('nm_kapal_perush') }}@elseif(isset($data->nm_kapal_perush)){{$data->nm_kapal_perush}}@endif" required="required" maxlength="64">
		
		@if ($errors->has('nm_kapal_perush'))
		<label style="color: red">
			{{ $errors->first('nm_kapal_perush') }}
		</label>
		@endif
	</div>
	
	<div class="form-group ">
		<label for="alamat">
			<b>Alamat</b><span class="span-required"> *</span>
		</label>
		<textarea class="form-control m-input m-input--square" name="alamat" id="alamat" maxlength="128" required="required">@if(old('alamat')!=null){{ old('alamat') }}@elseif(isset($data->alamat)){{$data->alamat}}@endif</textarea>
		@if ($errors->has('alamat'))
		<label style="color: red">
			{{ $errors->first('alamat') }}
		</label>
		@endif
	</div>
	
	<div class="form-group">
		<label for="telp">
			<b>Telp</b><span class="span-required"> *</span>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="telp" id="telp" value="@if(old('telp')!=null){{ old('telp') }}@elseif(isset($data->telp)){{$data->telp}}@endif" required="required" maxlength="16">
		
		@if ($errors->has('telp'))
		<label style="color: red">
			{{ $errors->first('telp') }}
		</label>
		@endif
	</div>
	
	@include('template.inc_action')
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	
	$('#f_id_kapal_perush').select2({
		placeholder: 'Cari Perusahaan Kapal ....',
		ajax: {
			url: '{{ url("getKapalPerush") }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_kapal_perush').empty();
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
	
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

	@if(isset($filter["id_kapal_perush"]->id_kapal_perush))
	$("#f_id_kapal_perush").empty();
	$("#f_id_kapal_perush").append('<option value="{{ $filter["id_kapal_perush"]->id_kapal_perush }}">{{ strtoupper($filter["id_kapal_perush"]->nm_kapal_perush) }}</option>');
	@endif
	
</script>
@endsection