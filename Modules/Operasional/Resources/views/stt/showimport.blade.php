@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url("stt/".$data->kode_booking."/saveimport") }}" enctype="multipart/form-data" id="form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }}
    @endif
    @csrf
    <div class="row" style="background-color: #fbfbfb">
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
                <b>No. STT Rekanan</b> <span class="span-required"></span>
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
                <div class="col-md-6">
                    <h5><b>Data Pengirim : </b></h5> 
                </div>
                <div class="col-md-6 text-left" style="margin-left: -25px">
                    <h5><b>Data Penerima : </b></h5>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 row">
            <div class="col-md-6">
                <label for="id_pelanggan">
                    <b>Pelanggan</b> <span class="span-required"> * </span>
                </label>
                
                <select class="form-control m-input m-input--square" id="id_pelanggan" name="id_pelanggan" required>
                    @foreach($pelanggan as $key => $value)
                    <option value="{{ $value->id_pelanggan }}">{{ $value->nm_pelanggan }}</option>
                    @endforeach
                </select>
                
                @if ($errors->has('id_pelanggan'))
                <label style="color: red">
                    {{ $errors->first('id_pelanggan') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-6">
                <label for="pengirim_nm">
                    <b>Nama Pengirim</b> <span class="span-required"> * </span>
                </label>
                
                <input type="text" class="form-control" name="pengirim_nm" id="pengirim_nm" maxlength="50" value="@if(old("pengirim_nm")!=null){{ old("pengirim_nm") }}@elseif(isset($data->nama_pengirim)){{ $data->nama_pengirim }}@endif" required="required">
                
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
                
                <input type="text" class="form-control" name="pengirim_telp" id="pengirim_telp" maxlength="16" value="@if(old("pengirim_telp")!=null){{ old("pengirim_telp") }}@elseif(isset($data->hp_pengirim)){{ $data->hp_pengirim }}@endif" required="required">
                
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
                
                <input type="text" class="form-control" name="pengirim_perush" id="pengirim_perush" maxlength="50" value="">
                
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
                
                <input type="text" class="form-control" name="pengirim_kodepos" id="pengirim_kodepos" maxlength="8" value="">
                
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
                
                <textarea class="form-control" id="pengirim_alm" name="pengirim_alm" maxlength="100" required="required">@if(old("pengirim_alm")!=null){{ old("pengirim_alm") }}@elseif(isset($data->alamat_pengirim)){{ $data->alamat_pengirim }}@endif</textarea >
                    
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
                    
                    <input type="text" class="form-control" name="penerima_nm" id="penerima_nm" maxlength="50" value="@if(old("penerima_nm")!=null){{ old("penerima_nm") }}@elseif(isset($data->nm_penerima)){{ $data->nm_penerima }}@endif" required="required">
                    
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
                    
                    <input type="text" class="form-control" name="penerima_telp" id="penerima_telp" maxlength="16" value="@if(old("penerima_telp")!=null){{ old("penerima_telp") }}@elseif(isset($data->hp_penerima)){{ $data->hp_penerima }}@endif" required="required">
                    
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
                    
                    <input type="text" class="form-control" name="penerima_perush" id="penerima_perush" maxlength="50" value="">
                    
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
                    
                    <textarea class="form-control"  id="penerima_alm" name="penerima_alm" maxlength="100" required="required">@if(old("penerima_alm")!=null){{ old("penerima_alm") }}@elseif(isset($data->alamat_penerima)){{ $data->alamat_penerima }}@endif</textarea>
                    
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
                                    <input type="number" class="form-control m-input m-input--square" id="n_berat" name="n_berat" maxlength="100" step="any" value="@if(isset($data->est_berat)){{ $data->est_berat }}@else{{ old("n_berat") }}@endif" required="required">
                                    
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
                                    <input type="number" class="form-control m-input m-input--square" id="n_volume" name="n_volume" maxlength="100" step="any" value="@if(isset($data->est_volume)){{ $data->est_volume }}@else{{ old("n_volume") }}@endif" required="required">
                                    
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
                                    <input type="number" class="form-control m-input m-input--square" id="n_kubik" name="n_kubik" maxlength="100" step="any" value="@if(isset($data->est_kgv)){{ $data->est_kgv }}@else{{ old("n_kubik") }}@endif" required="required">
                                    
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
                                    <input type="number" class="form-control m-input m-input--square" id="n_koli" name="n_koli" maxlength="100" value="@if(old("n_koli")!=null){{ old("n_koli") }}@elseif(isset($data->n_koli)){{ $data->n_koli }}@endif" required="required">
                                    
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
                <div class="col-md-12 checkbox">
                    <input style="width: 15px; height: 15px;" class="form-check-input" type="checkbox" id="is_packing" name="is_packing" value="1">
                    <label class="form-check-label" for="is_packing">
                        (Centang Jika ingin menambah packing)
                    </label>
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
                    <b>Keterangan / Info Kirim</b> <span class="span-required"> *</span>
                </label>
                <textarea class="form-control m-input m-input--square" id="info_kirim" name="info_kirim" maxlength="200" required="required">@if(old("info_kirim")!=null){{ old("info_kirim") }}@elseif(isset($data->ket_tambahan)){{ $data->ket_tambahan }}@endif</textarea>
                @if ($errors->has('info_kirim'))
                <label style="color: red">
                    {{ $errors->first('info_kirim') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-12 text-right" style="padding-top: 10px">
                <button class="btn btn-md btn-success" type="button" onclick="goSubmit()"><i class="fa fa-save"> </i> Simpan</button>
                <a href="{{ url(Request::segment(1)."/import") }}" class="btn btn-md btn-danger"><i class="fa fa-times"> </i> Batal</a>
            </div>
            
        </div>
    </div>
</div>
@include('operasional::inc_tarif')
@include('operasional::authborongan')
</form>
@endsection

@section('script')
@include("operasional::js.js-import")
@endsection

