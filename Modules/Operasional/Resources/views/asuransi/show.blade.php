@extends('template.document')

@section('data')
<div class="row" style="margin-top: 1%">
	<div class="col-md-6">
		<table class="table table-responsive">
			<thead>
				<tr>
					<td width="50%">Kode STT</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->id_stt)){{ strtoupper($data->id_stt) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Pelanggan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->pelanggan->nm_perush)){{ strtoupper($data->pelanggan->nm_perush) }}@endif
						</b>
					</td>
				</tr>			
				<tr>
					<td width="30%">Pengirim</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->nm_pengirim)){{ $data->nm_pengirim }}@endif
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Asal <i class="fa fa-arrow-right"></i> Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->asal->nama_wil)){{ $data->asal->nama_wil }}@endif <i class="fa fa-arrow-right"></i> @if(isset($data->tujuan->nama_wil)){{ $data->tujuan->nama_wil }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Tipe Barang</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
                            {{strtoupper($data->tipebarang->nm_tipe_kirim)}}
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Quantity</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
                            {{strtoupper($data->qty)}}
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Broker</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
                            {{strtoupper($data->nm_broker->nm_perush_asuransi)}}
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Harga Pertanggungan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
                            {{torupiah($data->harga_pertanggungan)}}
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Premi</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
                            {{torupiah($data->premi)}}
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Tarif</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
                            {{torupiah($data->nominal)}}
						</b>
					</td>
				</tr>
                
			</thead>
		</table>		
	</div>
</div>
@endsection