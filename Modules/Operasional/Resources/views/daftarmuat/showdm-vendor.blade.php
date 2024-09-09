@extends('template.document')

@section('data')
<div class="col-md-12 text-right" style="margin-top: -1%">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
</div>
<div class="row" style="margin-top: 10px">
	<div class="col-md-4">
		<table class="table table-responsive">
			<thead>
				<tr>
					<td width="30%">No. DM</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->kode_dm)){{ $data->kode_dm }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Perusahaan Asal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->perush_asal->nm_perush)){{ strtoupper($data->perush_asal->nm_perush) }}@endif
						</b>
					</td>
				</tr>
				@if(Request::segment(1)=="dmvendor")
				<tr>
					<td width="30%">Vendor Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->vendor->nm_ven))
							{{ strtoupper($data->vendor->nm_ven) }}
							@elseif(isset($data->perush_tujuan->nm_perush))
							{{ strtoupper($data->perush_tujuan->nm_perush) }}
							@endif
						</b>
					</td>
				</tr>
				@else
				@if(Request::segment(1)!="dmkota")
				<tr>
					<td width="30%">Perusahaan Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->perush_tujuan->nm_perush)){{ strtoupper($data->perush_tujuan->nm_perush) }}@endif
						</b>
					</td>
				</tr>
				@endif
				
				@endif
				<tr>
					<td width="30%">Kota Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->wilayah_tujuan->nama_wil)){{ strtoupper($data->wilayah_tujuan->nama_wil) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Layanan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->layanan->nm_layanan)){{ strtoupper($data->layanan->nm_layanan) }}@endif
						</b>
					</td>
				</tr>
				@if(Request::segment(1)=="dmtrucking" or Request::segment(1)=="dmcontainer" or Request::segment(1)=="dmkota")
				<tr>
					<td width="30%">Armada</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->armada->nm_armada)){{ strtoupper($data->armada->nm_armada) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Sopir</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->sopir->nm_sopir)){{ strtoupper($data->sopir->nm_sopir) }}@endif
						</b>
					</td>
				</tr>
				@endif
				<tr>
					<td width="30%">Status</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->status->nm_status))
							{{ strtoupper($data->status->nm_status) }}
							@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Keterangan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ strtoupper($data->keterangan) }}
						</b>
					</td>
				</tr>
				
			</thead>
		</table>
	</div>
	<div class="col-md-4">
		<table class="table table-responsive">
			<thead>
				@if(Request::segment(1)=="dmtiba")
				<tr>
					<td width="40%">Tanggal Berangkat</td>
					<td width="2%"><b>:</b></td>
					<td>
						@if(isset($data->atd) and $data->atd!=null)
						<b>
							{{ daydate($data->atd).", ".dateindo($data->atd) }}
						</b>
						@else
						-
						@endif
					</td>
				</tr>
				<tr>
					<td width="40%">Tanggal Sampai</td>
					<td width="2%"><b>:</b></td>
					<td>
						@if(isset($data->ata) and $data->ata!=null)
						<b>
							{{ daydate($data->ata).", ".dateindo($data->ata) }}
						</b>
						@else
						-
						@endif
					</td>
				</tr>
				@else
				<tr>
					<td width="40%">Rencana Berangkat</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ daydate($data->tgl_berangkat).", ".dateindo($data->tgl_berangkat) }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">Realisasi Berangkat</td>
					<td width="2%"><b>:</b></td>
					<td>
						@if(isset($data->atd) and $data->atd!=null)
						<b>
							{{ daydate($data->atd).", ".dateindo($data->atd) }}
						</b>
						@else
						-
						@endif
					</td>
				</tr>
				<tr>
					<td width="40%">Estimasi Sampai</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ daydate($data->tgl_sampai).", ".dateindo($data->tgl_sampai) }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">Realisasi Sampai</td>
					<td width="2%"><b>:</b></td>
					<td>
						@if(isset($data->ata) and $data->ata!=null)
						<b>
							{{ daydate($data->ata).", ".dateindo($data->ata) }}
						</b>
						@else
						-
						@endif
					</td>
				</tr>
				@endif
				<tr>
					<td width="40%">Dari Pelabuhan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->nm_dari }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">Ke Pelabuhan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->nm_tuju }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">PJ Asal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->nm_pj_dr }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">PJ Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->nm_pj_tuju }}
						</b>
					</td>
				</tr>
				<tr>
					@if(isset($data->no_container) and $data->no_container != null)
					<td width="30%">No. Container</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->no_container }}
						</b>
					</td>
					@endif
				</tr>
				<tr>
					@if(isset($data->no_seal) and $data->no_seal != null)
					<td width="30%">No. Seal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->no_seal }}
						</b>
					</td>
					@endif
				</tr>
			</thead>
		</table>
	</div>
	@if(Request::segment(1)!="dmtiba")
	<div class="col-md-4">
		<table class="table table-responsive">
			<tr>
				<td width="40%">Cara Hitung</td>
				<td width="2%">
					:
				</td>
				<td>
					<b>
						@if($data->cara == 1)
						Kg
						@elseif($data->cara==2)
						Kgv
						@elseif($data->cara == 3)
						M3
						@elseif($data->cara == 4) 
						Borongan
						@endif
					</b>
				</td>
			</tr>
			<tr>
				<td width="40%">Harga</td>
				<td width="2%">
					:
				</td>
				<td>
					<b>
						{{ toRupiah($data->n_harga) }}
					</b>
				</td>
			</tr>
			<tr>
				<td width="40%">Est. Pendapatan</td>
				<td width="2%">
					:
				</td>
				<td>
					<b>
						@if(isset($data->c_total)){{ "Rp. ".number_format($data->c_total, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
					</b>
					<a href="{{ url('dmtrucking/'.Request::segment(2).'/counting') }}"><i style="margin-left: 5px; font-size:12pt " class="fa fa-refresh"> </i></a>
				</td>
			</tr>
			<tr>
				<td width="40%">Proyeksi Biaya</td>
				<td width="2%">
					:
				</td>
				<td>
					@php
					$persen = divnum($data->c_pro, $data->c_total)*100;
					@endphp
					<b>
						@if(isset($data->c_pro)){{ "Rp. ".number_format($data->c_pro, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
						( {{ number_format($persen, 2, ',', '.') }} % )
					</b>
				</td>
			</tr>
			<tr>
				@php
				$proyeksi = (Double)$data->c_total-$data->c_pro;
				@endphp
				<td width="40%">Proyeksi Laba / Rugi</td>
				<td width="2%">
					:
				</td>
				<td>
					@php
					$persentase = divnum($proyeksi, $data->c_total)*100;
					@endphp
					<b>
						@if(isset($proyeksi)){{ "Rp. ".number_format($proyeksi, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
						( {{ number_format($persentase, 2, ',', '.') }} % )
					</b>
				</td>
			</tr>
		</table>
	</div>
	@endif
</div>
@include('operasional::daftarmuat.change')
@include('operasional::daftarmuat.confirm')
@if(isset($detail))
@if($data->is_vendor==true)
@include('operasional::daftarmuat.inc_detail_ven')
@else
@include('operasional::daftarmuat.inc_detail')
@endif
@endif

@endsection
