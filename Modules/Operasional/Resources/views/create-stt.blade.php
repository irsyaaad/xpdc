<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('stt') }}@else{{ url('stt', $data->id_stt) }}@endif" enctype="multipart/form-data" id="form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }}
    @endif
    @csrf
    <div class="row" style="background-color: #fbfbfb">
        <div class="col-md-12" id="div-limit">
            <div class="alert alert-danger">
                <h5>Limit Piutang Pelanggan Terbatas !</h5>
                <h6 id="label-plgn" style="margin-top: 6px">
                    Nama Pelanggan :
                </h6>
                <h6 id="label-piutang">
                    Jumlah Piutang :
                </h6>
            </div>
        </div>
        
        <div class="col-md-12" id="div-tarif">
            <div class="alert alert-danger">
                <h5>Tarif Jual Belum Terdefinisi !</h5>
            </div>
        </div>
        <div class="col-md-4">
            <label for="tgl_masuk">
                <b>Tgl Masuk</b> <span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control" name="tgl_masuk" id="tgl_masuk" maxlength="20" style="background-color: #fff"value="@if(isset($data->tgl_masuk)){{ $data->tgl_masuk }}@else{{ old("tgl_masuk") }}@endif" required="required">
            
            @if ($errors->has('tgl_masuk'))
            <label style="color: red">
                {{ $errors->first('tgl_masuk') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-4">
            <label for="tgl_keluar">
                <b>Tgl Rencana Keluar</b> <span class="span-required"></span>
            </label>
            
            <input type="date" class="form-control" name="tgl_keluar" id="tgl_keluar" maxlength="20" value="@if(isset($data->tgl_keluar)){{ $data->tgl_keluar }}@else{{ old("tgl_keluar") }}@endif" style="background-color: #fff">
            
            @if ($errors->has('tgl_keluar'))
            <label style="color: red">
                {{ $errors->first('tgl_keluar') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-4">
            <label for="no_awb">
                <b>No. Resi Rekanan (AWB)</b> <span class="span-required"></span>
            </label>
            
            @if(isset($awb->id_stt))
            <input type="text" class="form-control" name="kode_awb" id="kode_awb" maxlength="30" value="{{ $awb->kode_stt }}" style="background-color: #fff">
            @else
            <input type="text" class="form-control" name="no_awb" id="no_awb" maxlength="30" value="@if(isset($data->no_awb)){{ $data->no_awb }}@else{{ old("no_awb") }}@endif" style="background-color: #fff">
            @endif
            
            @if ($errors->has('no_awb'))
            <label style="color: red">
                {{ $errors->first('no_awb') }}
            </label>
            @endif
        </div>
    </div>
    
    <hr>
    
    <div class="row" style="background-color: #fbfbfb">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 text-left">
                    <h5><b>Data Pengirim : </b></h5>
                </div>
                <div class="col-md-6 text-left" style="margin-left: -25px">
                    <h5><b>Data Penerima : </b></h5>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 row">
            <div class="col-md-8">
                <label for="id_pelanggan">
                    <b>Pelanggan</b> <span class="span-required"> * </span>
                </label>
                
                <select class="form-control m-input m-input--square" id="id_pelanggan" name="id_pelanggan" required>
                    @if(!is_null(old('id_pelanggan')))
                    <option value="{{ old("id_pelanggan") }}">{{ old('pengirim_nm') }}</option>
                    @endif
                </select>
                
                @if ($errors->has('id_pelanggan'))
                <label style="color: red">
                    {{ $errors->first('id_pelanggan') }}
                </label>
                @endif
            </div>

            <div class="col-md-4" style="margin-top:26px">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg" title="Cari Pelanggan"><i class="fa fa-search" ></i></button>
                <button type="button" class="btn btn-success" onclick="addPelanggan()" title="Cari Pelanggan"><i class="fa fa-plus" ></i></button>
            </div>
            
            <div class="col-md-12">
                <label for="pengirim_nm">
                    <b>Nama Pengirim</b> <span class="span-required"> * </span>
                </label>
                
                <input type="text" class="form-control" name="pengirim_nm" id="pengirim_nm" maxlength="50" value="@if(isset($data->pengirim_nm)){{ $data->pengirim_nm }}@else{{ old("pengirim_nm") }}@endif" required="required">
                
                @if ($errors->has('pengirim_nm'))
                <label style="color: red">
                    {{ $errors->first('pengirim_nm') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="pengirim_telp">
                    <b>Telp Pengirim</b> <span class="span-required"> * </span>
                </label>
                
                <input type="text" class="form-control" name="pengirim_telp" id="pengirim_telp" maxlength="16" value="@if(isset($data->pengirim_telp)){{ $data->pengirim_telp }}@else{{ old("pengirim_telp") }}@endif" required="required">
                
                @if ($errors->has('pengirim_telp'))
                <label style="color: red">
                    {{ $errors->first('pengirim_telp') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="pengirim_perush">
                    <b>Perusahaan Pengirim</b> <span class="span-required"></span>
                </label>
                
                <input type="text" class="form-control" name="pengirim_perush" id="pengirim_perush" maxlength="50" value="@if(isset($data->pengirim_perush)){{ $data->pengirim_perush }}@else{{ old("pengirim_perush") }}@endif">
                
                @if ($errors->has('pengirim_perush'))
                <label style="color: red">
                    {{ $errors->first('pengirim_perush') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6" style="padding-left: 5px;">
                <label for="pengirim_id_region">
                    <b>Kota Pengirim</b> <span class="span-required"> * </span>
                </label>
                
                <select class="form-control m-input m-input--square" id="pengirim_id_region" name="pengirim_id_region">
                    @if(!is_null(old('pengirim_id_region')))
                    <option value="{{ old("pengirim_id_region") }}">{{ old('nm_pengirim_region') }}</option>
                    @endif
                </select>
                
                @if ($errors->has('pengirim_id_region'))
                <label style="color: red">
                    {{ $errors->first('pengirim_id_region') }}
                </label>
                @endif
                
                <input type="hidden" name="nm_pengirim_region" id="nm_pengirim_region" value="{{ old("nm_pengirim_region") }}">
            </div>
            
            <div class="col-md-6">
                <label for="pengirim_kodepos">
                    <b>Kode Pos</b> <span class="span-required"> </span>
                </label>
                
                <input type="text" class="form-control" name="pengirim_kodepos" id="pengirim_kodepos" maxlength="8" value="@if(isset($data->pengirim_kodepos)){{ $data->pengirim_kodepos }}@else{{ old("pengirim_kodepos") }}@endif">
                
                @if ($errors->has('pengirim_kodepos'))
                <label style="color: red">
                    {{ $errors->first('pengirim_kodepos') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-12">
                <label for="pengirim_alm">
                    <b>Alamat Pengirim</b> <span class="span-required"> * </span>
                </label>
                
                <textarea class="form-control" id="pengirim_alm" name="pengirim_alm" required="required">@if(isset($data->pengirim_alm)){{ $data->pengirim_alm }}@else{{ old("pengirim_alm") }}@endif</textarea >
                    
                    @if ($errors->has('pengirim_alm'))
                    <label style="color: red">
                        {{ $errors->first('pengirim_alm') }}
                    </label>
                    @endif
                </div>
                
            </div>
            
            <div class="col-md-6 row">
                <div class="col-md-12">
                    <label for="penerima_nm">
                        <b>Nama Penerima</b> <span class="span-required"> * </span>
                    </label>
                    
                    <input type="text" class="form-control" name="penerima_nm" id="penerima_nm" maxlength="50" value="@if(isset($data->penerima_nm)){{ $data->penerima_nm }}@else{{ old("penerima_nm") }}@endif" required="required">
                    
                    @if ($errors->has('penerima_nm'))
                    <label style="color: red">
                        {{ $errors->first('penerima_nm') }}
                    </label>
                    @endif
                </div>
                
                <div class="col-md-6">
                    <label for="penerima_telp">
                        <b>Telp Penerima</b> <span class="span-required"> * </span>
                    </label>
                    
                    <input type="text" class="form-control" name="penerima_telp" id="penerima_telp" maxlength="16" value="@if(isset($data->penerima_telp)){{ $data->penerima_telp }}@else{{ old("penerima_telp") }}@endif" required="required">
                    
                    @if ($errors->has('penerima_telp'))
                    <label style="color: red">
                        {{ $errors->first('penerima_telp') }}
                    </label>
                    @endif
                </div>
                
                <div class="col-md-6">
                    <label for="penerima_perush">
                        <b>Perusahaan Penerima</b> <span class="span-required"></span>
                    </label>
                    
                    <input type="text" class="form-control" name="penerima_perush" id="penerima_perush" maxlength="50" value="@if(isset($data->penerima_perush)){{ $data->penerima_perush }}@else{{ old("penerima_perush") }}@endif">
                    
                    @if ($errors->has('penerima_perush'))
                    <label style="color: red">
                        {{ $errors->first('penerima_perush') }}
                    </label>
                    @endif
                </div>
                
                <div class="col-md-6">
                    <label for="penerima_id_region">
                        <b>Kota Penerima</b> <span class="span-required"> * </span>
                    </label>
                    
                    <select class="form-control m-input m-input--square" id="penerima_id_region" name="penerima_id_region">
                        @if(!is_null(old('penerima_id_region')))
                        <option value="{{ old("penerima_id_region") }}">{{ old('nm_penerima_region') }}</option>
                        @endif
                    </select>
                    
                    @if ($errors->has('penerima_id_region'))
                    <label style="color: red">
                        {{ $errors->first('penerima_id_region') }}
                    </label>
                    @endif
                    
                    <input type="hidden" name="nm_penerima_region" id="nm_penerima_region" value="{{ old("nm_penerima_region") }}">
                </div>
                
                <div class="col-md-6">
                    <label for="penerima_kodepos">
                        <b>Kode Pos</b> <span class="span-required"></span>
                    </label>
                    
                    <input type="text" class="form-control" name="penerima_kodepos" id="penerima_kodepos"  maxlength="8" value="@if(isset($data->penerima_kodepos)){{ $data->penerima_kodepos }}@else{{ old("penerima_kodepos") }}@endif">
                    
                    @if ($errors->has('penerima_kodepos'))
                    <label style="color: red">
                        {{ $errors->first('penerima_kodepos') }}
                    </label>
                    @endif
                </div>
                
                <div class="col-md-12">
                    <label for="penerima_alm">
                        <b>Alamat Penerima</b> <span class="span-required"> * </span>
                    </label>
                    
                    <textarea class="form-control"  id="penerima_alm" name="penerima_alm" required="required">@if(isset($data->penerima_alm)){{ $data->penerima_alm }}@else{{ old("penerima_alm") }}@endif</textarea>
                    
                    @if ($errors->has('penerima_alm'))
                    <label style="color: red">
                        {{ $errors->first('penerima_alm') }}
                    </label>
                    @endif
                </div>
                
            </div>
        </div>
        <hr>
        <div class="row" style="padding: 1%; padding-bottom: -3%; background-color: #fbfbfb">
            
            <div class="col-md-12 row">
                <div class="col-md-6">
                    <h5 style="margin-top: 1%;"><b>Perhitungan Tarif : </b></h5>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-responsive">
                            <tr>
                                <th>
                                    <label for="id_layanan">
                                        <b>Layanan</b> <span class="span-required">*</span>
                                    </label>
                                    
                                    <select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan"
                                    required="required" style="background-color: #fff">
                                    <option value="0">-- Pilih Layanan --</option>
                                    @foreach($layanan as $key => $value)
                                    <option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
                                    @endforeach
                                </select>
                                
                                @if ($errors->has('id_layanan'))
                                <label style="color: red">
                                    {{ $errors->first('id_layanan') }}
                                </label>
                                @endif
                            </th>
                            <th style="width:30%"><b>Asal : </b> <br><b id="text-asal"> Pilih Asal</b></span></th>
                            <th style="width:30%"><b>Tujuan : </b> <br> <b  id="text-tujuan">Pilih Tujuan</b></th>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-12">
                    <div class="row" style="margin-top: 10px">
                        <div class="col-md-11">
                            <label for="id_tarif" >
                                <b>Tarif Dasar :</b> <span class="span-required">*</span>
                            </label>
                            
                            <select class="form-control m-input m-input--square" id="id_tarif" name="id_tarif"
                            style="background-color: #fff">
                            <option value="0">-- Pilih Tarif --</option>
                        </select>
                        
                        <input type="hidden" name="nm_tarif" id="nm_tarif" value="">
                    </div>
                    
                    <div class="col-md-12">
                        <table class="table table-responsive" style="font-weight: bold;">
                            <tr>
                                <td width="20%"><label><input type="radio" value="1" id="c_hitung" name="c_hitung"> Kg</label></td>
                                <td width="20%"><label>
                                    <input type="radio" value="2" id="c_hitung" name="c_hitung"> Kgv</label>
                                </td>
                                <td width="20%"><label>
                                    <input type="radio" value="4" id="c_hitung" name="c_hitung"> M3</label>
                                </td>
                                <td width="20%">
                                    <label><input type="radio" value="3" id="c_hitung" name="c_hitung"> Borongan</label>
                                </td>
                                
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-12">
                        <table class="table table-responsive" style="font-weight: bold;">
                            <tr>
                                <td><label> Kg : </label> <span class="span-required">*</span></td>
                                <td width="40%">
                                    <input type="number" class="form-control m-input m-input--square" id="n_berat" name="n_berat" maxlength="100" step="any" value="@if(isset($data->n_berat)){{ $data->n_berat }}@else{{ old("n_berat") }}@endif" required="required">
                                    
                                    @if ($errors->has('n_berat'))
                                    <label style="color: red">
                                        {{ $errors->first('n_berat') }}
                                    </label>
                                    @endif
                                </td>
                                <td>
                                    <input type="number" class="form-control m-input m-input--square" id="n_tarif_brt" name="n_tarif_brt" maxlength="100" value="@if(isset($data->n_tarif_brt)){{ $data->n_tarif_brt }}@else{{ old("n_tarif_brt") }}@endif" style="background-color: #fff" required="required">
                                    
                                    <input type="hidden" name="cm_brt" id="cm_brt">
                                    
                                    @if ($errors->has('n_tarif_brt'))
                                    <label style="color: red">
                                        {{ $errors->first('n_tarif_brt') }}
                                    </label>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><label> Kgv : </label> <span class="span-required">*</span></td>
                                <td width="40%">
                                    <input type="number" class="form-control m-input m-input--square" id="n_volume" name="n_volume" maxlength="100" step="any" value="@if(isset($data->n_volume)){{ $data->n_volume }}@else{{ old("n_volume") }}@endif" required="required">
                                    
                                    <input type="hidden" name="cm_vol" id="cm_vol">
                                    
                                    @if ($errors->has('n_volume'))
                                    <label style="color: red">
                                        {{ $errors->first('n_volume') }}
                                    </label>
                                    @endif
                                </td>
                                <td>
                                    <input type="number" class="form-control m-input m-input--square" id="n_tarif_vol" name="n_tarif_vol" maxlength="100" value="@if(isset($data->n_tarif_vol)){{ $data->n_tarif_vol }}@else{{ old("n_tarif_vol") }}@endif" style="background-color: #fff;" required="required">
                                </td>
                            </tr>
                            
                            <tr>
                                <td><label> M3 : </label> <span class="span-required">*</span></td>
                                <td width="40%">
                                    <input type="number" class="form-control m-input m-input--square" id="n_kubik" name="n_kubik" maxlength="100" step="any" value="@if(isset($data->n_kubik)){{ $data->n_kubik }}@else{{ old("n_kubik") }}@endif" required="required">
                                    
                                    <input type="hidden" name="cm_kubik" id="cm_kubik">
                                    
                                    @if ($errors->has('n_kubik'))
                                    <label style="color: red">
                                        {{ $errors->first('n_kubik') }}
                                    </label>
                                    @endif
                                </td>
                                <td>
                                    <input type="number" class="form-control m-input m-input--square" id="n_tarif_kubik" name="n_tarif_kubik" maxlength="100" value="@if(isset($data->n_tarif_kubik)){{ $data->n_tarif_kubik }}@else{{ old("n_tarif_kubik") }}@endif" style="background-color: #fff;" required="required">
                                </td>
                            </tr>
                            
                            <tr>
                                <td width="30%"><label> Borongan : </label> <span class="span-required"></span></td>
                                <td colspan="2">
                                    <input type="number" class="form-control m-input m-input--square" id="n_tarif_borongan" name="n_tarif_borongan" maxlength="100" value="@if(isset($data->n_tarif_borongan)){{ $data->n_tarif_borongan }}@else{{ old("n_tarif_borongan") }}@endif">
                                    
                                    <input type="hidden" name="secret_code" id="secret_code">
                                    
                                    @if ($errors->has('n_tarif_borongan'))
                                    <label style="color: red">
                                        {{ $errors->first('n_tarif_borongan') }}
                                    </label>
                                    @endif
                                </td>
                            </tr>
                            
                            <tr>
                                <td><label> Jumlah Koli : </label> <span class="span-required">*</span></td>
                                <td colspan="2">
                                    <input type="number" class="form-control m-input m-input--square" id="n_koli" name="n_koli" maxlength="100" value="@if(isset($data->n_volume)){{ $data->n_koli }}@else{{ old("n_koli") }}@endif" required="required">
                                    
                                    @if ($errors->has('n_koli'))
                                    <label style="color: red">
                                        {{ $errors->first('n_koli') }}
                                    </label>
                                    @endif
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="row" style="margin-top: 1%; background-color: #fbfbfb">
            <div class="col-md-6">
                <label for="n_hrg_bruto">
                    <b>Harga Bruto </b> <span class="span-required">*</span>
                </label>
                
                <input type="text" class="form-control m-input m-input--square" id="n_hrg_bruto" name="n_hrg_bruto" maxlength="100" style="background-color: #fff" value="@if(isset($data->n_hrg_bruto)){{ $data->n_hrg_bruto }}@else{{ old("n_hrg_bruto") }}@endif" required="required" readonly="readonly">
                
                @if ($errors->has('n_hrg_bruto'))
                <label style="color: red">
                    {{ $errors->first('n_hrg_bruto') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="n_diskon">
                    <b>Diskon </b> <span class="span-required"></span>
                </label>
                
                <input type="text" class="form-control m-input m-input--square" id="n_diskon" name="n_diskon" maxlength="100" value="@if(isset($data->n_diskon)){{ $data->n_diskon }}@else{{ old("n_diskon") }}@endif">
                
                @if ($errors->has('n_diskon'))
                <label style="color: red">
                    {{ $errors->first('n_diskon') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="n_ppn">
                    <b>Tarif PPN </b> @if($tarif_ppn)
                    ({{$tarif_ppn}} %)
                    @else
                    -
                    @endif
                    <span class="span-required"></span>
                </label>
                
                <div class="row">
                    <div class="col-md-2 checkbox">
                        <label><input type="checkbox" value="1" id="is_ppn" name="is_ppn"></label>
                    </div>
                    
                    <div class="col-md-10">
                        
                        <input type="text" class="form-control m-input m-input--square" id="n_ppn" name="n_ppn" maxlength="100" value="@if(isset($data->n_ppn)){{ $data->n_ppn }}@else{{ old("n_ppn") }}@endif" style="background-color: #fff">
                        
                        @if ($errors->has('n_ppn'))
                        <label style="color: red">
                            {{ $errors->first('n_ppn') }}
                        </label>
                        @endif
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <label for="n_materai">
                    <b>Biaya Materai </b> <span class="span-required"></span>
                </label>
                
                <input type="text" class="form-control m-input m-input--square" id="n_materai" name="n_materai" maxlength="100"  value="@if(isset($data->n_materai)){{ $data->n_materai }}@else{{ old("n_materai") }}@endif">
                
                @if ($errors->has('n_materai'))
                <label style="color: red">
                    {{ $errors->first('n_materai') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="n_ppn">
                    <b>Asuransi </b> <span class="span-required"></span>
                </label>
                
                <div class="row">
                    <div class="col-md-2 checkbox">
                        <label><input type="checkbox" value="1" id="is_asuransi" name="is_asuransi"></label>
                    </div>
                    
                    <div class="col-md-10">
                        
                        <input type="text" class="form-control m-input m-input--square" id="n_asuransi" name="n_asuransi" maxlength="100" value="@if(isset($data->n_asuransi)){{ $data->n_asuransi }}@else{{ old("n_asuransi") }}@endif" style="background-color: #fff">
                        
                        @if ($errors->has('n_asuransi'))
                        <label style="color: red">
                            {{ $errors->first('n_asuransi') }}
                        </label>
                        @endif
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <label for="is_packing">
                    <b>Packing </b> <span class="span-required"></span>
                </label>
                <div class="row">
                    <div class="col-md-2 checkbox">
                        <label><input type="checkbox" value="1" id="is_packing" name="is_packing"></label>
                    </div>
                    
                    <div class="col-md-10">
                        
                        <input type="number" step="any" class="form-control m-input m-input--square" id="n_packing" name="n_packing" maxlength="100" value="@if(isset($data->n_packing)){{ $data->n_packing }}@else{{ old("n_packing") }}@endif" style="background-color: #fff">
                        
                        @if ($errors->has('n_packing'))
                        <label style="color: red">
                            {{ $errors->first('n_packing') }}
                        </label>
                        @endif
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <label for="c_total" style="margin-top: 2%">
                    <b>Harga Nett </b> <span class="span-required">*</span>
                </label>
                
                <input type="text" class="form-control m-input m-input--square" id="c_total" name="c_total" maxlength="100" readonly="readonly" style="background-color: #fff" required="required" value="@if(isset($data->c_total)){{ $data->c_total }}@else{{ old("c_total") }}@endif">
                
                @if ($errors->has('c_total'))
                <label style="color: red">
                    {{ $errors->first('c_total') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="id_cr_byr_o">
                    <b>Cara Pembayaran</b> <span class="span-required">*</span>
                </label>
                
                <select class="form-control m-input m-input--square" id="id_cr_byr_o" name="id_cr_byr_o" required="required">
                    <option value="">-- Pilih Cara Bayar --</option>
                    @foreach($cara as $key => $value)
                    <option value="{{ $value->id_cr_byr_o }}">{{ strtoupper($value->nm_cr_byr_o) }}</option>
                    @endforeach
                </select>
                
                @if ($errors->has('id_cr_byr_o'))
                <label style="color: red">
                    {{ $errors->first('id_cr_byr_o') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="id_marketing">
                    <b>Marketing </b> <span class="text-danger">*</span>
                </label>
                
                <select class="form-control" id="id_marketing" name="id_marketing" required>
                    <option value="0"> DATANG SENDIRI </option>
                    @foreach($marketing as $key => $value)
                    <option value="{{ $value->id_marketing }}"> {{ strtoupper($value->nm_marketing) }} </option>
                    @endforeach
                </select>
                
                @if ($errors->has('id_marketing'))
                <label style="color: red">
                    {{ $errors->first('id_marketing') }}
                </label>
                @endif
                
            </div>
            
            <div class="col-md-6">
                <label for="id_tipe_kirim">
                    <b>Tipe Barang Kiriman</b> <span class="span-required"> *</span>
                </label>
                
                <select class="form-control m-input m-input--square" id="id_tipe_kirim" name="id_tipe_kirim" required="required">
                    <option value="">-- Pilih Tipe Kirim --</option>
                    @foreach ($tipe as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                
                @if ($errors->has('id_tipe_kirim'))
                <label style="color: red">
                    {{ $errors->first('id_tipe_kirim') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="cara_kemas" style="margin-top: 2%">
                    <b>Cara Kemas </b>
                </label>
                
                <input type="text" placeholder="Cara Kemas Barang" class="form-control m-input m-input--square" id="cara_kemas" name="cara_kemas" maxlength="150"style="background-color: #fff" value="@if(isset($data->cara_kemas)){{ $data->cara_kemas }}@else{{ old("cara_kemas") }}@endif">
                
                @if ($errors->has('cara_kemas'))
                <label style="color: red">
                    {{ $errors->first('cara_kemas') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="info_kirim">
                    <b>Keterangan / Info Kirim</b>
                </label>
                <textarea class="form-control m-input m-input--square" id="info_kirim" name="info_kirim" style="min-height: 130px" required="required">@if(isset($data->info_kirim)){{ $data->info_kirim }}@else{{ old("info_kirim") }}@endif</textarea>
                @if ($errors->has('info_kirim'))
                <label style="color: red">
                    {{ $errors->first('info_kirim') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-12 text-right" style="padding-top: 10px">
                <button class="btn btn-md btn-success" type="button" id="btn-submit" onclick="goSubmit()"><i class="fa fa-save"> </i> Simpan</button>
                <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-danger"><i class="fa fa-times"> </i> Batal</a>
            </div>
            
        </div>
    </div>
</div>

{{-- @include('operasional::form') --}}
@include('operasional::search_pelanggan.search_pelanggan')
@include('operasional::inc_tarif')
@include('operasional::authborongan')
</form>

<div class="modal fade" id="modal-pelanggan" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5><b id="text-auth"> Masukkan Data Pelanggan </b></h5>
            </div>
            <div class="modal-body" style="margin-top: -7%">
                
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>
                
                <div class="alert alert-success print-success-msg" style="display:none">
                    <h6 id="text-success"></h6>
                </div>
                
                <form action="#" method="post" id="ajax-form">
                    <label for="nm_user">
                        <b>Nama Pelanggan <span class="text-danger">*</span></b>
                    </label>
                    <input required type="text" class="form-control m-input m-input--square" name="nm_pelanggan" id="nm_pelanggan" >
                    <br>
                    
                    <label for="id_plgn_group">
                        <b>Group Pelanggan <span class="text-danger">*</span></b>
                    </label>
                    <select class="form-control m-input m-input--square" name="id_plgn_group" required id="id_plgn_group">
                        @foreach($group as $key => $value)
                        <option value="{{ $value->kode }}">{{ strtoupper($value->kode.' - '.$value->nm_group) }}</option>
                        @endforeach
                    </select>
                    <br>
                    
                    <label for="alamat">
                        <b>Alamat <span class="text-danger">*</span></b>
                    </label>
                    <textarea class="form-control m-input m-input--square" required name="alamat" id="alamat"></textarea>
                    <br>
                    
                    <label for="id_wil">
                        <b>Wilayah <span class="text-danger">*</span></b>
                    </label>
                    <select id="id_wil_pelanggan" name="id_wil_pelanggan" required class="form-control"></select>
                    <br><br>
                    
                    <label for="telp">
                        <b>No Telp / WA <span class="text-danger">*</span></b>
                    </label>
                    
                    <input type="telp" class="form-control m-input m-input--square" min="6" required name="telp" id="telp" maxlength="14">
                </form>
                
                <div style="margin-top: 1%; font-weight: bold;">
                    <label class="span-required" id="error-code"></label>
                    <label class="span-success" id="success-code"></label>
                </div>
                
                <div class="text-right">
                    <button type="button" class="btn btn-sm btn-success" onclick="savedata()"><i class="fa fa-save"></i> Submit</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


@section('script')
@include("operasional::js.js-stt")
@if(Request::segment(3)=="edit")
@include("operasional::js.js-edit")
@endif
<script>
    $("#div-tarif").hide();
    $("#div-limit").hide();
    
    $("#text-pelanggan").hide();
    
    function savedata() {
        var nama = $("#nm_pelanggan").val();
        var group = $("#id_plgn_group").val();
        var alamat = $("#alamat").val();
        var wil = $("#id_wil_pelanggan").val();
        var telp = $("#telp").val();
        var token = "{{ csrf_token() }}";
        
        $.ajax({
            url : "{{ url('addPelanggan') }}",
            method : "POST",
            data : {_token: token, nm_pelanggan : nama, id_plgn_group : group, alamat : alamat, id_wil : wil, telp : telp },
            success :function(data){
                if($.isEmptyObject(data.error)){
                    $(".print-error-msg").hide();
                    $(".print-success-msg").show();
                    $("#text-success").text("Data Pelanggan Sukses Ditambahkan");
                    $("#ajax-form > .form-control ").val("");
                    $("#modal-pelanggan").modal("hide");
                }else{
                    $(".print-error-msg").show();
                    $(".print-success-msg").hide();
                    printErrorMsg(data.error);
                }
            }
        });
    }
    
    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
    }
    
    $('#id_wil_pelanggan').select2({
        placeholder: 'Cari Wilayah ....',
        allowClear: true,
        ajax: {
            url: '{{ url('getwilayah') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_wil_pelanggan').empty();
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
    function addPelanggan() {
    $("#modal-pelanggan").modal('show');
  }
</script>
@endsection

