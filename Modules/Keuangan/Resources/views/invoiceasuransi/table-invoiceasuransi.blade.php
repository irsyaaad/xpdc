
<div class="row" style="margin-top:-2%">
	<div class="col-md-12">
		<h4 style="margin-left: 3%"><i class="fa fa-thumb-tack"></i>
			<b>DATA ASURANSI</b>
		</h4>
		<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
			@csrf
			<table class="table table-responsive table-hover" width="100%">
				<thead style="background-color: #f4f5f8;">
					<tr>
						<th>Kode STT</th>
						<th class="text-center">Pelanggan</th>
						<th class="text-center">Pengirim</th>
						<th class="text-center">Asal -> Tujuan</th>
						<th>Harga Pertanggungan</th>
						<th>Nominal</th>
						<th>Bayar</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
                    {{-- {{dd($detail)}} --}}
					@foreach($detail as $key => $value)
					<tr>
						<td>{{ strtoupper($value->asuransi->id_stt) }}</td>
						<td>{{ strtoupper($value->asuransi->pelanggan->nm_perush) }}</td>
                        <td>{{ strtoupper($value->asuransi->nm_pengirim) }}</td>
						<td>
							{{ $value->asuransi->asal->nama_wil}} <i class="fa fa-arrow-right"></i> {{ $value->asuransi->tujuan->nama_wil}}
						</td>
						<td>
							{{ toRupiah($value->asuransi->harga_pertanggungan) }}
						</td>
						<td>
							{{ toRupiah($value->asuransi->nominal) }}
						</td>
						<td>
							@if ($value->asuransi->bayar !== null)
								{{ toRupiah($value->asuransi->bayar->sum('n_bayar') )}}
							@else
								Rp. 0
							@endif
						</td>
						<td>
							@php
							$url = url(Request::segment(1)."/".$value->id_draft."/hapusdraft");
							@endphp
							
							@if($data->id_status == 1)
							<button type="button" class="btn btn-sm btn-danger" onclick="CheckDelete('{{ $url }}')">
								<i class="fa fa-times"> </i> Hapus
							</button>
							@elseif($data->id_status == 2 || ($value->asuransi->bayar !== null and $value->asuransi->bayar->sum('n_bayar') < $value->asuransi->nominal))
							<button type="button" onclick="setBayar('{{ $value->id_asuransi }}', '{{ $value->asuransi->id_stt }}',  '{{ $value->asuransi->nominal }}')" class="btn btn-sm btn-primary">
								<i class="fa fa-money"> </i> Set Bayar
							</button>
							@endif
							
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</form>
		@include('keuangan::invoice.modal');
	</div>
</div>
<script>
	function setBayar(id_asuransi, kode_stt, nominal) {
		console.log(id_asuransi, kode_stt, nominal)
		var today = new Date().toISOString().split('T')[0];
		$("#modal-dm").modal('show');
		$("#tgl_bayar").val(today);
		$("#id_stt").val(kode_stt);
		$("#n_bayar").val(nominal);
		$("#modal-dm").find('.modal-title').text("Pembayaran Asuransi Pada Invoice");
		$("#info").val("Pembayaran Asuransi STT No. "+kode_stt+" Atas Nama {{ $data->nm_pelanggan }} ");
		$("#form-bayar").attr("action", "{{ url('invoiceasuransi').'/bayar/' }}"+id_asuransi);
	}
	
	function goSubmitUpdate() {
		$("#form-bayar").submit();
	}
	
	function setPPN(id_stt,kode,n_ppn) {
		$("#modal-stt").modal('show');
		$("#stt").val(id_stt);
		$("#kode_stt").val(kode);
		$("#n_ppn").val(n_ppn);
	}
</script>
