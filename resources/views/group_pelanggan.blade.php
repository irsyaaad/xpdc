@extends('template.document')

@section('data')
@if(Request::segment(1)=="groupplgn" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-striped table-responsive">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Kode</th>
				<th>Nama</th>
				<th>Umum ?</th>
				<th>
					<center>Action</center>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ strtoupper($value->kode) }}</td>
				<td>{{ strtoupper($value->nm_group) }}</td>
				<td>
					@if($value->is_umum==1)
					<i class="fa fa-check" style="color: green"></i>
					@else
					<i class="fa fa-times" style="color: red"></i>
					@endif
				</td>
				<td>
					@if(get_admin())
					{!! inc_edit($value->id_plgn_group) !!}
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create" ){{ url('groupplgn') }} @else{{ route('groupplgn.update', $data->id_plgn_group) }}@endif " enctype="multipart/form-data">
	@if(Request::segment(3)=="edit" )	
	{{ method_field("PUT") }} 
	@endif
	@csrf
	
	<div class="row">
		
		<div class="form-group col-md-4">
			<label for="id_plgn_group">
				<b>Kode Group</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" id="id_plgn_group" name="id_plgn_group" maxlength="16" class="form-control" value="@if(old('id_plgn_group')!=null){{ old('id_plgn_group') }}@elseif(isset($data->kode_plgn_group)){{$data->kode_plgn_group}}@endif">
			
			@if ($errors->has('id_plgn_group'))
			<label style="color: red">
				{{ $errors->first('id_plgn_group') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-4">
			<label for="nm_group">
				<b>Nama Group</b> <span class="span-required"> *</span>
			</label>
			
			<input type="text" id="nm_group" name="nm_group" maxlength="64" class="form-control" value="@if(old('nm_group')!=null){{ old('nm_group') }}@elseif(isset($data->nm_group)){{$data->nm_group}}@endif">
			
			@if ($errors->has('nm_group'))
			<label style="color: red">
				{{ $errors->first('nm_group') }}
			</label>
			@endif
		</div>
		
		<div class="form-group col-md-1">
			<label for="akses">
				<b>Is Umum ?</b>
			</label>
			<br>
			<label><input type="checkbox" value="1" id="is_umum" name="is_umum"> Umum</label>
			
			@if ($errors->has('is_umum'))
			<label style="color: red">
				{{ $errors->first('is_umum') }}
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
	@if(isset($data->is_umum) and $data->is_umum==1)
	$("#is_umum").prop("checked", true);
	@endif
	
</script>
@endsection