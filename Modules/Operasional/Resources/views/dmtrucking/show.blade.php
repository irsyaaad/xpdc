@extends('template.document')

@section('data')
<div class="col-md-12 text-right" style="margin-top: -1%">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
</div>

<div class="row">
	<div class="col-md-4">
		<table class="table table-responsive">
			<thead>
				<tr>
					<td width="30%">No. MANIFEST</td>
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
                <tr>
					<td width="30%">Perusahaan Tujuan</td>
					<td width="2%"><b>:</b></td>
					<td>
						<b>
							@if(isset($data->perush_tujuan->nm_perush)){{ strtoupper($data->perush_tujuan->nm_perush) }}@endif
						</b>
					</td>
				</tr>
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
    <div class="col-md-4">
		<table class="table table-responsive">
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
</div>

@include('operasional::daftarmuat.change')
@include('operasional::daftarmuat.confirm')

<br>
<div class="col-md-12">
	<input type="text" class="form-control" id="search" placeholder="Cari Kode STT">
</div>
<br>
<div class="col-md-12">
	<ul class="nav nav-tabs nav-bold nav-tabs-line">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#tabdetail">
				<span class="nav-icon">
					<i class="fa fa-eye"></i>
				</span>
				<span class="nav-text">Data Stt</span>
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
	</ul>
	
	<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
		@csrf
		<input type="hidden" name="_method" value="GET">
		<div class="tab-content">
			<div class="tab-pane active show" id="tabdetail" role="tabpanel" aria-labelledby="tabdetail">
				<table class="table table-responsive table-bordered" id="tableasal">
					<thead style="background-color: grey; color : #ffff">
						<tr>
							<th rowspan="2" class="text-center">No. </th>
							<th rowspan="2">Kode STT</th>
							<th rowspan="2">Pengirim</th>
							<th rowspan="2">Penerima</th>
                            <th colspan="2" class="text-center">Koli</th>
							<th rowspan="2">Kg</th>
							<th rowspan="2">Kgv</th>
							<th rowspan="2">M3</th>
                            <th rowspan="2">Omset</th>   
							<th rowspan="2">Bayar</th>
							<th rowspan="2">Piutang</th>                         
							<th rowspan="2">Action</th>
						</tr>
                        <tr>
                            <th>Total</th>
							<th>Muat</th>
                        </tr>
					</thead>
					<tbody>
						@php
						$total = 0;
						$t_koli = 0;
                        $t_muat = 0;
                        $t_rata = 0;
                        $t_brt = 0;
						$t_vol = 0;
						$t_kbk = 0;
                        $t_omset = 0;
                        $t_piutang = 0;
                        $t_bayar = 0;
						@endphp
						@foreach($detail as $key => $value)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>
								<a href="javascript:void(0)" onclick="myFunction('{{ $value->id_stt }}')" class="class-edit">
									{{ strtoupper($value->kode_stt) }}
								</a>
								<br>
								{{ dateindo($value->tgl_masuk) }}
							</td>
							<td>{{ strtoupper($value->pengirim_nm)}}
								<br>
								<span class="label label-inline label-light-primary font-weight-bold" style="font-size: 10px">
                                    {{$value->pengirim_telp}}
                                </span>
								<br>
								<span style="font-size: 12px">{{$value->pengirim_alm}}</span>
							</td>					
							<td>
								{{ strtoupper($value->penerima_nm)}}
								<br>
								<span class="label label-inline label-light-primary font-weight-bold" style="font-size: 10px">
									{{$value->penerima_telp}}
								</span>
								<br>
								<span style="font-size: 12px">{{$value->penerima_alm}}</span>
							</td>
							<td class="text-right">{{ $value->n_koli }}</td>
							<td class="text-right">{{ $value->muat }}</td>
							@php
                            $rata = divnum($value->n_hrg_bruto, $value->muat);
                            $t_rata += $rata;
							$t_brt += $value->n_berat;
							$t_vol += $value->n_volume;
							$t_kbk += $value->n_kubik;
                            $t_omset += $value->c_total;
                            $t_koli += $value->n_koli;
                            $t_muat += $value->muat;
							$t_bayar += $value->x_n_bayar;
							$t_piutang += ($value->x_n_bayar == 0 || $value->x_n_bayar == null) ? ($value->c_total) : ($value->x_n_piut)
							@endphp
							<td class="text-right">{{ $value->n_berat }}</td> 
							<td class="text-right">{{ $value->n_volume }}</td> 
							<td class="text-right">{{ $value->n_kubik }}</td> 
                            <td class="text-right">{{ tonumber($value->c_total) }}</td>
							<td class="text-right">{{ toNumber($value->x_n_bayar) }}</td> 
                            <td class="text-right">{{ ($value->x_n_bayar == 0 || $value->x_n_bayar == null) ? toNumber($value->c_total) : toNumber($value->x_n_piut) }}</td> 
                            <td>
                                <div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item" href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/'.$value->id_stt.'/detailstt' }}">
											<span><i class="fa fa-eye"></i></span> Detail
										</a>
										@if($data->ata==null and $data->atd==null)
										<input type="hidden" name="kode_dm" id="kode_dm" value="{{ Request::segment(2) }}">
										<a href="#" class="dropdown-item" type="button" onclick="CheckDelete('{{ url('dmtrucking/'.$value->id_stt.'/deletestt') }}')">
											<span><i class="fa fa-times"></i></span> Hapus
										</a>
										@endif
									</div>
								</div>
                            </td>
						</tr>
						@endforeach
						<tr>
							<td colspan="4" class="text-right"><h6><b>TOTAL :</b></h6></td>
                            <td class="text-right"><h6>{{ $t_koli }}</h6></td>
                            <td class="text-right"><h6>{{ $t_muat }}</h6></td>
							<td class="text-right"><h6>{{ $t_brt }}</h6></td>
							<td class="text-right"><h6>{{ $t_vol }}</h6></td>
							<td class="text-right"><h6>{{ $t_kbk }}</h6></td>
                            <td class="text-right"><h6>{{ toNumber($t_omset) }}</h6></td>
							<td class="text-right"><h6>{{ toNumber($t_bayar) }}</h6></td>
							<td class="text-right"><h6>{{ toNumber($t_piutang) }}</h6></td>
                        </tr>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="tabumum" role="tabpanel" aria-labelledby="tabumum">
				<table class="table table-responsive table-striped" style="margin-top: 5px">
					<thead style="background-color: rgb(151, 151, 151); color:#fff">
						<tr>
							<th>No</th>
							<th>Nomor STT</th>
							<th>Biaya</th>
							<th>Kelompok</th>
							<th>Nominal</th>
							<th>Keterangan</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@php
						$total = 0;
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
								{{ toRupiah($value->nominal) }}
								@php
								$total += $value->nominal;
								@endphp
							</td>
							<td>
								@if(isset($value->keterangan))
								{{$value->keterangan}}
								@endif
							</td>
							<td>
								@if($value->is_lunas==true)
								-
								@else
								<button class="btn btn-sm btn-warning" type="button" onclick="goEdit('{{ $value->id_pro_bi }}', '{{ $value->id_biaya_grup }}', '{{ $value->id_stt}}',  '{{ $value->nominal }}', '{{ $value->tgl_posting }}','{{ $value->keterangan }}', '{{ $value->id_jenis }}')" data-toggle="tooltip" data-placement="bottom" title="Edit">
									<span><i class="fa fa-edit"></i></span> Edit
								</button>
								
								<a href="#" class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url('dmtrucking').'/'.$value->id_pro_bi.'/deleteproyeksi' }}')">
									<span><i class="fa fa-times"></i></span> Hapus
								</a>
								@endif
							</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
			</div>
		</div>
	</form>
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

<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ url(Request::segment(1)) }}" id="form-data">
				<input type="hidden" name="_method" id="_method" value="PUT">
				@csrf
				<div class="modal-body">
					<div class="row">
						
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
						
						<input type="hidden" id="id_jenis" name="id_jenis" value="1" />
						
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

@endsection

@section('script')
<script>
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
	
	function CheckSampai(id = ""){
		$("#id_stt").val(id);
		$("#modal-end").modal('show');
	}
	
	function CheckTerusan(id = ""){
		$("#t_id_stt").val(id);
		$("#modal-terusan").modal('show');
	}
	
	var idstatus = "";
	function CheckStatus(id = "", id_status = null){
		idstatus = id;
		$("#id_status").val(id_status);
		$("#id_dmtb").val(idstatus);
		$('#form-status').attr('action', '{{ url('dmtiba/updatestatusdm') }}/'+idstatus);
		$("#modal-status").modal('show');
	}
	
	function UpdateStt(id, id_status = ""){
		var url = "{{ url('dmtiba') }}/"+id+"/updatestt";
		$("#id_dmtb").val(id);
		$("#form-stt-stat").attr("action", url);
		$("#modal-stt-stat").modal('show');
	}
	
	$('#id_kota').select2({
		minimumInputLength: 3,
		placeholder: 'Cari Kota ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getKota') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_kota').empty();
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
	
	$('#id_kota_stt').select2({
		minimumInputLength: 3,
		placeholder: 'Cari Kota ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getKota') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_kota_stt').empty();
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
	
	$('#id_kota_handling').select2({
		minimumInputLength: 3,
		placeholder: 'Cari Kota ....',
		allowClear: true,
		ajax: {
			url: '{{ url('getKota') }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_kota_handling').empty();
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

	function refresh(){
		$("#_method").val("POST");
		$("#form-data").attr("action", "{{ url("dmtrucking/saveproyeksi/".Request::segment(2)) }}");
		$("#nominal").val("");
		$("#b_id_stt").val("").trigger("change");
		$("#keterangan").text("");
		$("#id_biaya_grup").val("").trigger('change');
		$("#modal-create").modal("show");
		$("#tgl_posting").val('{{ date("Y-m-d") }}');
	}
	
	function goEdit(id, id_group, id_stt, nominal, tgl_posting,keterangan){
		$("#_method").val("PUT");
		$("#nominal").val(nominal);
		$("#b_id_stt").val(id_stt).trigger("change");
		$("#keterangan").text(keterangan);
		$("#id_biaya_grup").val(id_group).trigger('change');
		$("#form-data").attr("action", "{{ url('dmtrucking/updateproyeksi/') }}/"+id);
		$("#modal-create").modal("show");
		$("#tgl_posting").val(tgl_posting);
	}
	
	$("#b_id_stt").select2(
	{
		dropdownParent: $('#modal-create')
	}
	);
	
	$("#id_biaya_grup").select2({
		dropdownParent: $('#modal-create')}
	);
	
	var $rows = $('#tableasal tr');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            
            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
</script>
@endsection

