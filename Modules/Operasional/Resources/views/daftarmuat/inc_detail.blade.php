
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
							<th>No. </th>
							<th>No Resi</th>
							<th>Pengirim</th>
							<th>Penerima</th>
							<th>Total Koli</th>
							<th>Koli Termuat</th>
							<th>Status</th>
							<th>Kg</th>
							<th>Kgv</th>
							<th>M3</th>
							@if(Request::segment(1)!="dmtiba")
							<th>Omzet</th>
							<th>Bayar</th>
							<th>Sisa</th>
							@endif
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@php
						$total = 0;
						$total = 0;
						$t_bayar = 0;
						$t_sisa = 0;
						$t_brt = 0;
						$t_vol = 0;
						$t_kbk = 0;
						@endphp
						@foreach($detail as $key => $value)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>
								<a href="#" onclick="myFunction('{{ $value->id_stt }}')" class="class-edit">
									{{ strtoupper($value->kode_stt) }}
								</a>
								<br>
								{{ dateindo($value->tgl_masuk) }}
							</td>
							<td>{{ strtoupper($value->pengirim_nm)}}
								<br>
								<span class="label label-inline label-light-primary font-weight-bold">{{$value->pengirim_telp}}</span>
								<br>
								<span >{{$value->pengirim_alm}}</span>
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
							<td class="text-center">
								@if(isset($sttstat[$value->id_status]->nm_ord_stt_stat))
								{{ strtoupper($sttstat[$value->id_status]->nm_ord_stt_stat) }}
								@endif
							</td>
							@php
							$t_brt += $value->n_berat;
							$t_vol += $value->n_volume;
							$t_kbk += $value->n_kubik;
							@endphp
							<td class="text-right">{{ tonumber($value->n_berat) }}</td> 
							<td class="text-right">{{ tonumber($value->n_volume) }}</td> 
							<td class="text-right">{{ tonumber($value->n_kubik) }}</td> 
							@if(Request::segment(1)!="dmtiba")
							@php
							$c_total = $value->n_tarif_koli * $value->muat;
							$total += $c_total;
							$t_bayar += $value->x_n_bayar;
							$t_sisa += $value->x_n_piut;
							@endphp
							<td class="text-right">{{ toRupiah($c_total) }}</td> 
							<td class="text-right">{{ toRupiah($value->x_n_bayar) }}</td> 
							<td class="text-right">{{ toRupiah($value->x_n_piut) }}</td> 
							@endif
							<td class="text-center">
								@if(Request::segment(1)=="dmtiba")
								@if($data->id_status>3 and $value->id_status < 6 and $value->is_import != true and $value->is_penerusan != 1)
								<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
										@if($data->is_vendor == null)
										<a href="#" class="dropdown-item" type="button" onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
											<span><i class="fa fa-check"></i></span> Ambil Gudang
										</a>
										@else
										<a href="#" class="dropdown-item" type="button" onclick="ShowModal('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
											<span><i class="fa fa-download"></i></span> Import
										</a>
										@endif
										<a href="#" class="dropdown-item" type="button" onclick="CheckTerusan('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
											<span><i class="fa fa-truck"></i></span> Ambil Gudang Vendor Penerusan
										</a>
									</div>
								</div>
								@endif
								@elseif(Request::segment(1)=="dmkota" and $value->id_status < 7 )
								<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
										
										@if($value->id_status == 6)
										<a href="#" class="dropdown-item" type="button" onclick="CheckSampai('{{ $value->id_stt }}')" data-toggle="tooltip" data-placement="bottom" title="Ambil Stt Di Gudang">
											<span><i class="fa fa-check"></i></span> Diterima
										</a>
										@endif
										
										@if($data->ata==null and $data->atd==null and $data->id_status == 1)
										<input type="hidden" name="kode_dm" id="kode_dm" value="{{ Request::segment(2) }}">
										<a class="dropdown-item" href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/'.$value->id_stt.'/detailstt' }}">
											<span><i class="fa fa-eye"></i></span> Detail
										</a>
										<a href="#" class="dropdown-item" type="button" onclick="CheckDelete('{{ url('dmtrucking/'.$value->id_stt.'/deletestt') }}')">
											<span><i class="fa fa-times"></i></span> Hapus
										</a>
										@endif
									</div>
								</div>
								@else
								@if(Request::segment(3)=="show" and isset($data->id_status) and $data->id_status==1 and $data->id_perush_dr==Session("perusahaan")["id_perush"])
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
								@endif
								@endif
							</td>
						</tr>
						@endforeach
						@if(Request::segment(1)!="dmtiba")
						<tr>
							<td colspan="7" class="text-right"><h6><b>TOTAL :</b></h6></td>
							<td class="text-right"><h6>{{ $t_brt }}</h6></td>
							<td class="text-right"><h6>{{ $t_vol }}</h6></td>
							<td class="text-right"><h6>{{ $t_kbk }}</h6></td>
							<td class="text-right"><h6>{{ torupiah($total) }}</h6></td>
							<td class="text-right"><h6>{{ torupiah($t_bayar) }}</h6></td>
							<td class="text-right"><h6>{{ torupiah($t_sisa) }}</h6></td>
						</tr>
						@endif
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

@if((Request::segment(1)=="dmvendor" and isset($data->id_ven) and $data->id_ven != null) or Request::segment(1)=="dmkota" or Request::segment(1)=="dmtiba")
<div class="modal fade" id="modal-end" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 7%; font-weight: bold;"> <i class="fa fa-truck"></i>  
					@if(Request::segment(1)=="dmtiba") 
					Barang diambil di Gudang ? 
					@else Apakah Anda Barang Sudah Sampai ? 
					@endif
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body" style="margin-top: -2%">
				<form method="POST" action="{{ url(Request::segment(1)."/sampai") }}" enctype="multipart/form-data" id="form-end">
					@csrf
					
					@if((Request::segment(1)=="dmvendor" and isset($data->id_ven) and $data->id_ven != null) or Request::segment(1)=="dmkota" and $data->id_status >= 3)
					<label style="font-weight : bold ">
						Kota Posisi Barang <span class="text-danger"> *</span>
					</label>
					<select class="form-control" id="id_kota_handling" name="id_kota_handling"></select>
					<br>
					<br>
					@endif
					
					<h6>Foto Dokumentasi  1<span class="span-required"> * </span></h6>
					<input class="form-control" name="dok1" id="dok1" required type="file" />
					<img id="img1" name="img1" src="" >  
					@if ($errors->has('dok1'))
					<label style="color: red">
						{{ $errors->first('dok1') }}
					</label>
					@endif  
					
					<br>
					
					<h6>Foto Dokumentasi  2<span class="span-required"> * </span></h6>
					<input class="form-control" name="dok2" id="dok2" required type="file" />
					<img id="img2" name="img2" src="" >
					@if ($errors->has('dok1'))
					<label style="color: red">
						{{ $errors->first('dok1') }}
					</label>
					@endif
					
					<input class="form-control" name="id_stt" id="id_stt" required type="hidden" />
					@if ($errors->has('id_stt'))
					<label style="color: red">
						{{ $errors->first('id_stt') }}
					</label>
					@endif
					
					<h6>Keterangan<span class="span-required"> * </span></h6>
					<textarea class="form-control" name="keterangan" id="keterangan" maxlength="100" placeholder="Masukan Keterangan ..."></textarea>
					@if ($errors->has('keterangan'))
					<label style="color: red">
						{{ $errors->first('keterangan') }}
					</label>
					@endif
					
					<br>
					<h6>Nama Penerima<span class="span-required"> * </span></h6>
					<input type="text" class="form-control" name="nm_penerima" id="nm_penerima" maxlength="100" placeholder="Masukan Nama Penerima ..." />
					@if ($errors->has('nm_penerima'))
					<label style="color: red">
						{{ $errors->first('nm_penerima') }}
					</label>
					@endif
					<br>
					<div class="text-right">
						<button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" >Sampai</button>
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-terusan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="margin-left: 7%; font-weight: bold;"> 
					Apakah Barang Diteruskan Ke Vendor ?
				</h3>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{ url(Request::segment(1)."/penerusan") }}" enctype="multipart/form-data" id="form-penrusan">
					@csrf
					<div class="text-right">
						<input class="form-control" name="t_id_stt" id="t_id_stt" required type="hidden" />
						<button type="submit" class="btn btn-md btn-success" id="modal-btn-si" >Iya</button>
						<button type="button" class="btn btn-md btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Tidak</span></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif

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
	
</script>
@endsection