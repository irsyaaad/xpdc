@extends('template.document')

@section('data')

<div class="col-md-12" style="margin-top: -1%">
	@if(Request::segment(1)!="packingbarang")
	<div class="text-right">
		@if(Request::segment(1) =="stt")
		<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
		@else
		<a href="{{ url()->previous() }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
		@endif
		@if(Request::segment(1) =="stt")
		@if($data->is_packing == true)
		<a href="{{ url(Request::segment(1).'/'.$data->id_stt."/packing") }}" class="btn btn-sm btn-info"><i class="fa fa-cube"></i>  Packing</a>
		@endif

		@if($data->id_status>1)
		<a href="{{ url(Request::segment(1).'/'.$data->id_stt."/tracking") }}" class="btn btn-sm btn-success"><i class="fa fa-map-marker"></i> Tracking</a>
		@endif
		

		<div class="dropdown d-inline-block">
			<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			   <i class="fa fa-print"> </i> Cetak Resi
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a href="cetak_pdf" class="dropdown-item"><i class="fa fa-print" target="_blank" rel="nofollow"></i>  Cetak</a>
				<a href="cetak_tnp_tarif" class="dropdown-item"><i class="fa fa-print" target="_blank" rel="nofollow"></i>  Cetak Tanpa Tarif</a>
				<a href="label" class="dropdown-item" target="_blank" rel="nofollow"><i class="fa fa-paste"></i>  Label</a>
			</div>
		</div>
		@endif
	</div>
	@else 
	<form method="POST" action="" id="form-stt">
		<div class="row">
			<div class="form-group col-md-4" >
				<label for="id_stt">
					<b>RESI </b> <span class="span-required"> *</span>
				</label>
				
				<select class="form-control m-input m-input--square" name="id_stt" id="id_stt" required></select>
				@csrf
				@if ($errors->has('id_stt'))
				<label style="color: red">
					{{ $errors->first('id_stt') }}
				</label>
				@endif
			</div>
			
			<div class="col-md-4" style="margin-top: 2%">
				<button type="button" onclick="goShow()" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Lihat</button>
				<button type="button" onclick="goImport()" class="btn btn-success btn-sm"><i class="fa fa-download"></i> import</button>
			</div>
			<div class="col-md-4 text-right" style="margin-top: 2%">
				<a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
			</div>
		</div>
	</form>
	@endif
</div>

@if(isset($data))
<table class="table table-responsive">
	<thead>
		<tr>
			<td><h5> > Data RESI</h5></td>
		</tr>
		<tr>
			<td width="15%"><h6>No. RESI</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->kode_stt }}</td>
			
			<td width="15%"><h6>No. AWB</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->no_awb }}</td>
		</tr>
		<tr>
			<td width="15%"><h6>Tgl. Masuk</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->tgl_masuk)){{ daydate($data->tgl_masuk).", ".dateindo($data->tgl_masuk) }}@endif</td>
			
			<td width="15%"><h6>Layanan</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->layanan->nm_layanan)){{ $data->layanan->nm_layanan }}@endif</td>
		</tr>
		<tr>
			<td width="15%"><h6>Tgl. Keluar</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->tgl_keluar)){{ daydate($data->tgl_keluar).", ".dateindo($data->tgl_keluar) }}@endif</td>
			
			<td width="15%"><h6>Status</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->status->nm_ord_stt_stat)){{ $data->status->nm_ord_stt_stat }}@endif</td>
		</tr>
		<tr>
			<td width="15%"><h6>Tgl. Jatuh Tempo</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->tgl_tempo)){{ daydate($data->tgl_tempo).", ".dateindo($data->tgl_tempo) }}@endif</td>
		</tr>
		<tr>
			<td><h5> > Pengirim</h5></td>
			<td width="1%"></td>
			<td><td><h5> > Penerima</h5></td></td>
		</tr>
		<tr>
			<td width="20%"><h6>Nama Pengirim</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->pengirim_nm }}</td>
			
			<td width="20%"><h6>Nama Penerima</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->penerima_nm }}</td>
		</tr>
		<tr>
			<td width="20%"><h6>Perusahaan Pengirim</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->pengirim_perush }}</td>
			
			<td width="20%"><h6>Perusahaan Penerima</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->penerima_perush }}</td>
		</tr>
		<tr>
			<td width="20%"><h6>Telp Pengirim</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->pengirim_telp }}</td>
			
			<td width="20%"><h6>Telp Penerima</h6></td>
			<td width="2%"><b>:</b></td>
			<td>{{ $data->penerima_telp }}</td>
		</tr>
		<tr>
			<td width="20%"><h6>Alamat Pengirim</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->asal->nama_wil)){{ $data->asal->nama_wil }} @endif, {{ $data->pengirim_alm }}  -  {{ $data->pengirim_kodepos }}</td>
			
			<td width="20%"><h6>Alamat Penerima</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->tujuan->nama_wil)){{ $data->tujuan->nama_wil }}@endif, {{ $data->penerima_alm }}  -  {{ $data->penerima_kodepos }}</td>
		</tr>
		<tr>
			<td><h5> > Detail Kiriman</h5></td>
		</tr>
		
		<tr>
			<td width="15%"><h6>Tipe Kirim</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->tipekirim->nm_tipe_kirim)){{ $data->tipekirim->nm_tipe_kirim }} @endif</td>
			
			<td width="15%"><h6>Marketing</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->marketing->nm_marketing)) {{ strtoupper($data->marketing->nm_marketing) }} @endif</td>
		</tr>
		
		<tr>
			<td width="15%"><h6>Cara Bayar</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->cara->nm_cr_byr_o)){{ $data->cara->nm_cr_byr_o }}@endif</td>
			<td width="15%"><h6>Info Kirim</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->info_kirim)){{ $data->info_kirim }}@endif</td>
		</tr>
		
		@if(Request::segment(1) =="stt")
		<tr>
			<td><h5> > Detail Barang</h5></td>
		</tr>
		
		<tr>
			<td width="15%"><h6>Harga Bruto</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_hrg_bruto)){{ number_format($data->n_hrg_bruto, 2, ',', '.') }}@endif</td>
			
			<td width="15%"><h6>Harga Terusan</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_hrg_terusan)){{ number_format($data->n_hrg_terusan, 2, ',', '.') }}@endif</td>
		</tr>
		
		<tr>
			<td width="15%"><h6>PPN</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_ppn)){{ number_format($data->n_ppn, 2, ',', '.') }}@endif</td>
			
			<td width="15%"><h6>Asuransi</h6></td>
			<td width="2%"><b>:</b></td>
			<td>
				@if(isset($data->id_asuransi) and $data->id_asuransi==0)
				Tidak ada
				@elseif(isset($data->id_asuransi) and $data->id_asuransi==1)
				Ongkos Kirim <br> {{ number_format($data->n_asuransi, 2, ',', '.') }}
				@else
				Harga Barang <br> {{ number_format($data->n_asuransi, 2, ',', '.') }}
				@endif
			</td>
		</tr>
		
		<tr>
			<td width="15%"><h6>Diskon</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_diskon)){{ number_format($data->n_diskon, 2, ',', '.') }}@endif</td>
			
			<td width="15%"><h6>Biaya Materai</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_materai)){{ number_format($data->n_materai, 2, ',', '.') }}@endif</td>
		</tr>
		
		<tr>
			<td width="15%"><h6>Kg</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_berat)){{$data->n_berat }} @endif</td>
			
			<td width="15%"><h6>Kgv</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_volume)){{ $data->n_volume }} @endif</td>
		</tr>
		<tr>
			<td width="15%"><h6>M3</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_kubik)){{ $data->n_kubik }} @endif</td>

			<td width="15%"><h6>Jumlah Colly</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->n_koli)){{ $data->n_koli }}@endif</td>
		</tr>
		
		<tr>
			<td width="15%"><h6>Harga Netto</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->c_total)){{ "Rp. ".number_format($data->c_total, 2, ',', '.') }}@endif</td>

			<td width="15%"><h6>Cara Kemas</h6></td>
			<td width="2%"><b>:</b></td>
			<td>@if(isset($data->cara_kemas)){{ $data->cara_kemas }}@endif</td>
		</tr>
		
		@endif
		
	</thead>
</table>
@endif

@if(isset($data) and Request::segment(1)!="packingbarang")
<div class="row">
	<h4> > Detail Koli RESI </h4>
	<div class="col-md-12">
		<table class="table table-responsive table-striped">
			<thead style="background: grey; color:#fff ">
				<tr >
					<th>No. </th>
					<th>Info Koli</th>
					<th>Keterangan </th>
					<th>Action </th>
				</tr>
			</thead>
			<tbody>
				@foreach($detail as $key => $value)
				<tr>
					<td>{{ ($key+1) }}</td>
					<td>{{ $value->ket_koli }}</td>
					<td>{{ $value->keterangan }}</td>
					<td class="text-center">
						@if($data->id_status==1 and Request::segment(1)=="stt" and Request::segment(3)=="show")
						<form method="POST" action="{{ url('stt/deletestt').'/'.$value->id_detail }}">
							{{ method_field("DELETE") }}
							@csrf
							
							<button class="btn btn-sm btn-warning" type="button" onclick="getEdit('{{ $value->id_detail }}','{{ $value->ket_koli }}', '{{ $value->keterangan }}')">
								<span><i class="fa fa-edit"></i></span>
							</button>
							
							<button class="btn btn-sm btn-danger" type="submit">
								<span><i class="fa fa-times"></i></span>
							</button>
						</form>
						@endif
					</td>
				</tr>
				@endforeach
				
				@if($data->id_status==1 and Request::segment(1)=="stt" and Request::segment(3)=="show")
				<tr>
					<form method="POST" action="{{ url('stt/savedetail') }}" id="form-detail" name="form-detail">
						@csrf
						<td>
							<input type="hidden" name="id_stt" id="id_stt" value="{{ $data->id_stt }}">
						</td>
						<td>
							<input type="number" name="ket_koli" id="ket_koli" class="form-control m-input m-input--square" placeholder="Masukan Koli" required="required">
							
							@if ($errors->has('ket_koli'))
							<label style="color: red">
								{{ $errors->first('ket_koli') }}
							</label>
							@endif
						</td>
						<td>
							<input type="text" name="keterangan" id="keterangan" class="form-control m-input m-input--square" placeholder="Masukan Keterangan Koli" required="required">
							
							@if ($errors->has('keterangan'))
							<label style="color: red">
								{{ $errors->first('keterangan') }}
							</label>
							@endif
						</td>
						<td>
							<button class="btn btn-sm btn-success" type="submit">
								<span><i class="fa fa-save"></i></span>
							</button>
							<button class="btn btn-sm btn-danger" type="button" onclick="getBatal()">
								<span><i class="fa fa-times"></i></span>
							</button>
						</td>
					</form>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
@endif

@endsection

@section('script')
<script type="text/javascript">
console.log("cek");
	function getEdit(id, ket_koli, keterangan) {
		$("#ket_koli").val(ket_koli);
		$("#keterangan").val(keterangan);
		$("#form-detail").attr("action", "{{ url('stt/updatestt') }}/"+id);
	}
	
	function getBatal() {
		$("#ket_koli").val('');
		$("#keterangan").val('');
		
		$("#form-detail").attr("action", "{{ url('stt/savedetail') }}/");
	}
	
	$('#id_stt').select2({
		placeholder: 'Cari STT (Minimal 3 Karakter) ....',
		minimumInputLength: 3,
		allowClear: true,
		ajax: {
			url: '{{ url("getstt") }}',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_stt').empty();
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

	@if(Request::segment(1)=="packingbarang")	
		
		function goShow()
		{
			$("#form-stt").attr("action", "{{ url(Request::segment(1).'/import') }}");
			$("#form-stt").submit();
		}

		function goImport(){
			$("#form-stt").attr("action", "{{ url(Request::segment(1).'/doimport') }}");
			$("#form-stt").submit();
		}

		@if(isset($data))
		$("#id_stt").append('<option value="{{ $data->id_stt }}">{{ strtoupper($data->id_stt) }}</option>');
		@endif
	@endif

</script>
@endsection