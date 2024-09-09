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
						<th>Kode Packing</th>
						<th>Packing</th>
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
							{{ strtoupper($value->kode_packing) }}
						</td>
						<td>
							{{ strtoupper($value->nm_packing) }}
						</td>
						<td class="text-center">
							{!! inc_edit($value->id_packing) !!}
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

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create")@else{{ url('packing', $data->id_packing) }} @endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")	
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
	<div class="form-group col-md-6">
		<label for="id_packing">
			<b>Kode Packing</b>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="id_packing" id="id_packing" value="@if(old('id_packing')!=null){{ old('id_packing') }}@elseif(isset($data->kode_packing)){{$data->kode_packing}}@endif" required="required" maxlength="64">
		
		@if ($errors->has('id_packing'))
		<label style="color: red">
			{{ $errors->first('id_packing') }}
		</label>
		@endif
	</div>
	
	<div class="form-group col-md-6">
		<label for="nm_packing">
			<b>Nama Packing</b>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="nm_packing" id="nm_packing" value="@if(old('nm_packing')!=null){{ old('nm_packing') }}@elseif(isset($data->nm_packing)){{$data->nm_packing}}@endif" required="required" maxlength="64">
		
		@if ($errors->has('nm_packing'))
		<label style="color: red">
			{{ $errors->first('nm_packing') }}
		</label>
		@endif
	</div>

	<div class="col-md-12 text-right">
		@include('template.inc_action')
	</div>
	</div>
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	
	$('#f_id_packing').select2({
		placeholder: 'Cari Packing ....',
		ajax: {
			url: '{{ url("getPacking") }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#f_id_packing').empty();
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
	
	@if(isset($filter["id_packing"]->id_packing))
	$("#f_id_packing").empty();
	$("#f_id_packing").append('<option value="{{ $filter["id_packing"]->id_packing }}">{{ strtoupper($filter["id_packing"]->nm_packing) }}</option>');
	@endif
	
</script>
@endsection