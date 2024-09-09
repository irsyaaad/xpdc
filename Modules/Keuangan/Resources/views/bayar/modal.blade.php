<div class="modal fade" id="modal-dm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="margin-left: 10%; font-weight: bold;"><span><i class="fa fa-money"></i></span> Pembayaran STT</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@php
			$ldate = date('Y-m-d H:i:s')
			@endphp
			<div class="modal-body">
				<form method="POST" action="" id="form-bayar">
					<input type="hidden" name="_method" id="_method" value="POST" />
					@csrf
					<table>
						<thead>
							<tr>
								<th width="180px"> No. STT </th>
								<th width="10px"> : </th>
								<th> <input type="text" class="form-control no-border" readonly id="id_stt" name="id_stt"> </th>
							</tr>
							<tr>
								<th width="180px"> Perusahaan Asal </th>
								<th width="10px"> : </th>
								<th> <b> @if(isset(Session('perusahaan')['id_perush'])) <input type="text" class="form-control" name="nm_perush" value="{{ strtoupper(Session('perusahaan')['nm_perush']) }}" readonly>  @endif </b> </th>
								<input type="hidden" name="id_perush" value="{{Session('perusahaan')['id_perush']}}">
							</tr>
							
							<tr>
								<th width="180px"> Pelanggan </th>
								<th width="10px"> : </th>
								<th> <input type="text" class="form-control" name="nm_pelanggan" id="nm_pelanggan" readonly></th>
								<th> <input type="hidden" class="form-control" name="id_plgn" id="id_plgn" readonly></th>
							</tr>
							<tr>
								<th width="180px">Keterangan </th>
								<th width="10px"> : </th>
								<th>
									<textarea class="form-control no-border" id="keterangan" name="keterangan"></textarea>
								</th>
							</tr>
						</thead>
					</table>
					
					<div class="form-group">
						<label for="tgl_bayar" >No Referensi</label>
						<input class="form-control" id="referensi" name="referensi" placeholder="No Referensi " />
						@if ($errors->has('referensi'))
						<label style="color: red">
							{{ $errors->first('referensi') }}
						</label>
						@endif
					</div>
					
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
						<input class="form-control" step="any" id="n_bayar" name="n_bayar" type="number" placeholder="Masukan Nilai bayar" />
						@if ($errors->has('n_bayar'))
						<label style="color: red">
							{{ $errors->first('n_bayar') }}
						</label>
						@endif
					</div>
					
					<div class="form-group">
						<label for="ac4_d" >Perkiraan Akun<span class="span-required"> *</span></label>
						<select class="form-control" id="ac4_d" name="ac4_d">
							<option value="1"> -- Pilih Akun --</option>
							@foreach ($akun as $key => $value)
							<option value="{{ $value->id_ac }}">{{ strtoupper($value->nama) }}</option>
							@endforeach
						</select>
						@if ($errors->has('ac4_d'))
						<label style="color: red">
							{{ $errors->first('ac4_d') }}
						</label>
						@endif
					</div>
					
					<div class="form-group">
						<label for="id_cr_byr" >Cara Bayar<span class="span-required"> *</span></label>
						<select class="form-control" id="id_cr_byr" name="id_cr_byr">
							<option value=""> -- Pilih Cara Bayar --</option>
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
						<label for="info" >Keterangan</label>
						<textarea class="form-control" name="info" id="info"></textarea>
						@if ($errors->has('info'))
						<label style="color: red">
							{{ $errors->first('info') }}
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