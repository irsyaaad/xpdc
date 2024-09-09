@extends('template.document')

@section('data')
@section('style')
<style>
	.col-md-4{
        margin-top: 15px;
    }
    
    .col-md-12{
        margin-top: 15px;
    }
    
    #divbayar{
        margin-top: 15px;
        border-radius: 10px;
        padding: 5px;
        padding-bottom: 10px;
    }
    
    .modal-dialog {
        position:absolute;
        top:60% !important;
        left: 30% !important;
        transform: translate(0, -50%) !important;
        -ms-transform: translate(0, -50%) !important;
        -webkit-transform: translate(0, -50%) !important;
        width:100%;
        height:80%;
    }

	thead{
		font-size: 11pt;
		font-weight: bold;
	}
</style>
@endsection

<div class="col-md-12 text-right" style="margin-top: -1%">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
</div>

<div class="row" style="margin-top: 5px">
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
					<td width="30%">Perusahaan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ strtoupper(Session("perusahaan")["nm_perush"]) }}
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Admin Penerima</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->nm_user)){{ strtoupper($data->nm_user) }}@endif
						</b>
					</td>
				</tr>		
				<tr>
					<td width="30%">Tgl Invoice dibuat</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->tgl_invoice)){{ daydate($data->tgl_invoice).", ".dateindo($data->tgl_invoice) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Tgl Penagihan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->tgl_tagihan)){{ daydate($data->tgl_tagihan).", ".dateindo($data->tgl_tagihan) }}@endif
						</b>
					</td>
				</tr>
                <tr>
					<td width="30%">Invoice Jatuh Tempo</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->tgl_jatuh_tempo)){{ daydate($data->tgl_jatuh_tempo).", ".dateindo($data->tgl_jatuh_tempo) }}@endif
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
					<td width="30%">Perusahaan Pengirim</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->nm_perush)){{ strtoupper($data->nm_perush) }}@endif
						</b>
					</td>
				</tr>
                
				<tr>
					<td width="30%">Admin Pengirim</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->admin }}
						</b>
					</td>
				</tr>

				<tr>
					<td width="30%">Status</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->nm_status)){{ strtoupper($data->nm_status) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Total</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->total)){{ "Rp. ".number_format($data->total, 0, ',', '.') }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Dibayar</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($bayar)){{ "Rp. ".number_format($bayar, 0, ',', '.') }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Sisa</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->total)){{ "Rp. ".number_format($data->total-$bayar, 0, ',', '.') }}@endif
						</b>
					</td>
				</tr>
			</thead>
		</table>		
	</div>
</div>

@include('keuangan::invoicehandlingtj.biayainvoicetj')
@endsection