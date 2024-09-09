@extends('template.document')

@section('data')
@if(Request::segment(1)=="module" && Request::segment(2)==null)
<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-responsive table-striped">
		<thead  style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Color Base</th>
				<th>Icon</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $value->nm_module }}</td>
				<td>{{ $value->color }}</td>
				<td>
					<i class="{{ $value->icon }}" style="font-size: 18pt"></i>
				</td>
				<td>
					{!! inc_edit($value->id_module) !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit" )

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('module') }}@else{{ route('module.update', $data->id_module) }}@endif" enctype="multipart/form-data">
	@csrf
	
	@if(Request::segment(3)=="edit")
	{{ method_field("PUT") }} 
	@endif
	
	<div class="form-group m-form__group">
		<label for="nm_module">
			<b>Nama Module</b>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="nm_module" id="nm_module" placeholder="Masukan Nama Module" value="@if(isset($data->nm_module)){{ $data->nm_module }}@else{{ old('nm_module') }}@endif" required="required" maxlength="30">
		
		@if ($errors->has('nm_module'))
		<label style="color: red">
			{{ $errors->first('nm_module') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="icon">
			<b>Icon Module</b>
		</label>
		
		<div class="row">
			<div class="col-md-11">
				<input type="text" class="form-control m-input m-input--square" name="icon" id="icon" placeholder="Pilih Icon Module" value="@if(isset($data->icon)){{ $data->icon }}@else{{ old('icon') }}@endif" required="required">
			</div>
			<div class="col-md-1">
				<button class="btn btn-md btn-warning" type="button" data-toggle="modal" data-target="#Modal-font">
					<i class="fa fa-edit"></i>
				</button>
			</div>
		</div>	
		
		@if ($errors->has('icon'))
		<label style="color: red">
			{{ $errors->first('icon') }}
		</label>
		@endif
	</div>	
	
	<div class="form-group m-form__group">
		<label for="color">
			<b>Color Base</b>
		</label>
		
		<input type="color" name="color" id="color" placeholder="Masukan Color Module" value="@if(isset($data->color)){{ $data->color }}@else{{ old('color') }}@endif" required="required">
		
		@if ($errors->has('color'))
		<label style="color: red">
			{{ $errors->first('color') }}
		</label>
		@endif
	</div>
	
	@include('template.inc_action')
	
</form>
@endif

<div class="modal fade" id="Modal-font" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Pilih Icon</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('inc.font-awesome')
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-primary">Pilih</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function() {
		@if(Request::segment(3)=="edit" or Request::segment(2)=="create")
		$(".baru").click(function(){
			var icon = $(this).attr("class");
			var icon = icon.replace("baru ", "");
			$("#icon").val(icon);
			$('#Modal-font').modal('toggle');
		});
		@endif
	});
</script>
@endsection