@extends('template.document')

@section('data')
<div class="row" style="margin-top: 1%">
	<div class="col-md-6">
		<table class="table table-responsive">
			<thead>
				<tr>
					<td width="50%">No. INVOICE</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->kode_invoice)){{ strtoupper($data->kode_invoice) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Perusahaan Asal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->id_perush)){{ strtoupper($data->perusahaan->nm_perush) }}@endif
						</b>
					</td>
				</tr>			
				<tr>
					<td width="30%">Tgl Invoice dibuat</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->tgl)){{ daydate($data->tgl).", ".dateindo($data->tgl) }}@endif
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Invoice Jatuh Tempo</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->inv_j_tempo)){{ daydate($data->inv_j_tempo).", ".dateindo($data->inv_j_tempo) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Nama Pelanggan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
                            {{strtoupper($data->nm_pelanggan)}}
						</b>
					</td>
				</tr>
                
			</thead>
		</table>		
	</div>
	
	<div class="col-md-6">
		<table class="table table-responsive">
			<thead>
				
                <tr>
					<td width="30%">Kontak</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->kontak)){{ strtoupper($data->kontak) }}@endif
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">HP</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->hp)){{ strtoupper($data->hp) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Total Invoice</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->total)){{ toRupiah($data->total) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Status</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->id_status)){{ strtoupper($data->status->nm_status) }}@endif
						</b>
					</td>
				</tr>
			</thead>
		</table>		
	</div>
</div>
<div>
@include('keuangan::invoice.tambahsttinvoice')
</div>
<br>
@include('keuangan::invoice.tabledetail-invoice')
@endsection