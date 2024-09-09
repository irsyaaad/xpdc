@extends('template.document')
@section('data')
@section('style')
<style>
	.modal {
		text-align: center;
	}
	
	.modal-dialog {
		position:absolute;
		top:50% !important;
		left: 35% !important;
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

<div class="row">
	<div class="col-md-12">
		<div class="form-group m-form__group text-right">
			<a href="{{ url(Request::segment(1)).'/'.Request::segment(2).'/show' }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
		</div>
		<table class="table">
			<tr>
				<td>No. DM : <b>{{ strtoupper($data->kode_dm) }}</b></td>
				<td>Tanggal DM : <b>{{ dateindo(date_format($data->created_at, "Y-m-d")) }}</b></td>
				<td>Layanan : <b>@if(isset($data->layanan->nm_layanan)){{ strtoupper($data->layanan->nm_layanan) }}@endif</b></td>
				
			</tr>
			<tr>
				<td>Perusahaan Pengirim : <b>@if(isset($data->perush_asal->nm_perush)){{ $data->perush_asal->nm_perush }}@endif</b></td>
				@if(isset($data->vendor->nm_ven))
				<td>Vendor Tujuan : <b>@if(isset($data->vendor->nm_ven)) {{ $data->vendor->nm_ven }} @endif</b></td>
				@else
				<td>Perusahaan Tujuan : <b>@if(isset($data->perush_tujuan->nm_perush)) {{ $data->perush_tujuan->nm_perush }} @endif</b></td>
				@endif
			</tr>
			<tr>
				<td>Estimasi Pendapatan : <b>{{ "Rp. ".strtoupper(number_format($data->c_total, 0, ',', '.')) }}</b></td>
				<td>Proyeksi Biaya : <b>{{ "Rp. ".strtoupper(number_format($data->c_pro, 0, ',', '.')) }}</b></td>
			</tr>
		</table>
	</div>
	
	<div class="col-md-6">
		<h4 style="margin-left: 3%"><i class="fa fa-money"></i>
			<b>Data Biaya DM</b>
		</h4>
	</div>
	<div class="col-md-6 text-right">
		<div class="text-right">
			<a href="{{ url("dmtrucking/".$data->id_dm."/generateproyeksi") }}" class="btn btn-sm btn-success"><span><i class="fa fa-retweet"> </i></span> Generate Proyeksi</a>
			<button type="button" class="btn btn-sm btn-primary"  data-toggle="modal" data-target="#modal-create" onclick="refresh()"><span><i class="fa fa-plus"> </i></span> Tambah Biaya</button>
		</div>
	</div>
	<div class="col-md-12">
		<form method="GET" action="#" enctype="multipart/form-data" id="form-select">
			@csrf
			<input type="hidden" name="_method" value="GET">
			<table class="table table-responsive table-striped" style="margin-top: 5px">
				<thead style="background-color: rgb(151, 151, 151); color:#fff">
					<tr>
						<th>No</th>
						<th>Nomor STT</th>
						<th>Biaya</th>
						<th>Kelompok</th>
						<th>Nominal</th>
						<th>Dibayar</th>
						<th>Keterangan</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@php
					$total = 0;
					$bayar = 0;
					@endphp
					@foreach($detail as $key => $value)
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
							{{ toRupiah($value->n_bayar) }}
							@php
							$bayar += $value->n_bayar;
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
							<button class="btn btn-sm btn-warning" type="button" onclick="goEdit('{{ $value->id_pro_bi }}', '{{ $value->id_biaya_grup }}', '{{ $value->id_stt}}',  '{{ $value->nominal }}', '{{ $value->tgl_posting }}','{{ $value->keterangan }}')" data-toggle="tooltip" data-placement="bottom" title="Edit">
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
		</form>
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
							
							<select class="form-control m-input m-input--square" id="id_stt" name="id_stt">
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
						
						<div class="col-md-12 text-left" style="padding-top: 10px">
							<label for="nominal">
								<b>Nominal Biaya</b> <span class="span-required"> *</span>
							</label>
							
							<input class="form-control m-input m-input--square" id="nominal" name="nominal" type="number" required maxlength="16" />
							
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
							<button type="button" class="btn btn-sm btn-danger" onclick="refresh()" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="Batal"><i class="fa fa-times"> </i> Batal</button>
						</div>
					</div>
				</div>
			</form>
			
		</div>
	</div>
</div>
@endsection

@section("script")
<script type="text/javascript">
	
	function goEdit(id, id_group, id_stt, nominal, tgl_posting,keterangan){
		$("#_method").val("PUT");
		$("#nominal").val(nominal);
		$("#id_stt").val(id_stt).trigger("change");
		$("#keterangan").text(keterangan);
		$("#id_biaya_grup").val(id_group).trigger('change');
		$("#form-data").attr("action", "{{ url('dmtrucking/updateproyeksi/') }}/"+id);
		$("#modal-create").modal("show");
		$("#tgl_posting").val(tgl_posting);
	}
	
	function refresh(){
		$("#_method").val("POST");
		$("#form-data").attr("action", "{{ url("dmtrucking/saveproyeksi/".Request::segment(2)) }}");
		$("#nominal").val("");
		$("#id_stt").val("").trigger("change");
		$("#keterangan").text("");
		$("#id_biaya_grup").val("");
		$("#tgl_posting").val('{{ date("Y-m-d") }}');
	}
	
	$("#id_stt").select2(
	{
		dropdownParent: $('#modal-create')
	}
	);
	$("#id_biaya_grup").select2(
	{
		dropdownParent: $('#modal-create')
	}
	);
	
</script>
@endsection