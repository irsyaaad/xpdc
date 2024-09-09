@extends('template.document')

@section('data')
<style>
	.txt-style{
		font-size: 12px;
	}
	
	thead{
		font-size: 16px;
	}
</style>
@if(isset($data) and $data!=null)
<div class="row">
	<div class="col-md-11 text-right">
		@if(Request::segment(3)!="proyeksi")
		@if($data->id_status < 6)
		<a href="{{ url(Request::segment(1)."/import"."/".$data->id_handling) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="Import Data"><i class="fa fa-download"></i>    Import Stt</a>
		@endif
		<a href="{{ url(Request::segment(1).'/'.Request::segment(2).'/proyeksi') }}" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Biaya Handling">
			<i class="fa fa-money"></i> Biaya
		</a>
		
		@php
		$a_status = $data->id_status;
		$a_status_plus = (Int)$a_status+1;
		if($a_status_plus == 8){
			$a_status_plus = $a_status;
		}
		@endphp
		
		@if(count($end)!=0 or $a_status_plus < 7)
		<div class="dropdown d-inline-block">
			<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-bell"> </i> Update Status
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a class="dropdown-item" href="#"  data-toggle="modal" data-target="#exampleModal" onclick="CheckStatus('{{ $data->id_handling }}')" data-toggle="tooltip" data-placement="bottom" title="Handling Berangkat">
					<i class="fa fa-truck"></i> 
					@if(isset($data->status->nm_status)) 
					{{ $status[$a_status_plus]->nm_status }} 
					@endif
				</a>
			</div>
		</div>
		@endif
		
		<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Kembali"><i class="fa fa-reply"></i>    Kembali</a>
		@else
		<a href="{{ url(Request::segment(1).'/'.Request::segment(2).'/show') }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="Kembali"><i class="fa fa-reply"></i>    Kembali</a>
		@endif
	</div>
</div>

@if($data->id_status == 6)
@include('operasional::handling.confirmend')
@endif

@if($data->id_status < 7)
@include('operasional::handling.confirm')
@endif

@endif

<div class="row" style="padding:10px">
	<table class="table table-responsive">
		<tr>
			<td>
				No. Manifest Kurir : <b> @if(isset($data->kode_handling)){{ strtoupper($data->kode_handling) }} @endif </b>
			</td>
			<td>
				Perusahaan : <b> @if(isset($data->perusahaan->nm_perush)){{ strtoupper($data->perusahaan->nm_perush) }}@endif</b>
			</td>
			<td>
				Kota Asal : <b> @if(isset($data->asal->nama_wil)){{ strtoupper($data->asal->nama_wil) }}@endif</b>
			</td>
			<td>
				Kota Tujuan : <b> @if(isset($data->region_tuju)){{ strtoupper($data->tujuan->nama_wil) }}@endif</b>
			</td>
		</tr>
		<tr>
			@if(isset($data->vendor->nm_ven))
			<td>
				Vendor Tujuan : <b>{{ strtoupper($data->vendor->nm_ven) }}</b>
			</td>
			<td>
				Nama Sopir / Armada : <b>
					@if(isset($data->sopir->nm_sopir))
					{{ strtoupper($data->sopir->nm_sopir) }}
					@endif
					@if(isset($data->armada->nm_armada))
					<br>
					>
					{{ strtoupper($data->armada->nm_armada." ( ".$data->armada->no_plat." )") }}
					@endif
				</b>
			</td>
			@else
			<td>
				Nama Sopir : <b>@if(isset($data->sopir->nm_sopir)){{ strtoupper($data->sopir->nm_sopir) }}@endif</b>
			</td>
			<td>
				Armada : <b> @if(isset($data->armada->nm_armada)){{ strtoupper($data->armada->nm_armada) }}@endif</b>
			</td>
			@endif
			<td>
				Penganggung Jawab : <b> @if(isset($data->user->nm_user)){{ strtoupper($data->user->nm_user) }}@endif</b>
			</td>
			<td>
				Status : <b> @if(isset($data->status_handling->nm_status)){{ strtoupper($data->status_handling->nm_status) }}@endif</b>
			</td>
		</tr>
		<tr>
			<td>
				Jumlah RESI : 
				@if(isset($detail))
				{{ count($detail) }}
				@endif
			</td>
			<td>
				Est. Pendapatan : 
				{{ toRupiah($data->c_total) }}
			</td>
			<td>
				Total Biaya : 
				{{ toRupiah($data->c_biaya) }}
			</td>
			<td>
				Est. Laba :
				@php
				$persen = 0;
				if(isset($data->c_total) and isset($data->c_biaya) and $data->c_total!=0 and $data->c_biaya!=0){
					$persen = (Double)($data->c_total-$data->c_biaya);
					$hasil = ($persen/$data->c_total)*100;
				}else{
					$hasil = 0;
				}
				@endphp
				{{ toRupiah($persen) }}
				@if($hasil>0)
				
				<label style="color : green">
					{{ " (".strtoupper(number_format($hasil, 0, ',', '.'))." %)" }}
				</label>
				@else
				<label style="color : red">
					{{ " (".strtoupper(number_format($hasil, 0, ',', '.'))." %)" }}
				</label>
			@endif
			</td>
		</tr>
		<tr>
			<td>
				Tgl Berangkat : 
				@if(isset($data->tgl_berangkat))
				<b>{{ daydate($data->tgl_berangkat).", ".dateindo($data->tgl_berangkat) }}</b>
				@endif
			</td>
			<td>
				Jam Berangkat : 
				@if($data->waktu_berangkat)
				<b> {{ date("H:i:s", strtotime($data->waktu_berangkat))." WIB" }}</b>
				@endif
			</td>
			<td>
				Tgl Selesai :
				@if(isset($data->tgl_selesai))
				<b>{{ daydate($data->tgl_selesai).", ".dateindo($data->tgl_selesai) }}</b>
				@endif
			</td>
			<td>
				Jam Selesai : 
				@if($data->waktu_selesai)
				<b> {{ date("H:i:s", strtotime($data->waktu_selesai))." WIB" }}</b>
				@endif
			</td>
		</tr>
		<tr>
			<td>
				KM Berangkat : <b>{{ $data->km_awal }}</b>
			</td>
			<td>
				KM Selesai : <b>{{ $data->km_akhir }}</b>
			</td>
			<td>
				Ambil Gudang : 
				@if(isset($data->ambil_gudang) and $data->ambil_gudang=="1")
				<label class="badge badge-md badge-success">Ambil Gudang</label>
				@else
				<label class="badge badge-md badge-danger">Tidak</label>
				@endif
			</td>
			<td>
				Keterangan : {{ $data->keterangan }}
			</td>
		</tr>
	</table>
</div>

@if(Request::segment(3)=="show")

<div class="row" >
	<h4 style="margin-left: 3%"><i class="fa fa-thumb-tack"></i>
		<b>Data RESI</b>
	</h4>
	
	<div class="col-md-12" >
		<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
			@csrf
			<input type="hidden" name="_method" value="GET">
			<table class="table table-responsive table-bordered">
				<thead style="background-color : #ececec">
					<tr>
						<th rowspan="2">No. </th>
						<th rowspan="2">No. RESI</th>
						<th rowspan="2">No. MANIFEST
							<br> > Perusahaan Asal
						</th>
						<th rowspan="2">Penerima</th>
						<th rowspan="2">Status</th>
						<th colspan="4"  class="text-center">Jumlah</th>
						<th rowspan="2">Est. Laba</th>
						<th rowspan="2">Action</th>
					</tr>
					<tr>
						<th>Koli</th>
						<th>Kg</th>
						<th>Kgv</th>
						<th>M3</th>
					</tr>
				</thead>
				<tbody>
					@php
					$total = 0;
					@endphp
					
					@foreach($detail as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ strtoupper($value->kode_stt) }}
							<br> >
							<label style="font-size: 12px">{{ dateindo($value->tgl_masuk) }}</label>
						</td>
						<td>
							{{ strtoupper($value->kode_dm) }}
							<br> >
							<label style="font-size: 12px">{{ strtoupper($value->perush_asal) }}</label>
						</td>
						<td>{{ strtoupper($value->penerima_nm).PHP_EOL }}
							<br> <label class="txt-style">{{ $value->penerima_alm.PHP_EOL }}</label>
							<br> <label class="txt-style">{{ $value->nama_wil." - ".$value->prov." ".$value->kab }}</label>
							<br> <label class="txt-style">{{ $value->penerima_telp }}</label>
						</td>
						<td>
							{{ $value->nm_status ?? '' }}
						</td>
						<td>
							{{ $value->n_koli }}
						</td>
						<td>
							{{ $value->n_berat }}
						</td>
						<td>
							{{ $value->n_volume }}
						</td>
						<td>
							{{ $value->n_kubik }}
						</td>
						<td class="text-right">
							{{ torupiah($value->n_total) }}
							@php
							$total += $value->n_total;
							@endphp
						</td>
						<td class="text-center">
							@if($value->id_status == 6)
							<a href="#" class="btn btn-sm btn-success" onclick="CheckSampai('{{ $value->id_detail }}')" data-toggle="tooltip" data-placement="bottom" title="Barang Sampai Tujuan"><i class="fa fa-check"></i> </a>
							@elseif($value->id_status == 7)
							
							@else
							<button class="btn btn-sm btn-warning" type="button" onclick="CheckEdit('{{ $value->id_detail }}', '{{ $value->n_berat }}', '{{ $value->n_hrg_handling_brt }}','{{ $value->n_volume }}','{{ $value->n_hrg_handling_vol }}','{{ $value->n_kubik }}', '{{ $value->n_hrg_handling_kubik }}','{{ $value->n_borongan }}', '{{ $value->n_total }}', '{{ $value->cara_hitung }}')" data-toggle="tooltip" data-placement="bottom" title="Hapus STT">
								<span><i class="fa fa-pencil"></i></span>
							</button>
							
							<input type="hidden" name="kode_dm" id="kode_dm" value="{{ Request::segment(2) }}">

							<button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ url('dmhandling/'.$value->id_detail.'/deletestt') }}')" data-toggle="tooltip" data-placement="bottom" title="Hapus STT">
								<span><i class="fa fa-times"></i></span>
							</button>
							@endif
						</td>
					</tr>
					@endforeach
					@if($detail != null)
					<tr>
						<td colspan="9" class="text-right">
							<b>TOTAL :</b> 
						</td>
						<td class="text-right">
							{{ torupiah($total) }}
						</td>
					</tr>
					@endif
					@if($detail == null)
					<tr>
						<td colspan="11" class="text-center">
							<b>Data Kosong</b>
						</td>
					</tr>
					@endif
				</tbody>
			</table>
		</form>
	</div>
</div>

@include('operasional::handling.end')

<div class="modal fade" id="modal-konfirmasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<form method="POST" action="#" id="form-edit">
				@csrf
				<div class="modal-body">
					<label class="text-center"><h5>Apakah Anda Ingin Edit Data Ini ?</h5></label>
					<hr>
					
					<div class="row">
						<div class="col-md-4 form-group text-left">
							<label for="n_berat" >Kg<span class="span-required"> *</span></label> 
							<input class="form-control" type="number" data-decimal="5" oninput="enforceNumberValidation(this)" name="n_berat" id="n_berat" required placeholder="Masukan total berat" />
							@if ($errors->has('n_berat'))
							<label style="color: red">
								{{ $errors->first('n_berat') }}
							</label>
							@endif  
						</div>
						
						<div class="col-md-4 form-group text-left">
							<label for="n_volume" >Kgv<span class="span-required"> *</span></label> 
							<input class="form-control" type="text" data-decimal="5" oninput="enforceNumberValidation(this)" name="n_volume" id="n_volume" step="any" required placeholder="Masukan total volume" />
							@if ($errors->has('n_volume'))
							<label style="color: red">
								{{ $errors->first('n_volume') }}
							</label>
							@endif  
						</div>
						
						<div class="col-md-4 form-group text-left">
							<label for="n_kubik" >M3<span class="span-required"> *</span></label> 
							<input class="form-control" type="text" data-decimal="5" oninput="enforceNumberValidation(this)" name="n_kubik" id="n_kubik" step="any" required placeholder="Masukan total kubikasi" />
							@if ($errors->has('n_kubik'))
							<label style="color: red">
								{{ $errors->first('n_kubik') }}
							</label>
							@endif  
						</div>
						
					</div>
					
					<div class="form-group text-left">
						<label for="n_hrg_handling_brt" >Harga Handling Berat<span class="span-required"> *</span></label> 
						<input class="form-control" type="number" name="n_hrg_handling_brt" step="any" id="n_hrg_handling_brt" readonly required  />
						@if ($errors->has('n_hrg_handling_brt'))
						<label style="color: red">
							{{ $errors->first('n_hrg_handling_brt') }}
						</label>
						@endif  
					</div>
					
					<div class="form-group text-left">
						<label for="n_hrg_handling_vol" >Harga Handling Volume<span class="span-required"> *</span></label> 
						<input class="form-control" type="number" name="n_hrg_handling_vol" step="any" id="n_hrg_handling_vol" readonly required  />
						@if ($errors->has('n_hrg_handling_vol'))
						<label style="color: red">
							{{ $errors->first('n_hrg_handling_vol') }}
						</label>
						@endif  
					</div>
					
					<div class="form-group text-left">
						<label for="n_hrg_handling_kubik" >Harga Handling Kubik<span class="span-required"> *</span></label> 
						<input class="form-control" type="number" name="n_hrg_handling_kubik" step="any" id="n_hrg_handling_kubik" readonly required  />
						@if ($errors->has('n_hrg_handling_kubik'))
						<label style="color: red">
							{{ $errors->first('n_hrg_handling_kubik') }}
						</label>
						@endif  
					</div>
					
					<div class="form-group text-left">
						<label for="n_borongan" >Harga Handling Borongan<span class="span-required"></span></label> 
						<input class="form-control" type="number" name="n_borongan" step="any" id="n_borongan" maxlength="12"  />
						@if ($errors->has('n_borongan'))
						<label style="color: red">
							{{ $errors->first('n_borongan') }}
						</label>
						@endif  
					</div>
					
					<div class="form-group text-left">
						<label for="n_total" >Harga Netto<span class="span-required"> *</span></label> 
						<br>
						<label><input type="radio" value="1" id="c_hitung" name="c_hitung"> Kg</label>
						<label style="margin-left: 40px"><input type="radio" value="2" id="c_hitung" name="c_hitung"> KgV</label>
						<label style="margin-left: 40px"><input type="radio" value="3" id="c_hitung" name="c_hitung"> M3</label>
						<label style="margin-left: 40px"><input type="radio" value="4" id="c_hitung" name="c_hitung"> Borongan</label>
						
						<input class="form-control" type="number" name="n_total" id="n_total" readonly required  />
						@if ($errors->has('n_total'))
						<label style="color: red">
							{{ $errors->first('n_total') }}
						</label>
						@endif  
					</div>
					
					<hr>
					<div class="text-right">
						<button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"> <i class="fa fa-save"> </i> Simpan</button>
						<button type="button" class="btn btn-sm btn-danger"  data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

@else
@include('operasional::handling.showproyeksi')
@endif

@endsection

@section('script')
<script type="text/javascript">
	var km = 0;
	@if(Request::segment(3)=="proyeksi")
	
	function getEdit(id) {
		$.ajax({
			type: "GET",
			url: "{{ url(Request::segment(1)) }}/"+id+"/showbiaya",
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				var id_biaya = response.id_biaya;
				$("#nominal").val(response.nominal);
				$("#id_biaya_grup").val(response.id_biaya_grup);
				$("#form-proyeksi").attr("action", '{{ url(Request::segment(1)) }}/'+id_biaya+"/updatebiaya");
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	}
	
	function batal() {
		$("#form-proyeksi").attr("action", "{{ url(Request::segment(1).'/'.Request::segment(2))."/savebiaya" }}");
		$("#nominal").val("");
	}
	
	@endif
	
	@if(Request::segment(3)=="show")
	$("input[name=c_hitung][value='1']").prop('checked', true);
	
	function CheckEdit(id, brt, hrg_brt, volume, hrg_volume, kubik, hrg_kubik, borongan, total, cara){
		CountAll();
		$("#n_berat").val(brt);
		$("#n_hrg_handling_brt").val(hrg_brt);
		
		$("#n_volume").val(volume);
		$("#n_hrg_handling_vol").val(hrg_volume);
		
		$("#n_kubik").val(kubik);
		$("#n_hrg_handling_kubik").val(hrg_kubik);
		
		$("#n_borongan").val(borongan);
		$("#n_total").val(total);
		$("#form-edit").attr("action", '{{ url(Request::segment(1)) }}/'+id+"/updatestt");
		$("#modal-konfirmasi").modal("show");
		
		$('input:radio[name="c_hitung"][value="'+cara+'"]').attr('checked',true);
	}
	
	$('input[type=radio][name=c_hitung]').change(function() {
		if (this.value == '1') {
			CountAll();
		}
		else if (this.value == '2') {
			CountAll();
		}
		else if (this.value == '3') {
			CountAll();
		}
		else if (this.value == '4') {
			CountAll();
		}
	});
	
	$("#n_berat").keyup(function(){
		CountAll();
	});
	
	$("#n_borongan").keyup(function(){
		CountAll();
	});
	
	$("#n_volume").keyup(function(){
		CountAll();
	});
	
	$("#n_kubik").keyup(function(){
		CountAll();
	});
	
	function CountAll(){
		var brt = $("#n_berat").val();
		var vol = $("#n_volume").val();
		var kubik = $("#n_kubik").val();
		
		var hrg_brt = $("#n_hrg_handling_brt").val();
		var hrg_vol = $("#n_hrg_handling_vol").val();
		var hrg_kubik = $("#n_hrg_handling_kubik").val();
		
		var borongan = $("#n_borongan").val();
		
		var hitung =  $( 'input[name=c_hitung]:checked' ).val();
		total = 0;
		
		if(hitung == "1"){
			total = brt * hrg_brt;
		}else if(hitung == "2"){
			total = vol * hrg_vol;
		}else if(hitung == "3"){
			total = kubik * hrg_kubik;
		}else if(hitung == "4"){
			total = borongan;
		}
		
		$("#n_total").val(total);
	}
	
	@endif
	
	function enforceNumberValidation(ele) {
		if ($(ele).data('decimal') != null) {
			// found valid rule for decimal
			var decimal = parseInt($(ele).data('decimal')) || 0;
			var val = $(ele).val();
			if (decimal > 0) {
				var splitVal = val.split('.');
				if (splitVal.length == 2 && splitVal[1].length > decimal) {
					// user entered invalid input
					$(ele).val(splitVal[0] + '.' + splitVal[1].substr(0, decimal));
				}
			} else if (decimal == 0) {
				// do not allow decimal place
				var splitVal = val.split('.');
				if (splitVal.length > 1) {
					// user entered invalid input
					$(ele).val(splitVal[0]); // always trim everything after '.'
				}
			}
		}
	}
</script>
@endsection

