<div class="row">
	<div class="col-md-4">
		<h4 style="margin-left: 3%"><i class="fa fa-money"></i>
			<b>Data Biaya Invoice Handling</b>
		</h4>
	</div>

	<div class="col-md-8 text-right">
		@if($data->id_status == 1)
		<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-send"><span> <i class="fa fa-send"> </i> </span> Kirim Invoice</button>
		<a href="{{ url(Request::segment(1)."/".Request::segment(2)."/proyeksi") }}" class="btn btn-sm btn-primary"><span> <i class="fa fa-plus"> </i> </span> Tambah Biaya</a>
		@else
		@if ($data->id_status  ==  2)
		<a href="{{ url(Request::segment(1)."/".Request::segment(2)."/batalkirim") }}" class="btn btn-sm btn-danger"><span> <i class="fa fa-times"> </i> </span> Batalkan Pengiriman</a>
		@endif
		
		<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-konfirmasi"><span> <i class="fa fa-check"> </i> </span> Terima Bayar</button>
		@endif
	</div>

	<div class="col-md-12" style="margin-top:5px">
		<table class="table table-responsive table-bordered" id="tableasal">
			<thead style="background-color : #ececec">
				<tr>
					<th rowspan="2">Group Biaya</th>
					<th rowspan="2">Kelompok</th>
					<th rowspan="2">Nomor Handling</th>
					<th rowspan="2">Nomor DM</th>
					<th rowspan="2">Nomor STT</th>
					<th colspan="2" class="text-center">Nama Akun (AC4)</th>
					<th colspan="3" class="text-center">Nominal</th>
					<th rowspan="2">Is Lunas ?</th>
					<th rowspan="2">
						@if($data->id_status == 1)
						Action
						@else
						<input type="checkbox" value="1" id="c_all" name="c_all"> Semua
						@endif
					</th>
				</tr>
				<tr>
					<td>Pendapatan</td>
					<td>Piutang</td>
					<td>Total</td>
					<td>Bayar</td>
					<td>Sisa</td>
				</tr>
			</thead>
			@if($data->id_status > 1)
			<form method="POST" action="{{ url(Request::segment(1)."/".Request::segment(2)."/konfirmasibayar") }}" enctype="multipart/form-data" id="form-konfirmasibayar">
				<input type="hidden" name="_method" id="_method" value="PUT"/> 
				@csrf
				@endif
				
				<tbody>
					@foreach($biaya as $key => $value)
					<tr>
						<td onclick="goPopUp('{{ $value->id_biaya_pend }}')"><a href="#" style="text-decoration: none">{{ $value->nm_biaya_grup }}</a></td>
						<td>{{ $value->klp }}</td>
						<td>{{ $value->kode_handling }}</td>
						<td>{{ $value->kode_dm }}</td>
						<td>{{ $value->kode_stt }}</td>
						<td>@if(isset($akun[$value->pendapatan])){{ $akun[$value->pendapatan]->nama }}@endif</td>
						<td>@if(isset($akun[$value->piutang])){{ $akun[$value->piutang]->nama }}@endif</td>
						<td>{{ torupiah($value->nominal) }}</td>
						<td>{{ torupiah($value->dibayar) }}</td>
						<td>{{ torupiah($value->nominal-$value->dibayar) }}</td>
						<td>
							@if($value->is_lunas==1)
							<i class="fa fa-check" style="color: green"></i>
							@else
							<i class="fa fa-times" style="color: red"></i>
							@endif
						</td>
						<td>
							@php
							$sisa = $value->nominal-$value->dibayar;
							@endphp
							@if($data->id_status == 1)
							<form action="{{ url(Request::segment(1)).'/'.$value->id_biaya.'/deletebiaya' }}" method="post" id="form-delete{{ $value->id_biaya }}" name="form-delete{{ $value->id_biaya }}">
								{{ method_field("DELETE") }}
								@csrf
								<button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#modal-create"  onclick="setMethod('{{ $value->id_biaya }}','{{ $value->id_dm }}', '{{ $value->kode_dm }}', '{{ $value->kode_stt }}', '{{ $value->nm_biaya_grup }}', '{{ $value->nominal }}')" data-toggle="tooltip" data-placement="bottom" title="Edit">
									<span><i class="fa fa-edit"></i></span>
								</button>
								
								@if($value->is_default!=true)
								<button class="btn btn-sm btn-danger" type="button" onclick="CheckDelete('{{ $value->id_biaya }}')" data-toggle="tooltip" data-placement="bottom" title="Delete">
									<span><i class="fa fa-times"></i></span>
								</button>
								@endif
							</form>
							@else
							@if($value->dibayar > 0 and $value->is_lunas != true)
							<input type="checkbox" name="c_pro[]" id="c_pro{{ $value->id_biaya_pend }}" class="form-control c_pro" value="{{  $value->id_biaya_pend }}">
							@endif
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
				@if($data->id_status > 1)
			
			@endif
		</table>
	</div>
</div>

<div class="modal fade" id="modal-konfirmasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<label class="text-center"><h5>Apakah Anda Ingin Bayar Invoice ?</h5></label>
				<hr>
				<div class="form-group text-left">
					<label for="ac4_k" >Perkiraan Akun<span class="span-required"> *</span></label> 
					<select class="form-control" id="ac4_k" name="ac4_k"> 
						<option value="1"> -- Pilih Akun --</option>
						@foreach ($ac as $key => $value)
						<option value="{{ $value->id_ac }}">{{ strtoupper($value->nm_ac) }}</option>
						@endforeach
					</select>
					@if ($errors->has('ac4_k'))
					<label style="color: red">
						{{ $errors->first('ac4_k') }}
					</label>
					@endif  
				</div>
				<hr>
				<div class="text-right">
					<button type="button" class="btn btn-sm btn-success" onclick="goSubmit()" data-toggle="tooltip" data-placement="bottom" title="Kirim"> <i class="fa fa-send"> </i> Kirim</button>
					<button type="button" class="btn btn-sm btn-danger"  data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			
			<form method="POST" action="#" id="form-data">
				<input type="hidden" name="id_biaya" id="id_biaya" value="">
				<input type="hidden" name="id_dm" id="id_dm" value="">
				<input type="hidden" name="_method" id="_method" value="">
				@csrf
				<div class="modal-body">
					<div class="row">
						
						<div class="col-md-12" style="padding-top: 10px">
							<label for="kode_dm">
								<b>Nomor DM</b> <span class="span-required">* </span>
							</label>
							
							<input class="form-control m-input m-input--square" id="kode_dm" name="kode_dm" type="text" required readonly />
						</div>
						
						<div class="col-md-12" style="padding-top: 10px">
							<label for="kode_stt">
								<b>Nomor STT</b> <span class="span-required"></span>
							</label>
							
							<input class="form-control m-input m-input--square" id="kode_stt" name="kode_stt" type="text" required readonly />
						</div>
						
						<div class="col-md-12" style="padding-top: 10px">
							<label for="id_biaya_grup">
								<b>Group Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="id_biaya_grup" name="id_biaya_grup" type="text" readonly required />
						</div>
						
						<div class="col-md-12" style="padding-top: 10px">
							<label for="nominal">
								<b>Nominal Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="nominal" name="nominal" type="number" required />
							
							@if ($errors->has('nominal'))
							<label style="color: red">
								{{ $errors->first('nominal') }}
							</label>
							@endif
						</div>
						
						<div class="col-md-12 text-right" style="padding-top: 15px">
							<button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Simpan"> <i class="fa fa-save"> </i> Simpan</button>
							<button type="button" class="btn btn-sm btn-danger" onclick="refresh()" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
						</div>
						
					</div>
				</div>
			</form>
			
		</div>
	</div>
</div>

<div class="modal fade" id="modal-send" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ url(Request::segment(1)."/".Request::segment(2)."/kirim") }}" id="form-data" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<label class="text-center"><h5>Apakah Anda Ingin Mengirim Invoice {{ strtoupper($data->kode_invoice) }} <br> <br> Ke {{ strtoupper($data->nm_perush) }} ?</h5></label>
					<hr>
					<div class="text-right">
						<button type="submit" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" title="Kirim"> <i class="fa fa-send"> </i> Kirim</button>
						<button type="button" class="btn btn-sm btn-danger" onclick="refresh()" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Detail Pembayaran Biaya Invoice Handling</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-responsive table-bordered" id="table_show">
					<thead style="background-color : #ececec">
						<th>Group Biaya</th>
						<th>Tgl. Bayar</th>
						<th>Nominal Bayar</th>
					</thead>
					<tbody id="body_show">
						<tr>
							<td colspan="4" class="text-center">Data Kosong</td>
						</tr>
					</tbody>
				</table>
				
				<div class="form-group text-right">
					<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> <i class=" fa fa-times"> </i> Tutup</span></button>
				</div>
				
			</div>
		</div>
	</div>
</div>

@section('script')
<script>
	
	function goPopUp(id){
		$("#table_show").closest('tr').remove();
		$("#modal-show").modal("show");
		$.ajax({
			type: "GET",
			dataType: "json",
			url: "{{ url(Request::segment(1)) }}/"+id+"/showbayar",
			success: function(data) {
				$("#table_show > tbody").html(data);
			},
		});
	}
	
	function goSubmit(){
		$("#form-konfirmasibayar").submit();
	}

	function setMethod(id_biaya, id_dm, kode_dm, id_stt, id_biaya_grup, nominal) {
		$("#id_biaya").val(id_biaya);
		$("#id_dm").val(id_dm);
		$("#kode_dm").val(kode_dm);
		$("#kode_stt").val(id_stt);
		$("#id_biaya_grup").val(id_biaya_grup);
		$("#nominal").val(nominal);
		$("#_method").val("PUT");
		$("#form-data").attr("action", "{{ url(Request::segment(1)) }}"+"/"+id_biaya+"/updatebiaya");
	}
	
	function refresh(){
		$("#form-data").attr("action", "#");
		$("#id_biaya").val();
		$("#kode_dm").val();
		$("#id_dm").val();
		$("#kode_stt").val();
		$("#id_biaya_grup").val();
		$("#nominal").val();
	}
	
	$(function(){
		$('#c_all').change(function()
		{
			if($(this).is(':checked')) {
				$(".c_pro").prop("checked", true);
			}else{   
				$(".c_pro").prop("checked", false);
			}
		});
	});
</script>
@endsection