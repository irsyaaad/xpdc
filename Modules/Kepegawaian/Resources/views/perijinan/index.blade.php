@extends('template.document2')

@section('data')
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('kepegawaian::filter.filter-collapse')
	@csrf
	<div class="row mt-1">
		<div class="col-md-12" >
			<table class="table table-hover table-responsive" >
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>Nama Karyawan </th>
						<th>Jenis Perijinan</th>
						<th>Dari Tanggal</th>
						<th>Sampai Tanggal</th>
						<th>Lama Ijin</th>
						<th>Tgl Pengajuan</th>
						<th>Konfirmasi</th>
						<th>Approval Atasan</th>
						<th>
							<center>Action</center>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							<a href="#" onclick="goDetail('{{ $value->id_perijinan }}')" style="text-decoration: none">@if(isset($value->karyawan->nm_karyawan)){{ strtoupper($value->karyawan->nm_karyawan) }}@endif</a>
						</td>
						<td>@if(isset($value->id_jenis)){{ strtoupper($value->ijin->nm_jenis) }}@endif</td>
						<td>@if(isset($value->created_at)){{ daydate($value->dr_tgl).", ".dateindo($value->dr_tgl) }}@endif</td>
						<td>@if(isset($value->sp_tgl)){{ daydate($value->sp_tgl).", ".dateindo($value->sp_tgl) }}@endif</td>
						<td>{{$value->jumlah}}</td>
						<td>@if(isset($value->created_at)){{ daydate($value->created_at).", ".dateindo($value->created_at) }}@endif</td>
						<td>
							@if($value->is_konfirmasi)
							<label class="badge badge-md badge-success">
								<i class="fa fa-check"></i>
							</label>
							@else
							<label class="badge badge-md badge-danger">
								<i class="fa fa-times"></i>
							</label>
							@endif
						</td>
						<td>
							@if($value->approval == "1")
							<label class="badge badge-md badge-success">
								Diterima 
							</label>
							@elseif($value->approval == "0")
							<label class="badge badge-md badge-danger">
								Ditolak
							</label>
							@elseif($value->approval == null)
							<label class="badge badge-md badge-primary">
								Menunggu Approval
							</label>
							@endif
						</td>
						<td>
							@if($value->is_konfirmasi != 1 || $value->approval != 1)
							<div class="dropdown">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
									@if(get_admin() and $value->is_konfirmasi != 1 and $value->approval==null)
									<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_perijinan."/konfirmasi") }}"><i class="fa fa-check"></i> Konfirmasi</a>
									@elseif(strtolower(Session("role")["nm_role"])=="admin" and $value->is_konfirmasi != 1 and $value->approval==null) 
									<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_perijinan."/konfirmasi") }}"><i class="fa fa-check"></i> Konfirmasi</a>
									@endif
									
									@if($value->approval == null)
									<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_perijinan."/terima") }}"><i class="fa fa-check"></i> Terima</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_perijinan."/tolak") }}"><i class="fa fa-times"></i> Tolak</a>
									@endif
									
									@if($value->is_konfirmasi != 1 and $value->approval==null)
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_perijinan."/edit") }}"><i class="fa fa-edit"></i> Edit</a>
									<a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_perijinan) }}')"><i class="fa fa-times"></i> Hapus</a>
									@endif
									
									<a class="dropdown-item" href="#" onclick="goDetail('{{ $value->id_perijinan }}')"><i class="fa fa-eye"></i> Detail</a>	
								</div>
							</div>
							@endif
						</td>
					</tr>
					@endforeach
					@if(count($data)<1)
					<tr>
						<td colspan="10" class="text-center"> Data Kosong</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		@include('template.paginator')
	</div>
</form>

@if(isset($popup))
<div class="modal fade bd-example-modal-lg" id="modal-notif" tabindex="-1" role="dialog" aria-labelledby="modal-notif" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="exampleModalLongTitle">PERINGATAN</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h5 class="text-center">Segera Lakukan Konfirmasi Pada Setiap Perijinan</h5>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endif

<div class="modal fade" id="modal-konfirmasi" tabindex="-1" role="dialog" aria-labelledby="modal-konfirmasi" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<form method="POST" action="{{ url(Request::segment(1).'/allkonfirmasi') }}">
			@csrf
			<div class="modal-content">
				<div class="modal-body">
					<h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Konfirmasi Perijinan</h4>
					<hr>
					<div class="col-md-12" style="margin-top: 15px">
						<select class="form-control" id="co_id_perush" name="co_id_perush">
							<option value="0">-- Pilih Perusahaan --</option>
							@foreach($perush as $key => $value)
							<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>\
							@endforeach
						</select>
					</div>
					<div class="row" style="margin-top: 15px">
						<div class="col-md-12">
							<label for="dr_tgl">
								<b>Dari Tanggal</b> <span class="span-required"> *</span>
							</label>
							<input type="date" class="form-control" name="co_dr_tgl" id="co_dr_tgl">
						</div>
						
						<div class="col-md-12" style="margin-top: 15px">
							<label for="sp_tgl">
								<b>Dari Tanggal</b> <span class="span-required"> *</span>
							</label>
							<input type="date" class="form-control" name="co_sp_tgl" id="co_sp_tgl">
						</div>
					</div>
					
				</div>
				
				<div class="modal-footer">
					<button type="submit" class="btn btn-sm btn-success"><span aria-hidden="true"><i class="fa fa-send"></i> Konfirmasi</span></button>
					<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i> Batal</span></button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-detail" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<center>
					<h4>Detail Ijin Karyawan</h4>
				</center>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="div-body" class="row">
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$("#f_id_perush").select2();
	$("#f_id_jenis").select2();
	$("#f_id_karyawan").select2();
	$("#modal-notif").modal('show');
	
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif	
	
	$("#f_id_perush").val('{{ Session("perusahaan")["id_perush"] }}').trigger("change");
	
	@if(isset($filter["f_id_perush"]))
	$("#f_id_perush").val('{{ $filter["f_id_perush"] }}').trigger("change");
	@endif
	
	@if(isset($filter["f_id_jenis"]))
	$("#f_id_jenis").val('{{ $filter["f_id_jenis"] }}').trigger("change");
	@endif
	
	@if(isset($filter["f_id_karyawan"]))
	$("#f_id_karyawan").val('{{ $filter["f_id_karyawan"] }}').trigger("change");
	@endif
	
	function goKonfirmasi(){
		$("#modal-konfirmasi").modal("show");
	}
	
	function goDetail(id){
		$("#modal-detail").modal("show");
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "{{ url('perijinan') }}/"+id+"/getdetail",
			success: function(data) {
				$("#div-body").html('');
				$("#div-body").append(data);
			},
		});
	}
	
	$('#f_id_perush').on("change", function(e) {
		$('#f_id_karyawan').empty();
		$.ajax({
			type: "GET",
			url: "{{ url('absensi/getkaryawan') }}/"+$("#f_id_perush").val(),
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