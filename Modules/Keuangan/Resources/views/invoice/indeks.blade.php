@extends('template.document')
@section('data')

<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	<div class="row  mt-1">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead style="background-color: grey; color : #ffff">
						<tr>
							<th>No.</th>
							<th>No. Invoice</th>
							<th>Tanggal Penagihan -> Jth. Tempo</th>
							<th>Pelanggan</th>
							<th>Total</th>
							<th>Dibayar</th>
							<th>Kurang</th>
							<th>Status Invoice</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@php
							$urls = "dr_tgl=".$filter["dr_tgl"]."&sp_tgl=".$filter["sp_tgl"]."&status=".$filter["status"]."&shareselect=".$filter["page"]."";
						@endphp
						@foreach($data as $key => $value)
						<tr @if($value->sisa!=0 and $value->inv_j_tempo<date("Y-m-d")) class="text-danger" @endif >
							<td>{{ ($key+1) }}</td>
							<td>

								<a href="{{ url(Request::segment(1)).'/'.$value->id_invoice.'/show?'.$urls }}" class="class-edit">
									{{ strtoupper($value->kode_invoice) }}
								</a>
								<br>{{$value->created_at}}
							</td>
							<td>
								@if(isset($value->tgl))
								{{ daydate($value->tgl).", ".dateindo($value->tgl) }}
								@endif
								
								@if(isset($value->inv_j_tempo))
								<br>-> 
								{{ daydate($value->inv_j_tempo).", ".dateindo($value->inv_j_tempo) }}
								@endif
							</td>
							<td>
								{{strtoupper($value->nm_pelanggan)}}
								<br>
								{{ $value->hp }}
							</td>
							<td>
								{{ toNumber($value->total) }}
							</td>
							<td>
								{{ toNumber($value->bayar) }}
							</td>
							<td>
								{{ toNumber($value->sisa) }}
							</td>
							<td>
								@if(isset($value->nm_status))
								{{strtoupper($value->nm_status)}}
								@endif
							</td>
							<td>
								<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_invoice."/show?".$urls) }}"><i class="fa fa-eye"></i> Detail</a>
										<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_invoice."/cetak") }}" target="_blank" rel="nofollow" title="Cetak Invoice"><i class="fa fa-print"></i> Cetak</a>
										
										@if($value->id_status=="1")
										<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_invoice."/send") }}"><i class="fa fa-send"></i> Terbitkan</a>
										@endif

										@if($value->id_status=="2")
										<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_invoice."/batal") }}"><i class="fa fa-times"></i> Batal Terbit</a>
										@endif
										
										@if($value->id_status=="1")
										<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$value->id_invoice."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
										@endif

										@if($value->total>$value->bayar)
										<a class="dropdown-item" href="#" onclick="goBayar('{{ $value->id_invoice }}', '{{ $value->sisa }}')"><i class="fa fa-money"></i> Bayar</a>
										@endif
										
										@if($value->id_status=="1")
										@method('DELETE')
										@csrf
										<a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1) . '/' . $value->id_invoice) }}')"><i class="fa fa-times"></i> Delete</a>
										@endif
									</div>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		@include('template.paginator')
	</div>
</form>

<div class="modal fade" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Pembayaran STT</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@php
			$ldate = date('Y-m-d H:i:s')
			@endphp
			<div class="modal-body">
				<form method="POST" action="" id="form-bayar">
					<input type="hidden" name="_method" id="_method" value="POST" />
					@csrf
					<div class="form-group">
						<label for="nama_bayar" >Nama Pembayar</label>
						<input class="form-control" id="nama_bayar" name="nama_bayar" placeholder="Nama Pembayar" />
						@if ($errors->has('nama_bayar'))
						<label style="color: red">
							{{ $errors->first('nama_bayar') }}
						</label>
						@endif
					</div>

					<div class="form-group">
						<label for="tgl_bayar" >No Referensi</label>
						<input class="form-control" id="referensi" name="referensi" placeholder="No Referensi " />
						@if ($errors->has('referensi'))
						<label style="color: red">
							{{ $errors->first('referensi') }}
						</label>
						@endif
					</div>
					
					<div class="form-group">
						<label for="tgl_bayar" >Tanggal Bayar<span class="span-required"> *</span></label>
						<input class="form-control" id="tgl_bayar" required name="tgl_bayar" type="date" placeholder="Masukan Tanggal Bayar" />
						@if ($errors->has('tgl_bayar'))
						<label style="color: red">
							{{ $errors->first('tgl_bayar') }}
						</label>
						@endif
					</div>
					
					<div class="form-group">
						<label for="n_bayar" >Nominal Bayar<span class="span-required"> *</span></label>
						<input class="form-control" step="any" required id="n_bayar" name="n_bayar" type="number" placeholder="Masukan Nilai bayar" />
						@if ($errors->has('n_bayar'))
						<label style="color: red">
							{{ $errors->first('n_bayar') }}
						</label>
						@endif
					</div>
					
					<div class="form-group">
						<label for="ac4_d" >Perkiraan Akun<span class="span-required"> *</span></label>
						<select class="form-control" id="ac4_d" required name="ac4_d">
							<option value="1"> -- Pilih Akun --</option>
							@foreach ($akun as $key => $value)
							<option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
							@endforeach
						</select>
						@if ($errors->has('ac4_d'))
						<label style="color: red">
							{{ $errors->first('ac4_d') }}
						</label>
						@endif
					</div>
					
					<div class="form-group">
						<label for="id_cr_byr" >Cara Bayar<span class="span-required"> *</span></label>
						<select class="form-control" id="id_cr_byr" required name="id_cr_byr">
							<option value=""> -- Pilih Cara Bayar --</option>
							@foreach ($cara as $key => $value)
							<option value="{{ $value->id_cr_byr_o }}">{{ strtoupper($value->nm_cr_byr_o) }}</option>
							@endforeach
						</select>
						@if ($errors->has('id_cr_byr'))
						<label style="color: red">
							{{ $errors->first('id_cr_byr') }}
						</label>
						@endif
					</div>
					
					<div class="form-group">
						<label for="info" >Keterangan</label>
						<textarea class="form-control" name="info" id="info"></textarea>
						@if ($errors->has('info'))
						<label style="color: red">
							{{ $errors->first('info') }}
						</label>
						@endif
					</div>
					
					<div class="col-md-12 text-right">
						
						<button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" >Bayar</button>
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<script type="text/javascript">
	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif
	
	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
	
	$.ajax({
		type: "GET", 
		url: "{{ url("getPerusahaan") }}", 
		dataType: "json",
		beforeSend: function(e) {
			if(e && e.overrideMimeType) {
				e.overrideMimeType("application/json;charset=UTF-8");
			}
		},
		success: function(response){
			
			$.each(response,function(key, value)
			{
				$("#filter").append('<option value=' + value.kode + '>' + value.value + '</option>');
			});
			
			@if(Session('id_perush')!=null)
			$("#filter").val('{{ Session('id_perush') }}');
			@endif
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.log(thrownError);
		}
	});
	$('#id_pelanggan').select2({
		placeholder: 'Cari Pelanggan ....',
		ajax: {
			url: '{{ url('getPelanggan') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_pendapatan').empty();
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
	$('#id_invoice').select2({
		placeholder: 'Cari Invoice ....',
		ajax: {
			url: '{{ url('Invoice') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_ac').empty();
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
	
	$('#id_stt').select2({
		placeholder: 'Cari RESI ....',
		ajax: {
			url: '{{ url('getSttPerush') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_stt').empty();
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
	@if(get_admin() and isset($filter["filterperush"]->nm_perush))
	$("#filterperush").empty();
	$("#filterperush").append('<option value="{{ $filter["filterperush"]->id_perush }}">{{ strtoupper($filter["filterperush"]->nm_perush) }}</option>');
	@endif
	
	@if(isset($filter["pelanggan"]->nm_pelanggan))
	$("#id_pelanggan").empty();
	$("#id_pelanggan").append('<option value="{{ $filter["pelanggan"]->id_pelanggan }}">{{ strtoupper($filter["pelanggan"]->nm_pelanggan) }}</option>');
	@endif
	
	@if(isset($filter["stt"]->kode_stt))
	$("#id_stt").empty();
	$("#id_stt").append('<option value="{{ $filter["stt"]->id_stt }}">{{ strtoupper($filter["stt"]->kode_stt) }}</option>');
	@endif
	
	@if(isset($filter["invoice"]->kode_invoice))
	$("#id_invoice").empty();
	$("#id_invoice").append('<option value="{{ $filter["invoice"]->id_invoice }}">{{ strtoupper($filter["invoice"]->kode_invoice) }}</option>');
	@endif

	@if(isset($filter["status"]))
	$("#status").val('{{ $filter["status"] }}');
	@endif

	function goBayar(id, nominal){
		$("#modal-bayar").modal("show");
		$("#n_bayar").val(nominal);
		$("#form-bayar").attr("action", "{{ url('invoice') }}/"+id+"/bayarall");
	}

	@if(old('tgl_bayar')!=null)
	$("#tgl_bayar").val("{{ old('tgl_bayar') }}");
	@else
	$("#tgl_bayar").val("{{ date('Y-m-d') }}");
	@endif
</script>
@endsection