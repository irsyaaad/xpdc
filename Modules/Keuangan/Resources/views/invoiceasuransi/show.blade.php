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
					<td width="30%">Total</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->total)){{ "Rp. ".number_format($data->total, 0, ',', '.') }}@endif
						</b>
					</td>
				</tr>
			</thead>
		</table>		
	</div>
</div>

<div>
@include('keuangan::invoicetambahasuransi')
</div>
<br>
@include('keuangan::invoicetable-invoiceasuransi')
@endsection