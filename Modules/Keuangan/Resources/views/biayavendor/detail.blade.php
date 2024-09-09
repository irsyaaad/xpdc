@extends('template.document2')
@section('style')
<style>
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
		top:50% !important;
		left: 35% !important;
		transform: translate(0, -50%) !important;
		-ms-transform: translate(0, -50%) !important;
		-webkit-transform: translate(0, -50%) !important;
		width:90%;
		height:80%;
	}
	.tr-bold{
		font-weight: bold;
	}
</style>

@endsection
@section('data')

<div class="row">
	<div class="col-md-4">
		<table class="table table-responsive">
			<thead>
				<tr>
					<td width="30%">No. DM</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($dm->kode_dm)){{ $dm->kode_dm }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Perusahaan Asal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($dm->perush_asal->nm_perush)){{ strtoupper($dm->perush_asal->nm_perush) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Vendor Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($dm->vendor->nm_ven))
							{{ strtoupper($dm->vendor->nm_ven) }}
							@elseif(isset($dm->perush_tujuan->nm_perush))
							{{ strtoupper($dm->perush_tujuan->nm_perush) }}
							@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Kota Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($dm->wilayah_tujuan->nama_wil)){{ strtoupper($dm->wilayah_tujuan->nama_wil) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Layanan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($dm->layanan->nm_layanan)){{ strtoupper($dm->layanan->nm_layanan) }}@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Status</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($dm->status->nm_status))
							{{ strtoupper($dm->status->nm_status) }}
							@endif
						</b>
					</td>
				</tr>
				<tr>
					<td width="30%">Keterangan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ strtoupper($dm->info) }}
						</b>
					</td>
				</tr>
				
			</thead>
		</table>
	</div>
	<div class="col-md-4">
		<table class="table table-responsive">
			<thead>
				<tr>
					<td width="40%">Rencana Berangkat</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ daydate($dm->tgl_berangkat).", ".dateindo($dm->tgl_berangkat) }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">Realisasi Berangkat</td>
					<td width="2%"><b>:</b></td>
					<td>
						@if(isset($dm->atd) and $dm->atd!=null)
						<b>
							{{ daydate($dm->atd).", ".dateindo($dm->atd) }}
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
							{{ daydate($dm->tgl_sampai).", ".dateindo($dm->tgl_sampai) }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">Realisasi Sampai</td>
					<td width="2%"><b>:</b></td>
					<td>
						@if(isset($dm->ata) and $dm->ata!=null)
						<b>
							{{ daydate($dm->ata).", ".dateindo($dm->ata) }}
						</b>
						@else
						-
						@endif
					</td>
				</tr>
				<tr>
					<td width="40%">Dari Pelabuhan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $dm->nm_dari }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">Ke Pelabuhan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $dm->nm_tuju }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">PJ Asal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $dm->nm_pj_dr }}
						</b>
					</td>
				</tr>
				<tr>
					<td width="40%">PJ Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $dm->nm_pj_tuju }}
						</b>
					</td>
				</tr>
				<tr>
					@if(isset($dm->no_container) and $dm->no_container != null)
					<td width="30%">No. Container</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $dm->no_container }}
						</b>
					</td>
					@endif
				</tr>
				<tr>
					@if(isset($dm->no_seal) and $dm->no_seal != null)
					<td width="30%">No. Seal</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							{{ $dm->no_seal }}
						</b>
					</td>
					@endif
				</tr>
			</thead>
		</table>
	</div>
	<div class="col-md-4">
		<table class="table table-responsive">
			<tr>
				<td width="40%">Cara Hitung</td>
				<td width="2%">
					:
				</td>
				<td>
					<b>
						@if($dm->cara == 1)
						Kg
						@elseif($dm->cara==2)
						Kgv
						@elseif($dm->cara == 3)
						M3
						@elseif($dm->cara == 4) 
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
						{{ toNumber($dm->n_harga) }}
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
						{{ toNumber($dm->c_total) }}
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
					$persen = divnum($dm->c_pro, $dm->c_total)*100;
					@endphp
					<b>
						{{ toNumber($dm->c_pro) }}
						( {{ number_format($persen, 2, ',', '.') }} % )
					</b>
				</td>
			</tr>
			<tr>
				@php
				$proyeksi = (Double)$dm->c_total-$dm->c_pro;
				@endphp
				<td width="40%">Proyeksi Laba / Rugi</td>
				<td width="2%">
					:
				</td>
				<td>
					@php
					$persentase = divnum($proyeksi, $dm->c_total)*100;
					@endphp
					<b>
						{{ toNumber($proyeksi) }}
						( {{ number_format($persentase, 2, ',', '.') }} % )
					</b>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="col-md-12">
		<div class="text-right">
			<div class="dropdown d-inline-block">
				<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fa fa-filter"> </i> Opsi Menu
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" dm-toggle="modal" dm-target="#modal-create" onclick="refresh()"> 
						<i class="fa fa-plus"></i> Tambah Biaya
					</a>
					<a class="dropdown-item" href="{{ url(Request::segment(1)."/".$dm->id_dm."/print") }}" target="_blank"> 
						<i class="fa fa-print"></i> Cetak
					</a>
					@if($dm->is_approve!=true)
					<a class="dropdown-item" href="#" onclick="setApprove('{{ Request::segment(2) }}')">
						<i class="fa fa-check"></i> Approve 
					</a>
					@else
					<a class="dropdown-item" href="#" onclick="setBatalApprove('{{ Request::segment(2) }}')">
						<i class="fa fa-check"></i> Batal Approve
					</a>
					@endif
					<a href="{{ url(Request::segment(1)."/".Request::segment(2)."/listbayar") }}" class="dropdown-item"><i class="fa fa-dollar"> </i> List Bayar</a>
					<a href="{{ url(Request::segment(1)."/".Request::segment(2)."/bayar") }}" class="dropdown-item"><i class="fa fa-money"></i> Set Bayar</a>
				</div>
			</div>
			<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning">
				<i class="fa fa-reply"></i>	Kembali
			</a>
		</div>

		<ul class="nav nav-tabs nav-bold nav-tabs-line">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#tabdetail">
					<span class="nav-icon">
						<i class="fa fa-eye"></i>
					</span>
					<span class="nav-text">Biaya Stt</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tabumum">
					<span class="nav-icon">
						<i class="fa fa-eye"></i>
					</span>
					<span class="nav-text">Biaya Umum</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tabvendor">
					<span class="nav-icon">
						<i class="fa fa-truck"></i>
					</span>
					<span class="nav-text">Biaya Vendor</span>
				</a>
			</li>
		</ul>
		
		<form method="GET" action="#" enctype="multipart/form-dm" id="form-select">
			<input type="hidden" name="_method" id="_method" type="POST" />
			@csrf
			<div class="tab-content">
				<div class="tab-pane active show" id="tabdetail" role="tabpanel" aria-labelledby="tabdetail">
					<table class="table table-responsive table-bordered" id="tableasal">
						<thead style="background-color: grey; color : #ffff">
							<tr>
								<th>No. </th>
								<th>Kode STT</th>
								<th>Pengirim</th>
								<th>Penerima</th>
								<th>Total Koli</th>
								<th>Koli Termuat</th>
								<th>Kg</th>
								<th>Kgv</th>
								<th>M3</th>
								<th>Omzet</th>
								<th>Biaya</th>
							</tr>
						</thead>
						<tbody>
							@php
							$total = 0;
							$t_biaya = 0;
							$t_brt = 0;
							$t_vol = 0;
							$t_kbk = 0;
							@endphp
							@foreach($stt as $key => $value)
							<tr>
								<td>{{ $key+1 }} </td>
								<td>
									<a href="#" onclick="myFunction('{{ $value->id_stt }}')" class="class-edit">
										{{ strtoupper($value->kode_stt) }}
									</a>
									<br>{{ dateindo($value->tgl_masuk) }}
								</td>
								<td>
									{{ strtoupper($value->pengirim_nm)}}<br><span class="label label-inline label-light-primary font-weight-bold">{{$value->pengirim_telp}}</span><br><span >{{$value->pengirim_alm}}</span>
								</td>					
								<td>
									@isset($value->penerima_nm)
									{{ strtoupper($value->penerima_nm)}}
									@endisset
									<br>
									<span class="label label-inline label-light-primary font-weight-bold">
										@isset($value->penerima_telp)
										{{$value->penerima_telp}}
										@endisset
									</span>
									<br>
									<span>
										@isset($value->penerima_alm)
										{{$value->penerima_alm}}
										@endisset
									</span>
								</td>
								<td class="text-right">{{ $value->n_koli }}</td>
								<td class="text-right">{{ $value->muat }}</td>
								@php
								$t_brt += $value->n_berat;
								$t_vol += $value->n_volume;
								$t_kbk += $value->n_kubik;
								@endphp
								<td class="text-right">{{ tonumber($value->n_berat) }}</td> 
								<td class="text-right">{{ tonumber($value->n_volume) }}</td> 
								<td class="text-right">{{ tonumber($value->n_kubik) }}</td>
								@php
								$c_total = $value->n_tarif_koli * $value->muat;
								$total += $c_total;
								$biaya = 0;
								if($dm->cara==1){
									$biaya = $value->n_berat * $dm->n_harga;
								}elseif($dm->cara==2){
									$biaya = $value->n_volume * $dm->n_harga;
								}elseif($dm->cara==3){
									$biaya = $value->n_kubik * $dm->n_harga;
								}else{
									$biaya = $dm->n_harga;
								}
								
								$t_biaya += $biaya;
								@endphp
								<td class="text-right">{{ toNumber($c_total) }}</td> 
								<td class="text-right">{{ toNumber($biaya) }}</td> 
							</tr>
							@endforeach
							<tr>
								<td colspan="6" class="text-right"><h6><b>TOTAL :</b></h6></td>
								<td class="text-right"><h6>{{ $t_brt }}</h6></td>
								<td class="text-right"><h6>{{ $t_vol }}</h6></td>
								<td class="text-right"><h6>{{ $t_kbk }}</h6></td>
								<td class="text-right"><h6>{{ toNumber($total) }}</h6></td>
								<td class="text-right"><h6>{{ toNumber($t_biaya) }}</h6></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tabumum" role="tabpanel" aria-labelledby="tabumum">
					<table class="table table-responsive table-bordered" style="margin-top: 5px">
						<thead style="background-color: rgb(151, 151, 151); color:#fff">
							<tr>
								<th>No</th>
								<th>Nomor STT</th>
								<th>Biaya</th>
								<th>Kelompok</th>
								<th>Keterangan</th>
								<th>Biaya</th>
								<th>Bayar</th>
								<th>Sisa</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@php
							$total = 0;
							$t_bayar = 0;
							$t_sisa = 0;
							@endphp
							@foreach($bumum as $key => $value)
							<tr>
								<td>{{ ($key+1) }}</td>
								<td>@if(isset($value->kode_stt)){{ strtoupper($value->kode_stt) }}@endif</td>
								<td>
									@if(isset($value->nm_biaya_grup))
									{{  strtoupper($value->nm_biaya_grup)  }}
									@endif
									<br>{{ $value->tgl_posting }}
								</td>
								<td>
									@if(isset($value->klp))
									{{$value->klp}}
									@endif
								</td>
								<td>
									@if(isset($value->keterangan))
									{{$value->keterangan}}
									@endif
								</td>
								<td class="text-right">
									{{ toNumber($value->nominal) }}
									@php
									$total += $value->nominal;
									@endphp
								</td>
								<td class="text-right">
									{{ toNumber($value->n_bayar) }}
									@php
									$t_bayar += $value->n_bayar;
									@endphp
								</td>
								<td class="text-right">
									@php
									$sisa = $value->nominal-$value->n_bayar;
									$t_sisa += $sisa;
									@endphp
									{{ toNumber($sisa) }}
								</td>
								<td>
									@if($value->n_bayar==0)
									<button class="btn btn-sm btn-warning" type="button" onclick="goEdit('{{ $value->id_pro_bi }}', '{{ $value->id_biaya_grup }}', '{{ $value->id_stt}}',  '{{ $value->nominal }}', '{{ $value->tgl_posting }}','{{ $value->keterangan }}', '{{ $value->id_jenis }}')" dm-toggle="tooltip" dm-placement="bottom" title="Edit">
										<span><i class="fa fa-edit"></i></span> Edit
									</button>
									
									<a href="#" class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url('dmtrucking').'/'.$value->id_pro_bi.'/deleteproyeksi' }}')">
										<span><i class="fa fa-times"></i></span> Hapus
									</a>
									@endif
								</td>
							</tr>
							@endforeach
							<tr class="tr-bold">
								<td colspan="5" class="text-center">Total</td>
								<td class="text-right">
									{{ toNumber($total) }}
								</td>
								<td class="text-right">
									{{ toNumber($t_bayar) }}
								</td>
								<td class="text-right">
									{{ toNumber($t_sisa) }}
								</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tabvendor" role="tabpanel" aria-labelledby="tabvendor">
					<table class="table table-responsive table-bordered" style="margin-top: 5px">
						<thead style="background-color: rgb(151, 151, 151); color:#fff">
							<tr>
								<th>No</th>
								<th>Nomor STT</th>
								<th>Biaya</th>
								<th>Kelompok</th>
								<th>Keterangan</th>
								<th>Biaya</th>
								<th>Bayar</th>
								<th>Sisa</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@php
							$total = 0;
							$t_bayar = 0;
							$t_sisa = 0;
							@endphp
							@foreach($bvendor as $key => $value)
							<tr>
								<td>{{ ($key+1) }}</td>
								<td>@if(isset($value->kode_stt)){{ strtoupper($value->kode_stt) }}@endif</td>
								<td>
									@if(isset($value->nm_biaya_grup))
									{{  strtoupper($value->nm_biaya_grup)  }}
									@endif
									<br>{{ $value->tgl_posting }}
								</td>
								<td>
									@if(isset($value->klp))
									{{$value->klp}}
									@endif
								</td>
								<td>
									@if(isset($value->keterangan))
									{{$value->keterangan}}
									@endif
								</td>
								<td class="text-right">
									{{ toNumber($value->nominal) }}
									@php
									$total += $value->nominal;
									@endphp
								</td>
								<td class="text-right">
									{{ toNumber($value->n_bayar) }}
									@php
									$t_bayar += $value->n_bayar;
									@endphp
								</td>
								<td class="text-right">
									@php
									$sisa = $value->nominal-$value->n_bayar;
									$t_sisa += $sisa;
									@endphp
									{{ toNumber($sisa) }}
								</td>
								<td>
									@if($value->n_bayar==0)
									<button class="btn btn-sm btn-warning" type="button" onclick="goEdit('{{ $value->id_pro_bi }}', '{{ $value->id_biaya_grup }}', '{{ $value->id_stt}}',  '{{ $value->nominal }}', '{{ $value->tgl_posting }}','{{ $value->keterangan }}', '{{ $value->id_jenis }}')" dm-toggle="tooltip" dm-placement="bottom" title="Edit">
										<span><i class="fa fa-edit"></i></span> Edit
									</button>
									
									<a href="#" class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url('dmtrucking').'/'.$value->id_pro_bi.'/deleteproyeksi' }}')">
										<span><i class="fa fa-times"></i></span> Hapus
									</a>
									@endif
								</td>
							</tr>
							@endforeach
							<tr class="tr-bold">
								<td colspan="5" class="text-center">Total</td>
								<td class="text-right">
									{{ toNumber($total) }}
								</td>
								<td class="text-right">
									{{ toNumber($t_bayar) }}
								</td>
								<td class="text-right">
									{{ toNumber($t_sisa) }}
								</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modal-approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<form method="POST" action="{{ url(Request::segment(1).'/approve/'.$dm->id_dm) }}" id="form-approve">
					@csrf
					<center>
						<h4 style="margin-left: 5%; font-weight: bold;" id="txtjudul"><span><i class="fa fa-check"></i></span> Approve Biaya HPP ?</h4>
					</center>
					<hr>
					<div class="text-right">
						<button type="submit" class="btn btn-md btn-success">Iya</button>
						
						<button type="button" class="btn btn-md btn-danger" dm-dismiss="modal" aria-label="Close" style="margin-left:10px"><span aria-hidden="true">Tidak</span></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ url(Request::segment(1)) }}" id="form-data">
				<input type="hidden" name="_method" id="_methods" value="POST">
				@csrf
				<div class="modal-body">
					<div class="row">
						
						<div class="col-md-12 text-left" style="padding-top: 6px" id="lbl-jenis">
							<label for="id_jenis">
								<b>Jenis Biaya</b> <span class="text-danger"> *</span>
							</label>
							<br>
							<label style="margin-left:10px">
								<input type="radio" id="id_jenis" name="id_jenis" value="1" /> Biaya Umum
							</label>
							<label style="margin-left:10px">
								<input type="radio" id="id_jenis" name="id_jenis" value="2" /> Biaya Vendor
							</label>
						</div>
						
						<div class="col-md-12 text-left" style="padding-top: 6px">
							<label for="id_stt">
								<b>Nomor STT</b> <span class="span-required"></span>
							</label>
							<br>
							
							<select class="form-control m-input m-input--square" id="b_id_stt" name="id_stt">
								<option value="">-- Pilih Nomor STT --</option>
								@foreach($stt as $key => $value)
								<option value="{{ $value->id_stt }}">{{ strtoupper($value->kode_stt)." ( ".$value->pengirim_nm." )" }}</option>
								@endforeach
							</select>
							
							@if ($errors->has('id_stt'))
							<label style="color: red">
								{{ $errors->first('id_stt') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12 text-left" style="padding-top: 6px">
							<label for="id_biaya_grup">
								<b>Group Biaya</b> <span class="span-required"> *</span>
							</label>
							<br>
							
							<select class="form-control" id="id_biaya_grup" name="id_biaya_grup" required>
								<option value="">-- Pilih Group Biaya --</option>
								@foreach($group as $key => $value)
								<option value="{{ $value->id_biaya_grup }}">{{ strtoupper($value->nm_biaya_grup) }}</option>
								@endforeach
							</select>
							
							@if ($errors->has('id_biaya_grup'))
							<label style="color: red">
								{{ $errors->first('id_biaya_grup') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12 text-left" id="lbl-nominal" style="padding-top: 10px">
							<label for="nominal">
								<b>Nominal Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="nominal" name="nominal" type="number" maxlength="16" />
							
							@if ($errors->has('nominal'))
							<label style="color: red">
								{{ $errors->first('nominal') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12 text-left" style="padding-top: 10px">
							<label for="nominal">
								<b>Tanggal Posting</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="tgl_posting" name="tgl_posting" type="date" required/>
							
							@if ($errors->has('tgl_posting'))
							<label style="color: red">
								{{ $errors->first('tgl_posting') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12 text-left" style="padding-top: 6px">
							<label for="keterangan">
								<b>Keterangan</b>
							</label>
							<br>
							
							<textarea class="form-control" placeholder="Masukan keterangan biaya ..." id="keterangan" name="keterangan"></textarea>
							
							@if ($errors->has('keterangan'))
							<label style="color: red">
								{{ $errors->first('keterangan') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12 text-right" style="margin-top: 5px">
							<hr>
							<button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"> <i class="fa fa-save"> </i> Simpan</button>
							<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
						</div>
					</div>
				</div>
			</form>
			
		</div>
	</div>
</div>

<div class="modal fade" id="modal-detail"  role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Detail Stt</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="hasil">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
			</div>
		</div>
	</div>
</div>

@endsection
@section("script")
<script type="text/javascript">
	
	function myFunction(id) {
		$("#modal-detail").modal('show');
		$.ajax({
			type: "GET",
			url: "{{ url('getDetailStt') }}/"+id,
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				$("#hasil").html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	}
	
	function setBatalApprove(id){
		$("#form-approve").attr("action", "{{ url('biayahpp/batalapprove/'.$dm->id_dm) }}");
		$("#txtjudul").text("Apakah Anda Ingin Batal Approve ?");
		$("#modal-approve").modal('show');
	}
	
	function setApprove(id){
		@if (Request::segment(1) == 'biayahppvendor')
		$("#form-approve").attr("action", "{{ url('biayahpp') }}/approve/"+id);
		@endif
		$("#txtjudul").text("Apakah Anda Ingin Approve ?");
		$("#modal-approve").modal('show');
	}
	
	function goEdit(id, id_group, id_stt, nominal, tgl_posting,keterangan, id_jenis){
		$("#_methods").val("PUT");
		$("#nominal").val(nominal);
		$("#b_id_stt").val(id_stt).trigger("change");
		$("#keterangan").text(keterangan);
		$("#id_biaya_grup").val(id_group).trigger('change');
		$("#form-data").attr("action", "{{ url('dmvendor') }}/"+id+'/updateproyeksi');
		$("#modal-create").modal("show");
		$("#tgl_posting").val(tgl_posting);
		$("input[name=id_jenis][value='"+id_jenis+"']").prop('checked', true);
		$("#lbl-jenis").hide();
		$("#lbl-nominal").show();
	}
	
	function refresh(){
		$("#_methods").val("POST");
		$("#form-data").attr("action", "{{ url("dmvendor/saveproyeksi/".Request::segment(2)) }}");
		$("#lbl-jenis").show();
		$("#nominal").val("");
		$("#b_id_stt").val("").trigger("change");
		$("#keterangan").text("");
		$("#id_biaya_grup").val("").trigger('change');
		$("#modal-create").modal("show");
		$("#tgl_posting").val('{{ date("Y-m-d") }}');
	}
	
	$("#b_id_stt").select2(
	{
		dropdownParent: $('#modal-create')
	}
	);
	
	$("#id_biaya_grup").select2({dropdownParent: $('#modal-create')});
	
</script>
@endsection
