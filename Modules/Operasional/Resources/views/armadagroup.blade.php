@extends('template.document')

@section('data')

@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@csrf
	@include('template.filter-collapse')
	<div class="row mt-1">
		<div class="col-md-12"  style="overflow-x:auto;">
			<table class="table table-responsive table-hover" width="100%">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Armada Group</th>
						<th>Group Armada</th>
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
							{{ strtoupper($value->nm_armd_grup) }}
						</td>
						<td>
							@if($value->gr_armada==1)
							Darat
							@elseif($value->gr_armada==2)
							Laut
							@else
							Udara
							@endif
						</td>
						<td>
							@if($value->is_aktif==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							@if(get_admin())
							{!! inc_edit($value->id_armd_grup) !!}
							@endif
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
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('armadagroup') }} @else {{ url('armadagroup', $data->id_armd_grup) }} @endif" enctype="multipart/form-data">
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	@csrf
	<div class="row">
		<div class="col-md-4 form-group">
			<label for="nm_armd_grup">
				<b>Nama Armada</b><span class="span-required"> *</span>
			</label>
			
			<input type="text" class="form-control" name="nm_armd_grup" id="nm_armd_grup" value="@if(old('nm_armd_grup')!=null){{ old('nm_armd_grup') }}@elseif(isset($data->nm_armd_grup)){{$data->nm_armd_grup}}@endif" required="required" maxlength="128">
			
			@if ($errors->has('nm_armd_grup'))
			<label style="color: red">
				{{ $errors->first('nm_armd_grup') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-4 form-group">
			<label for="gr_armada">
				<b>Group Armada</b><span class="span-required"> *</span>
			</label>
			
			<select class="form-control" id="gr_armada" name="gr_armada" required="required">
				@foreach($group as $key => $value)
				<option value="{{ $key }}">{{ strtoupper($value) }}</option>
				@endforeach
			</select>
			
			@if ($errors->has('gr_armada'))
			<label style="color: red">
				{{ $errors->first('gr_armada') }}
			</label>
			@endif
		</div>
		
		<div class="col-md-1">
			<br><br>
			<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
		</div>
		
		<div class="col-md-3">
			@include('template.inc_action')
		</div>
	</div>
	
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
	$("#shareselect").on("change", function(e) {
		$("#form-share").submit();
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	@if(isset($filter["f_group"]))
	$("#f_group").val('{{ $filter["f_group"] }}');
	@endif
	
	@if(isset($data->is_aktif) and $data->is_aktif==1)
	$("#is_aktif").prop("checked", true);
	@endif
	
	@if(old("gr_armada")!=null)
	$("#gr_armada").val('{{ old("gr_armada") }}');
	@elseif(isset($data->gr_armada))
	$("#gr_armada").val('{{ $data->gr_armada }}');
	@endif

</script>
@endsection