@extends('template.document')

@section('data')

<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('asuransistt') }}" enctype="multipart/form-data">
@csrf	
			<div class="form-group m-form__group">
				<label for="nm_perush">
					<b>ID STT</b> <span class="span-required"> *</span>
				</label>
				<input type="text" class="form-control m-input m-input--square" name="id_stt" id="id_stt" value="{{$data->id_stt}}" maxlength="16" required readonly>
				<input type="hidden" name="id_tarif" id="id_tarif" >
				<input type="hidden" name="harga_beli" id="harga_beli" >
				@if ($errors->has('id_perush_asuransi'))
				<label style="color: red">
					{{ $errors->first('id_perush_asuransi') }}
				</label>
				@endif
			</div>

		<div class="row">
			<div class="col">
				<div class="form-group m-form__group">
				<label for="nm_perush">
					<b>Nama Perusahaan Asuransi</b> <span class="span-required"> *</span>
				</label>
				<select id="id_perush" name="id_perush" class="form-control" required>
					<option value="">-- Pilih Perusahaan --</option>
					@foreach($perush_asuransi as $key => $value)
					<option value="{{$value->id_perush_asuransi}}">{{strtoupper($value->nm_perush_asuransi)}}</option>
					@endforeach
				</select>
				
				@if ($errors->has('id_perush_asuransi'))
				<label style="color: red">
					{{ $errors->first('id_perush_asuransi') }}
				</label>
				@endif
				</div>

			</div>

			<div class="col">
				<div class="form-group m-form__group">
					<label for="nm_perush">
						<b>Jenis Asuransi</b> <span class="span-required"> *</span>
					</label>
					<select id="id_jenis" name="id_jenis" class="form-control" required>
						<option value="">-- Pilih Jenis Asuransi --</option>
						<option value="1">HARGA BARANG</option>
						<option value="2">ONGKOS KIRIM</option>
					</select>
					
					@if ($errors->has('id_perush_asuransi'))
					<label style="color: red">
						{{ $errors->first('id_perush_asuransi') }}
					</label>
					@endif
				</div>	
			</div>
		</div>
		<br>
		<div class="row">
            <div class="col">
				<div class="form-group m-form__group">
					<label for="fax">
						<b>Harga Barang</b><span class="span-required"> *</span>
					</label>

					<input type="number" class="form-control m-input m-input--square" name="harga_barang" id="harga_barang" step="any" placeholder="2500000" value="@if(isset($data->harga_barang)){{ $data->harga_barang }}@else{{ old('harga_barang') }}@endif" maxlength="16" required>
					@if ($errors->has('fax'))
					<label style="color: red">
						{{ $errors->first('fax') }}
					</label>
					@endif
				</div>
            </div>
            <div class="col">
				<div class="form-group m-form__group">
					<label for="fax">
						<b>Nominal Harga yang harus dibayar </b><span class="span-required"> *</span>
					</label>

					<input type="number" class="form-control m-input m-input--square" name="harga_jual" id="harga_jual"  value="@if(isset($data->harga_jual)){{ $data->harga_jual }}@else{{ old('harga_jual') }}@endif" maxlength="16" required readonly>

					@if ($errors->has('harga_jual'))
					<label style="color: red">
						{{ $errors->first('harga_jual') }}
					</label>
					@endif
				</div>
            </div>       
            
        </div>

		<br>
		<div class="row">
            <div class="col">
				<div class="form-group m-form__group">
				<label for="nm_perush">
					<b>Pilih Cara Pembayaran</b> <span class="span-required"> *</span>
				</label>
				<select id="id_ac" name="id_ac" class="form-control" required>
					<option value="">-- Pilh Cara Pembayararn --</option>
					@foreach($ac_perush as $key => $value)
					<option value="{{$value->id_ac}}">{{strtoupper($value->nama)}}</option>
					@endforeach
				</select>
				
				@if ($errors->has('id_perush_asuransi'))
				<label style="color: red">
					{{ $errors->first('id_perush_asuransi') }}
				</label>
				@endif
				</div>
            </div>

            <div class="col">
				<div class="form-group m-form__group">
					<label for="is_asuransi">
							<b>Konfirmasi </b> <span class="span-required"></span>
						</label>
						<div class="col-md-12 checkbox">
							<input style="width: 15px; height: 15px;" class="form-check-input" type="checkbox" id="is_asuransi" name="is_asuransi" value="1">
							<label class="form-check-label" for="is_asuransi">
								(Konfirmasi Pembayaran)
							</label>
						</div>
				</div>
            </div>       
            
        </div>
		
            
        </div>
		
		
		<div class="col-md-12 text-right">
            <div class="form-group m-form__group">
            <div class="m-form__actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan
                </button>
                
                <a href="{{ url(Request::segment(1)) }}" class="btn btn-danger">
                    <i class="fa fa-times"></i>	Batal
                </a>
            </div>
        </div>
		</div>
	
</form>
<script>
    var harga_jual = 0;
	var harga_beli = 0;
    var temp = 0;

    $('#id_jenis').on("change", function(e) {
		getTarif()
		
	});

	function getTarif() {
		var id_perush = $("#id_perush").val();
		var id_jenis = $("#id_jenis").val();
		var token = "{{ csrf_token() }}";
		
		$.ajax({
			type: "POST",
			url: "{{ url('getHargaAsuransi') }}",
			dataType: "json",
			data: {_token: token, id_perush: id_perush, id_jenis: id_jenis},
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
					console.log("response ",response[0]);

				if (typeof response[0] !== 'undefined') {
					harga_jual = response[0].harga_jual;
					harga_beli = response[0].harga_beli;	
					$("#id_tarif").val(response[0].kode);				
				} else {
					alert("Data yang Dipilih tidak ada (Belum ditambahkan) ");
				}	
			
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
				setTarif();
			}
		});
	}

    function setTarif() {
        jml = parseFloat($("#harga_barang" ).val());
        hasil = jml * harga_jual;
		beli = jml * harga_beli;
		console.log(hasil,beli);
		if (hasil != 0) {
			$("#harga_jual").val(hasil);
			$("#harga_beli").val(beli);
		} else {			
			alert("Data yang Dipilih tidak ada (Belum ditambahkan) ");
		}       
    }

	$("#harga_barang").keyup(function() {
		var harga_barang = parseFloat($("input[name='harga_barang']" ).val());

		setTarif();
	});

</script>
@endsection