@extends('template.document')

@section('data')
<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	@csrf
	<div class="row mt-1">
		<div class="col-md-12">
			<table class="table table-responsive table-striped" id="html_table" width="100%">
				<thead  style="background-color: grey; color : #ffff">>
					<tr>
						<th>No</th>
						<th>Username</th>
						<th>Karyawan</th>
						<th>
							Email
							<br>
							No Telp
						</th>
						<th>Perusahaan Asal</th>
						<th>Kepala Cabang</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $value->username }}</td>
						<td>
							@if(isset($value->karyawan->nm_karyawan))
							{{ $value->karyawan->nm_karyawan }}
							@endif
						</td>
						<td>
							{{ $value->email }}
							<br>
							{{ $value->telp }}
						</td>
						<td>@if(isset($value->perusahaan->nm_perush)){{ strtoupper($value->perusahaan->nm_perush) }}@endif</td>
						<td>
							@if($value->is_kacab==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							{!! inc_edit($value->id_user) !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
	
</form>
@endsection

@section('script')
<script type="text/javascript">
	$("#f_id_perush").select2();
	$("#f_id_karyawan").select2();

	$("#shareselect").change(function(){
		$("#form-select").submit();
	});

	@if(isset($filter["f_id_karyawan"]))
	$("#f_id_karyawan").val('{{ $filter["f_id_karyawan"] }}').trigger("change");
	@endif

	@if(isset($filter["f_id_perush"]))
	$("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
	@endif

	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	$('#f_id_perush').on("change", function(e) {
		$('#f_id_karyawan').empty();
		$.ajax({
			type: "GET",
			url: "{{ url('getkaryawanuser') }}/"+$("#f_id_perush").val(),
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				$('#f_id_karyawan').append('<option value="">-- Pilih Karyawan --</option>');
				$.each(response, function(index, value) {
					$('#f_id_karyawan').append('<option value="'+value.id_karyawan+'">'+value.nm_karyawan+'</option>');
				});
				$("#f_id_karyawan").select2();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	});

</script>
@endsection
