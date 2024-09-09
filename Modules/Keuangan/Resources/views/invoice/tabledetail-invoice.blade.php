
<div class="row" style="margin-top:-2%">
	<div class="col-md-12">
		<h4 style="margin-left: 3%"><i class="fa fa-thumb-tack"></i>
			<b>DATA RESI</b>
		</h4>
		<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
			@csrf
			<table class="table table-responsive table-striped" width="100%">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No. RESI</th>
						<th>Pengirim</th>
						<th>Asal -> Tujuan</th>
						<th>Harga Kirim</th>
						<th>Diskon</th>
						<th>PPN</th>
						<th>Asuransi</th>
						<th>Total</th>
						<th>Bayar</th>
						<th>Kurang</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($detail as $key => $value)
					<tr>
						<td>
							{{ strtoupper($value->kode_stt) }}
						</td>
						<td>
							{{ $value->pengirim_nm }}
							<br>{{ $value->pengirim_telp }}
						</td>
						<td>
							{{ $value->wil_asal}}
							<br>
							->
							<br>
							{{ $value->wil_tujuan}}
						</td>
						<td>
							{{ toNumber($value->n_hrg_bruto) }}
						</td>
						<td>
							{{ toNumber($value->n_diskon) }}
						</td>
						<td>
							{{ $value->n_ppn }}
						</td>
						<td>
							{{ toNumber($value->n_asuransi) }}
						</td>
						<td>
							{{ toNumber($value->c_total) }}
						</td>
						<td>
							{{ toNumber($value->bayar) }}
						</td>
						<td>
							@php
								$piutang = $value->c_total-$value->bayar; 
							@endphp
							{{ toNumber($piutang) }}
						</td>
						<td>
							@php
							$url = url(Request::segment(1)."/".$value->id_draft."/hapusdraft");
							@endphp
							
							@if($data->id_status == 1)
							<button type="button" class="btn btn-sm btn-danger" onclick="CheckDelete('{{ $url }}')">
								<i class="fa fa-times"> </i> Hapus
							</button>
							<button class="btn btn-sm btn-primary" type="button" onclick="setPPN('{{$value->id_stt}}','{{$value->kode_stt}}','{{$value->n_ppn}}')">
								<i class="fa fa-cog"> </i> Set PPN
							</button>
							@elseif($value->c_total > $value->x_n_bayar)
							<button type="button" onclick="setBayar('{{ $value->id_stt }}', '{{ $value->kode_stt }}',  '{{ $piutang }}')" class="btn btn-sm btn-primary">
								<i class="fa fa-money"> </i> Bayar stt
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
	function setBayar(id_stt, kode_stt, piutang) {
		var today = new Date().toISOString().split('T')[0];
		$("#modal-dm").modal('show');
		$("#tgl_bayar").val(today);
		$("#id_stt").val(id_stt);
		$("#n_bayar").val(piutang);
		$("#info").val("Pembayaran STT No. "+kode_stt+" Atas Nama {{ $data->nm_pelanggan }} ");
		$("#form-bayar").attr("action", "{{ url('pembayaran').'/store/' }}"+id_stt);
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
