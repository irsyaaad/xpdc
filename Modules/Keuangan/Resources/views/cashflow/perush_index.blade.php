
@extends('template.document2')

@section('data')

@if(Request::segment(1)=="mastercashflowperush" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
@include("template.filter2")

<table class="table table-sm" id="html_table" width="100%">
	<thead style="background-color: grey; color : #ffff">
		<tr>
			<th>No</th>
			<th>Nama Cashflow</th>
			<th>Nama AC</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($cashflow as $key => $value)
			<tr>
				<td>{{$key+1}}</td>
				<td colspan="2">{{$value->nama_cashflow}}</td>
				<td>
					<a href="{{ url("mastercashflowperush").'/'.$value->id_cf.'/edit' }}" class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip" data-placement="bottom" title="Edit">
						<i class="fa fa-pencil"></i>
					</a>
				</td>
				@if (isset($data[$value->id_cf]))
					@foreach ($data[$value->id_cf] as $key2 => $value2)
						<tr>
							<td></td>
							<td></td>
							<td>@if (isset($value2->akun->nama))
								{{$value2->akun->nama}}
							@endif</td>
						</tr>
					@endforeach
				@endif
			</tr>
		@endforeach
	</tbody>
</table>


@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")

@if(Request::segment(2)=="create")
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data">
@else
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url(Request::segment(1), $data->id_biaya_grup) }}" enctype="multipart/form-data">
		{{ method_field("PUT") }} 
@endif

	@csrf

    <div class="form-group m-form__group">
		<label for="klp">
			<b>Tipe CashFlow</b><span class="span-required"> *</span>
		</label>

		<select class="form-control m-input m-input--square" name="tipe" id="tipe" required="required">
			<option> -- Pilih Tipe Cashflow --</option>
			<option value="1">CASH IN</option>
			<option value="2">CASH OUT</option>
		</select>
		
		@if ($errors->has('tipe'))
		<label style="color: red">
			{{ $errors->first('tipe') }}
		</label>
		@endif
	</div>

	<div class="form-group m-form__group">
		<label for="klp">
			<b>Level</b><span class="span-required"> *</span>
		</label>

		<select class="form-control m-input m-input--square" name="level" id="level" required="required">
			<option> -- Pilih level Cashflow --</option>
			<option value="1">1</option>
			<option value="2">2</option>
		</select>
		
		@if ($errors->has('tipe'))
		<label style="color: red">
			{{ $errors->first('tipe') }}
		</label>
		@endif
	</div>

	<div class="form-group m-form__group">
		<label for="nm_biaya_grup">
			<b>Nama Cashflow</b><span class="span-required"> *</span>
		</label>

		<input type="text" class="form-control m-input m-input--square" name="nama_cashflow" id="nama_cashflow" value="@if(isset($data->nama_cashflow)){{$data->nama_cashflow}}@else{{ old('nama_cashflow') }}@endif" required="required" maxlength="128">
		
		@if ($errors->has('nm_biaya_grup'))
		<label style="color: red">
			{{ $errors->first('nm_biaya_grup') }}
		</label>
		@endif
	</div>

	<div class="form-group m-form__group">
		<label for="klp">
			<b>Kelompok</b><span class="span-required"> *</span>
		</label>
		
		<select class="form-control m-input m-input--square" name="head" id="head" required="required">
			<option value="0"> -- Pilih Head --</option>
			@foreach ($kelompok as $key => $value)
				<option value="{{$value->id_cf}}">{{strtoupper($value->nama_cashflow)}}</option>
			@endforeach
		</select>
		
		@if ($errors->has('head'))
		<label style="color: red">
			{{ $errors->first('head') }}
		</label>
		@endif
	</div>

	@include('template.inc_action')
</form>
@endif

@endsection

@section('script')
<script type="text/javascript">
			$("#shareselect").on("change", function(e) {
				$("#form-share").submit();
			});
			@if(isset($page))
				$("#shareselect").val({{ $page }});
			@endif
	@if(Request::segment(3)=="edit" and isset($data->head))
		$("#head").val('{{ $data->head }}');
	@endif

	@if(isset($filter) and $filter!=null)
			$("#filter").val('{{ $req }}');
	@endif
</script>
@endsection