<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="container">
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
	<button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i>  Cetak</button>
</div>
    <div class="container" style=" margin-top:10px;">
        <div class="row">
            <div class="col-3">
                <center>
                    @php

                        if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                        }

                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="width: 120px">
                </center>
            </div>
            <div class="col-8">

                <h5 class="text-center">{{ $perusahaan->nm_perush }}</h5>
                <h6 class="text-center">{{ $perusahaan->alamat }},
                    {{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</h6>
                <h6 class="text-center">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</h6>

            </div>
        </div>
    </div>
    <div class="row">
	<div class="col-md-5">
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
							@if(isset($data->vendor->nm_ven)){{ strtoupper($data->vendor->nm_ven) }}@endif
						</b>
					</td>
				</tr>
				@else
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
				<tr>
					<td width="30%">Layanan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->layanan->nm_layanan)){{ strtoupper($data->layanan->nm_layanan) }}@endif
						</b>
					</td>
				</tr>
				@if(Request::segment(1)=="dmtrucking")
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
				@elseif(Request::segment(1)=="dmcontainer")
				<tr>
					<td width="30%">No. Container</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->no_container }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">No. Seal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $data->no_seal }}
						</b>
					</td>
				</tr>
				@elseif(Request::segment(1)=="dmvendor")

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
							{{ strtoupper($data->info) }}
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
			</thead>
		</table>
	</div>
	<div class="col-md-3">
		<table class="table table-responsive">
			<tr>
				<td width="40%">Rata - rata Pendapatan</td>
				<td width="2%">
					:
				</td>
				<td>
					<b>
						@if(isset($data->c_total)){{ "Rp. ".number_format($data->c_total, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
					</b>
				</td>
			</tr>
			<tr>
				<td width="40%">Proyeksi Biaya</td>
				<td width="2%">
					:
				</td>
				<td>
					<b>
						@if(isset($data->c_pro)){{ "Rp. ".number_format($data->c_pro, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
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
					<b>
						@if(isset($proyeksi)){{ "Rp. ".number_format($proyeksi, 0, ',', '.') }} @else {{ "Rp. 0,00" }} @endif
					</b>
				</td>
			</tr>
		</table>
	</div>
</div>
<div>
	@if(strtolower(Request::segment(1))!="dmtiba")
	@include('operasional::daftarmuat.change')
	@include('operasional::daftarmuat.confirm')
	@endif
</div>
<br>
@if(isset($detail))
@include('operasional::daftarmuat.inc_detail')
@endif
</body>
</html>