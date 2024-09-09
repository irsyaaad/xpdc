<div class="modal fade" id="modal-dm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Pembayaran STT Pada INVOICE</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@php
			$ldate = date('Y-m-d H:i:s')
			@endphp
			<div class="modal-body">
				<form method="POST" action="#" id="form-bayar">
					@csrf

					<div class="form-group">
						<label for="tgl_bayar" >Tanggal Bayar<span class="span-required"> *</span></label>
						<input class="form-control" id="tgl_bayar" name="tgl_bayar" type="date" placeholder="Masukan Tanggal Bayar" />
						@if ($errors->has('tgl_bayar'))
						<label style="color: red">
							{{ $errors->first('tgl_bayar') }}
						</label>
						@endif
					</div>

					<div class="form-group">
						<label for="n_bayar" >Nominal Bayar<span class="span-required"> *</span></label>
						<input class="form-control" id="n_bayar" name="n_bayar" type="number" placeholder="Masukan Nilai bayar" />
						@if ($errors->has('n_bayar'))
						<label style="color: red">
							{{ $errors->first('n_bayar') }}
						</label>
						@endif
					</div>

					<div class="form-group">
						<label for="ac4_k" >Perkiraan Akun<span class="span-required"> *</span></label>
						<select class="form-control" id="ac4_d" name="ac4_d">
							<option value="1"> -- Pilih Akun --</option>
							@foreach ($akun as $key => $value)
							<option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
							@endforeach
						</select>
						@if ($errors->has('ac4_k'))
						<label style="color: red">
							{{ $errors->first('ac4_k') }}
						</label>
						@endif
					</div>

					<div class="form-group">
						<label for="ac4_k" >Cara Bayar<span class="span-required"> *</span></label>
						<select class="form-control" id="id_cr_byr" name="id_cr_byr">
							<option value="1"> -- Pilih Cara Bayar --</option>
							@foreach ($cara as $key => $value)
							<option value="{{ $value->id_cr_byr_o }}">{{ strtoupper($value->nm_cr_byr_o) }}</option>
							@endforeach
						</select>
						@if ($errors->has('id_cr_byr'))
						<label style="color: red">
							{{ $errors->first('id_cr_byr') }}
						</label>
						@endif
					</div>

					<div class="form-group">
						<label for="nm_bayar" >Nama<span class="span-required"> *</span></label>
						<input type="nama_rek" class="form-control" id="nm_bayar" name="nm_bayar" value="@if(isset($data->nm_pelanggan)){{$data->nm_pelanggan}} @else {{ old("nm_bayar") }} @endif" >
			
						@if ($errors->has('nm_bayar'))
						<label style="color: red">
							{{ $errors->first('nm_bayar') }}
						</label>
						@endif
					</div>

					<div class="form-group">
						<label for="tgl_bayar" >No Referensi<span class="span-required"> *</span></label>
						<input class="form-control" id="referensi" name="referensi" placeholder="No Referensi " />
						@if ($errors->has('referensi'))
						<label style="color: red">
							{{ $errors->first('referensi') }}
						</label>
						@endif
					</div>

					<div class="form-group">
						<label for="ac4_k" >Keterangan<span class="span-required"> *</span></label>
						<textarea class="form-control" name="info" id="info" cols="30" rows="5">

						</textarea>
						@if ($errors->has('ac4_k'))
						<label style="color: red">
							{{ $errors->first('ac4_k') }}
						</label>
						@endif
					</div>

					<div class="col-md-12 text-right">

						<button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" >Bayar</button>
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>

{{--  --}}

<div class="modal fade" id="modal-stt" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Pembayaran RESI Pada INVOICE</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@php
			$ldate = date('Y-m-d H:i:s')
			@endphp
			<div class="modal-body">
				<form method="POST" action="{{ url(Request::segment(1)).'/setppn' }}" id="form-bayar">
					@csrf
					<table>
						<thead>
							<tr>
								<th width="180px"> No. RESI </th>
								<th width="10px"> : </th>
								<th> <input type="text" class="form-control no-border" readonly id="kode_stt" name="kode_stt"> </th>
                                <input type="hidden" name="id_invoice" value="{{$data->id_invoice}}">
                                <input type="hidden" name="stt" id="stt">
                            </tr>
                            <tr>
								<th width="180px"> Nominal PPN </th>
								<th width="10px"> : </th>
								<th> <input type="number" class="form-control no-border" id="n_ppn" name="n_ppn"> </th>
							</tr>

						</thead>
					</table>
					<br>

					<div class="col-md-12 text-right">

						<button type="submit" class="btn btn-sm btn-success" id="modal-btn-si" >Bayar</button>
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Batal</span></button>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>
