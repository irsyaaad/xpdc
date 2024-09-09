@extends('template.document')

@section('data')

@if(Request::segment(1)=="authborongan" and Request::segment(2)==null)

<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
	@csrf
	<input type="hidden" name="_method" value="GET">
	<table class="table table-responsive table-stripped">
		<thead  style="background-color: grey; color : #ffff">
			<tr>
				<th>No. </th>
				<th>Perusahaan </th>
				<th>Karyawan </th>
				<th>Encript Code </th>
				<th>Admin</th>
				<th>Action </th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ ($key+1) }}</td>
				<td>{{ $value->nm_perush }}</td>
				<td>{{ $value->nm_user }}</td>
				<td>{{ $value->auth_kode }}</td>
				<td>{{ $value->nm_user }}</td>
				<td>
					<a href="{{ url(Request::segment(1)."/".$value->id_auth."/show") }}" class="btn btn-sm btn-info"><i class="fa fa-eye"> </i> Show Code</a>
					<button class="btn btn-sm btn-danger" id = "hapus" type="button" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_auth) }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
						<i class="fa fa-times"></i> Hapus
					</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</form>

@elseif(Request::segment(2)=="create" )
<form id="form-save" action="{{ url(Request::segment(1)) }}" method="POST" enctype="multipart/form-data">
	@csrf
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="id_perush">
					<b>Perusahaan Asal</b> <span class="text-danger">*</span>
				</label>
				
				<select class="form-control m-input m-input--square" id="id_perush" name="id_perush" required>
					@foreach($perusahaan as $key => $value)
					<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
					@endforeach
				</select>
				
				@if ($errors->has('id_perush'))
				<label style="color: red">
					{{ $errors->first('id_perush') }}
				</label>
				@endif
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group">
				<label for="id_user">
					<b>Karyawan</b> <span class="text-danger">*</span>
				</label>
				
				<select class="form-control m-input m-input--square" id="id_user" name="id_user" required>
					@foreach($karyawan as $key => $value)
					<option value="{{ $value->id_user }}">{{ $value->nm_user }}</option>
					@endforeach
				</select>
				
				@if ($errors->has('id_user'))
				<label style="color: red">
					{{ $errors->first('id_user') }}
				</label>
				@endif
			</div>
			<input type="hidden" id="secret" name="secret" required value="{{ $secret }}" />
		</div>
		
		<div class="col-md-4" style="padding-top: 30px">
			<button class="btn btn-md btn-primary" type="submit"><i class="fa fa-save"> </i> Simpan</button>
			<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-danger"><i class="fa fa-times"> </i> Batal </a>
		</div>
		
	</div>
</form>

@elseif(Request::segment(3)=="show")
<div class="row">
	<div class="col-md-4">
	</div>
	<div class="col-md-4">
		<div class="text-right">
			<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning"><i class="fa fa-back"> </i>Kembali </a>
		</div>
		<div class="form-group">
			<label>Qr Code <span class="text-danger"> * </span> </label>
			<br>
			<img src="{{ $qrcode }}" id="qrcode" name="qrcode" style="width: 100%">
			
			<input type="text" class="form-control m-input m-input--square" id="request_code" name="request_code" readonly="true" required="required" value="{{ $secret }}" style="text-align: center; margin-top: 3%">
		</div>
	</div>
	<div class="col-md-4">
	</div>
</div>
@endif

@endsection

@section("script")
<script type="text/javascript">
	
	@if(isset($id_perush))
	$("#id_perush").val("{{ $id_perush }}");
	@endif
	
	@if(isset($id_user))
	$("#id_user").val("{{ $id_user }}");
	@endif
	
	$('#id_perush').on("change", function(e) {
		$('#id_user').empty();
		$.ajax({
			type: "GET",
			url: "{{ url('getRoleUser') }}/"+$("#id_perush").val(),
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				$.each(response, function(index, value) {
					$('#id_user').append('<option value="'+value.id_user+'">'+value.nm_user+'</option>');
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	});
	
</script>
@endsection