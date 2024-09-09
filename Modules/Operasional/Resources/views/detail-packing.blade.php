@extends('template.document2')

@section('data')
<form method="POST" action="{{ Request::segment(1) }}" enctype="multipart/form-data">
    @csrf
    <div class="row" style="margin-top: -1%">
        <div class="col-md-12" >
            <h5><b> > Data STT : </b></h5>
        </div>
        <div class="form-group col-md-3">
            <label for="id_stt" style="margin-top:10px;">
                <b>NO. STT </b> <span class="span-required"> * </span>
            </label>
            
            <input class="form-control m-input m-input--square" value="@if(isset($data->kode_stt)){{ $data->kode_stt }}@else {{ old("kode_stt") }} @endif" readonly type="text" name="kode_stt" id="kode_stt" />
            <input type="hidden" value="@if(isset($data->id_stt)){{ $data->id_stt }}@else {{ old("id_stt") }} @endif" id="id_stt" name="id_stt" />
            
            @if ($errors->has('id_stt'))
            <label style="color: red">
                {{ $errors->first('id_stt') }}
            </label>
            @endif
        </div>
        
        <div class="form-group col-md-3">
            <label for="no_awb" style="margin-top:10px;">
                <b>No. AWB</b> <span class="span-required"> *</span>
            </label>
            <input class="form-control m-input m-input--square" value="@if(isset($data->no_awb)){{ $data->no_awb }}@else {{ old("no_awb") }} @endif" readonly type="text" name="no_awb" id="no_awb" />
        </div>
        <div class="form-group col-md-3">
            <label for="nm_pelanggan" style="margin-top:10px;">
                <b>Nama Pelanggan</b> <span class="span-required"> *</span>
            </label>
            <input class="form-control m-input m-input--square" value="@if(isset($data->pelanggan->nm_pelanggan)){{ $data->pelanggan->nm_pelanggan }}@else {{ old("nm_pelanggan") }} @endif" readonly type="text" name="nm_pelanggan" id="nm_pelanggan" />
        </div>
        
        <div class="form-group col-md-3">
            <label for="nm_pengirim" style="margin-top:10px;">
                <b>Nama Pengirim</b> <span class="span-required"> *</span>
            </label>
            <input class="form-control m-input m-input--square" value="@if(isset($data->pengirim_nm)){{ $data->pengirim_nm }}@else {{ old("pengirim_nm") }} @endif" readonly type="text" name="pengirim_nm" id="pengirim_nm" />
        </div>
        
        @if(isset($detail) and count($detail) >0 )
        <div class="col-md-12" style="margin-top: 10px">
            <h5><b> > Data Packing : </b></h5>
        </div>

        <div class="col-md-12 row">

            <div class="form-group col-md-3">
                <label for="no_awb" style="margin-top:10px;">
                    <b>Kode Packing</b>
                </label>
                <input class="form-control" value="@if(isset($barang->kode_packing)){{ $barang->kode_packing }}@endif" readonly type="text"/>
            </div>

            <div class="form-group col-md-3">
                <label for="no_awb" style="margin-top:10px;">
                    <b>Tgl. Masuk Barang</b>
                </label>
                <input class="form-control" value="@if(isset($barang->created_at)){{ dateindo($barang->created_at) }}@endif" readonly type="text"/>
            </div>

            <div class="form-group col-md-3">
                <label for="no_awb" style="margin-top:10px;">
                    <b>Est. Pendapatan</b>
                </label>
                <input class="form-control" value="@if(isset($barang->n_total)){{ torupiah($barang->n_total) }}@endif" readonly type="text"/>
            </div>
            
            <div class="form-group col-md-3">
                <label for="no_awb" style="margin-top:10px;">
                    <b>Total Bayar</b>
                </label>
                <input class="form-control" value="@if(isset($barang->n_bayar)){{ torupiah($barang->n_bayar) }} @endif" readonly type="text"/>
            </div>

        </div>
        @endif

        <div class="col-md-12" style="margin-top: 10px">
            <h5><b> > Data Barang : </b></h5>
        </div>
        
        <div class="col-md-12">
            <div class="text-right">
                <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"> </i> Kembali </a>
            </div>
            <table class="table table-stripped table-responsive" style="margin-top: 10px" width="100%">
                <thead style="background-color: grey; color : #ffff;"> 
                    <tr> 
                        <th rowspan="2">No. </th>
                        <th rowspan="2">Jenis Barang</th>
                        <th rowspan="2">Packing</th>
                        <th rowspan="2">Koli</th>
                        <th colspan="4" class="text-center">Dimensi</th>
                        <th rowspan="2">Tarif</th>
                        <th rowspan="2">Borongan</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr> 
                        <th>Panjang</th>
                        <th>Lebar</th>
                        <th>Tinggi</th>
                        <th>Volume</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach($detail as $key => $value)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>{{ $value->nm_tipe_kirim }}</td>
                        <td>{{ $value->nm_packing }}</td>
                        <td>{{ $value->koli}}</td>
                        <td>{{ $value->panjang }}</td>
                        <td>{{ $value->lebar }}</td>
                        <td>{{ $value->tinggi }}</td>
                        <td>{{ tonumber($value->volume) }}</td>
                        <td>{{ tonumber($value->tarif) }}</td>
                        <td>{{ tonumber($value->n_borongan) }}</td>
                        <td>{{ tonumber($value->n_total) }}</td>
                        <td>
                            @if(isset($barang) and $barang->n_bayar == "0")
                            <button type="button" class="btn btn-sm btn-warning" onclick="gOEdit('{{ $value->id_detail }}')"><i class="fa fa-edit"></i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="#" enctype="multipart/form-data" id="form-import">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h4 class="modal-title" style="font-weight: bold;"><b>Masukan Barang Yang Akan Di Packing</b></h4>
                            <hr>
                        </div>
                        
                        <div class="form-group col-md-4" style="margin-top: 10px">
                            <label for="id_tipe_kirim">
                                <b>Jenis Barang</b> <span class="span-required"> * </span>
                            </label>
                            
                            <select class="form-control m-input m-input--square" readonly="true" name="id_tipe_kirim" id="id_tipe_kirim" required>
                                @foreach($tipe as $key => $value)
                                <option value="{{ $value->id_tipe_kirim }}">{{ strtoupper($value->nm_tipe_kirim) }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('id_tipe_kirim'))
                            <label style="color: red">
                                {{ $errors->first('id_tipe_kirim') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-5" style="margin-top: 10px">
                            <label for="id_jenis_packing">
                                <b>Jenis Packing Barang</b> <span class="span-required"> *</span>
                            </label>
                            
                            <select class="form-control m-input m-input--square" name="id_jenis_packing" id="id_jenis_packing" required>
                                <option value=""> -- Pilih Packing Barang --</option>
                                @foreach($packing as $key => $value)
                                <option value="{{ $value->id_packing }}">{{ strtoupper($value->nm_packing) }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('id_jenis_packing'))
                            <label style="color: red">
                                {{ $errors->first('id_jenis_packing') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="n_koli" style="margin-top:10px;"> 
                                <b>Jumlah Koli Packing</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" required value="@if(isset($data->n_koli)){{ $data->n_koli }}@endif" type="number" placeholder="Masukan lebar Barang (cm)" name="n_koli" id="n_koli" />
                            
                            @if ($errors->has('n_koli'))
                            <label style="color: red">
                                {{ $errors->first('n_koli') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="panjang" style="margin-top:10px;">
                                <b>Panjang Barang (Cm)</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" step="any" required value="@if(isset($data->panjang)){{ $data->panjang }}@else{{ old("panjang") }}@endif" type="number" placeholder="Masukan Panjang Barang (cm)" name="panjang" id="panjang" />
                            
                            @if ($errors->has('panjang'))
                            <label style="color: red">
                                {{ $errors->first('panjang') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="lebar" style="margin-top:10px;"> 
                                <b>Lebar Barang (Cm)</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" step="any" required value="@if(isset($data->lebar)){{ $data->lebar }}@else{{ old("lebar") }}@endif" type="number" placeholder="Masukan lebar Barang (cm)" name="lebar" id="lebar" />
                            
                            @if ($errors->has('lebar'))
                            <label style="color: red">
                                {{ $errors->first('lebar') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="tinggi" style="margin-top:10px;">
                                <b>Tinggi Barang (Cm)</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" step="any" required value="@if(isset($data->tinggi)){{ $data->tinggi }} @else{{ old("tinggi") }}@endif" type="number" placeholder="Masukan tinggi Barang (cm)" name="tinggi" id="tinggi" />
                            
                            @if ($errors->has('tinggi'))
                            <label style="color: red">
                                {{ $errors->first('tinggi') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="n_volume" style="margin-top:10px;">
                                <b>Volume Barang (M3)</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" step="any" required value="{{ old("n_volume") }}" type="number" placeholder="Masukan Volume Barang (m3)" name="n_volume" id="n_volume" />
                            
                            @if ($errors->has('tinggi'))
                            <label style="color: red">
                                {{ $errors->first('tinggi') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="tarif" style="margin-top:10px;"> 
                                <b>Tarif Packing</b> <span class="span-required"></span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" step="any" readonly="true" value="{{ old("tarif") }}" type="number" placeholder="Masukan tarif Barang (Rp.)" name="tarif" id="tarif" />
                            
                            @if ($errors->has('tarif'))
                            <label style="color: red">
                                {{ $errors->first('tarif') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="n_borongan" style="margin-top:10px;"> 
                                <b>Tarif Borongan</b> <span class="span-required"></span>
                            </label>
                            
                            <div class="row"> 
                                <div class="col-md-2"> 
                                    <input type="checkbox" id="is_borongan" name="is_borongan" value="1" />
                                </div>
                                <div class="col-md-10">
                                    <input class="form-control m-input m-input--square" step="any" readonly="true" value="@if(isset($data->n_borongan)){{ $data->n_borongan }}@else{{ old("n_borongan") }}@endif" type="number" placeholder="Masukan tarif borongan (Rp.)" name="n_borongan" id="n_borongan" />
                                </div>
                            </div>
                            
                            @if ($errors->has('n_borongan'))
                            <label style="color: red">
                                {{ $errors->first('n_borongan') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="nominal" style="margin-top:10px;"> 
                                <b>Total Biaya Packing</b> <span class="span-required"> *</span>
                            </label>
                            
                            <input class="form-control m-input m-input--square" step="any" readonly="true" value="@if(isset($data->nominal)){{ $data->nominal }}@else{{ old("nominal") }}@endif" type="number" required name="nominal" id="nominal" />
                            
                            @if ($errors->has('nominal'))
                            <label style="color: red">
                                {{ $errors->first('nominal') }}
                            </label>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-8">
                            <label for="keterangan" style="margin-top:10px;"> 
                                <b>Keterangan Packing</b> <span class="span-required"></span>
                            </label>
                            <textarea class="form-control m-input m-input--square" name="keterangan" id="keterangan">@if(isset($data->keterangan)){{ $data->keterangan }}@else {{ old("keterangan") }}@endif</textarea>
                            
                            @if ($errors->has('keterangan'))
                            <label style="color: red">
                                {{ $errors->first('keterangan') }}
                            </label>
                            @endif
                        </div>
                    </div>
                    <div class="form-group text-right" >
                        <hr>
                        <button type="submit" class="btn btn-sm btn-success" id="modal-btn-si"><i class="fa fa-save"> </i> Simpan</button>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"> </i> Batal</span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script> 
    $("#id_tipe_kirim").val('{{ $data->id_tipe_kirim }}');
    $('#n_borongan').prop('disabled', true);
    
    function gOEdit(id){
        $("#modal-import").modal("show");
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('packingbarang') }}/"+id+"/editdetail",
            success: function(data) {
                $('#id_tipe_kirim').val(data.id_tipe_kirim);
                $('#id_jenis_packing').val(data.id_jenis_packing);
                $('#n_koli').val(data.koli);
                $('#panjang').val(data.panjang);
                $('#lebar').val(data.lebar);
                $('#tinggi').val(data.tinggi);
                $('#n_volume').val(data.volume);
                $('#tarif').val(data.tarif);
                $('#n_borongan').val(data.n_borongan);
                $('#nominal').val(data.n_total);

                if(data.is_borongan == 1){
                    $("#is_borongan").prop( "checked", true );
                    $('#n_borongan').prop('disabled', false);
                }else{
                    $("#is_borongan").prop( "checked", false );
                    $('#n_borongan').prop('disabled', true);
                }

                console.log(data);
                $('#tarif').prop("readonly", false);
                $('#form-import').attr('action', '{{ url("packingbarang") }}/'+id+"/updatedetail");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#tarif").val(0);
            }
        });

        $('#n_koli').prop('readonly', true);
        $('#panjang').prop('readonly', true);
        $('#lebar').prop('readonly', true);
        $('#tinggi').prop('readonly', true);
        $('#n_volume').prop('readonly', true);
    }

    function getTotal(){
        var tarif = parseFloat($("#tarif").val());
        var koli = parseInt($("#n_koli").val());
        var total = parseFloat(koli*tarif);

        if(total < 0 || isNaN(total)){
            total = 0;
        }
        if($("#is_borongan").is(":checked")){
            total = $("#n_borongan").val();
        }
        
        $("#nominal").val(total);
    }
    
    $("#tarif").keyup(function() {
        getTotal();
    });

    $("#is_borongan").change(function() {
        if ($(this).is(":checked")) {
            $('#n_borongan').prop('disabled', false);
            $('#n_borongan').attr('readonly', false);
            var total = $("#n_borongan").val();
            $("#nominal").val(total);
        } else{
            $('#n_borongan').prop('disabled', true);
            $('#n_borongan').attr('readonly', true);
            $('#n_borongan').val(0);
        }
        getTotal();
    });
    
    $("#n_borongan").keyup(function() {
        var n_borongan = parseFloat($("#n_borongan").val());
        
        if(n_borongan<0){
            $("#n_borongan").val(0);
        }
        
        $("#nominal").val(n_borongan);
    });
    
</script>
@endsection