@extends('template.document2')

@section('data')

@if(Request::segment(1)=="statusinvoice" && Request::segment(2)==null)
<div class="row text-right">
	@if(get_admin())
	<div class="col-md-12">
		<a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-md btn-primary"><i class="fa fa-plus"> </i> Tambah Status</a>
	</div>
	@endif
	<div class="col-md-12">
		<table class="table table-striped table-responsive" width="100%" style="margin-top: 10px">
			<thead style="background-color: grey; color : #ffff">
				<tr>
					<th>Kode Status</th>
					<th>Nama Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $key => $value)
				<tr>
					<td class="text-center">{{ $value->id_status }}</td>
					<td class="text-center">{{ strtoupper($value->nm_status) }}</td>
					<td class="text-center">@if(get_admin()){!! inc_edit($value->id_status) !!}@endif</td>
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
	</div>
</div>

@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

{{-- for insert data or edit --}}
<style type="text/css">
	textarea{
		min-height: 100px;
	}
</style>

@if(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('statusinvoice') }}" enctype="multipart/form-data">
	@else
	<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('statusinvoice', $data->id_status) }}" enctype="multipart/form-data">
		{{ method_field("PUT") }} 
		@endif
		@csrf
		
		<div class="form-group m-form__group">
			<label for="id_status">
				<b>Kode Status</b> <span class="span-required"> * </span>
			</label>
			
			<input type="number" class="form-control m-input m-input--square" name="id_status" id="id_status" value="@if(isset($data->id_status)){{$data->id_status}}@else{{ old('id_status') }}@endif" required="required" maxlength="3">
			
			@if ($errors->has('id_status'))
			<label style="color: red">
				{{ $errors->first('id_status') }}
			</label>
			@endif
		</div>
		
		<div class="form-group m-form__group">
			<label for="nm_status">
				<b>Nama Status</b> <span class="span-required"> * </span>
			</label>
			
			<input type="text" class="form-control m-input m-input--square" name="nm_status" id="nm_status" value="@if(isset($data->nm_status)){{$data->nm_status}}@else{{ old('nm_status') }}@endif" required="required" maxlength="32">
			
			@if ($errors->has('nm_status'))
			<label style="color: red">
				{{ $errors->first('nm_status') }}
			</label>
			@endif
		</div>
		
		@include('template.inc_action')
		
	</form>
	@endif
	
	@endsection
	
	{{-- this for loading javascript data --}}
	@section('script')
	<script type="text/javascript">
		@if(isset($data->tipe))
		$("#tipe").val("{{$data->tipe}}");
		@else
		$("#tipe").val("{{ old('tipe') }}");
		@endif
	</script>
	@endsection