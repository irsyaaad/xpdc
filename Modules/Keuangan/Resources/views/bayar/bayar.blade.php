<style>
    .col-md-4{
        margin-top: 15px;
    }
    
    .col-md-12{
        margin-top: 15px;
    }
    
    #divbayar{
        margin-top: 15px;
        border-radius: 10px;
        padding: 5px;
        padding-bottom: 10px;
    }
    
</style>
<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>Data STT</b>
        </h4>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div>
    
    <div class="col-md-12" style="margin-top:0.5%">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td width="10%">No. STT</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->id_stt)){{ strtoupper($data->kode_stt) }}@endif
                            </b>
                        </td>
                        
                        <td width="10%">Penerima</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->penerima_nm)){{ strtoupper($data->penerima_nm) }}@endif
                            </b>
                        </td>
                        
                        <td width="5%">Berat</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->n_berat)){{ strtoupper($data->n_berat) }}@endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="10%">Perusahaan Asal</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->perush_asal->nm_perush)){{ strtoupper($data->perush_asal->nm_perush) }}@endif
                            </b>
                        </td>
                        
                        <td width="10%"> Alamat Penerima</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->tujuan->nama_wil)){{ strtoupper($data->tujuan->nama_wil)." - ".$data->penerima_alm }}@endif
                            </b>
                        </td>
                        
                        <td width="5%">Volume</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->n_volume)){{ strtoupper($data->n_volume) }}@endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td width="10%">Pengirim</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->pengirim_nm)){{ strtoupper($data->pengirim_nm) }}@endif
                            </b>
                        </td>
                        
                        <td width="10%"> Tanggal Masuk</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->tgl_masuk)){{ daydate($data->tgl_masuk).", ".dateindo($data->tgl_masuk) }}@endif
                            </b>
                        </td>
                        
                        <td width="5%">Koli</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->n_koli)){{ strtoupper($data->n_koli) }}@endif
                            </b>
                        </td>
                    </tr>
                    
                    <tr>
                        <td width="10%"> Alamat Pengirim</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->asal->nama_wil)){{ strtoupper($data->asal->nama_wil)." - ".$data->pengirim_alm }}@endif
                            </b>
                        </td>
                        
                        <td width="10%">Status</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                @if(isset($data->status)){{ strtoupper($data->status->nm_ord_stt_stat) }}@endif
                            </b>
                        </td>
                        
                        <td width="5%">Total</td>
                        <td width="2%"><b>:</b></td>
                        <td>
                            <b>
                                
                                @if(isset($data->c_total))Rp. {{ number_format($data->c_total, 2, ',', '.') }}@endif
                            </b>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    <div class="col-md-12">
        <h4><i class="fa fa-chevron-right"></i>
            <b>Data Pembayaran</b>
        </h4>
    </div>
</div>

@if(Request::segment(3)=="edit")
<form  method="POST" action="{{ url(Request::segment(1), $bayar->id_order_pay) }}" enctype="multipart/form-data">
    {{ method_field("PUT") }}
    @else
    <form method="POST" action="{{ url(Request::segment(1)."/store"."/".Request::segment(2)) }}" enctype="multipart/form-data">
        @endif
        <div class="row">
            @csrf
            <div class="col-md-3">
                <input type="hidden" name="id_plgn" value="{{$data->id_plgn}}">
                <label for="ac4_d">Pilih Akun<span class="span-required"> *</span></label>
                
                <select class="form-control" id="ac4_d" name="ac4_d">
                    <option value=""> -- Perkiraan Akun --</option>
                    @foreach($akun as $key => $value)
                    <option value="{{ $value->id_ac }}">{{ strtoupper($value->id_ac." - ".$value->nama) }}</option>
                    @endforeach
                </select>
                
                @if ($errors->has('ac4_d'))
                <label style="color: red">
                    {{ $errors->first('ac4_d') }}
                </label>
                @endif
                
            </div>
            
            <div class="col-md-3">
                <label for="date" >Nominal Bayar<span class="span-required"> *</span></label>
                <input type="number" class="form-control" id="n_bayar" name="n_bayar" required>
                <input type="hidden" name="id_plgn" id="id_plgn" value="@if(isset($data->id_plgn)){{$data->id_plgn}} @endif">
                @if ($errors->has('n_bayar'))
                <label style="color: red">
                    {{ $errors->first('n_bayar') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-3">
                <label for="date" >Tanggal Bayar<span class="span-required"> *</span></label>
                <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar" value="@if(isset($data->tgl)){{$data->tgl}} @else {{ old("tgl_bayar") }} @endif" required="required">
                
                @if ($errors->has('tgl_bayar'))
                <label style="color: red">
                    {{ $errors->first('tgl_bayar') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-3">
                <label for="info" >Info Bayar<span class="span-required"> *</span></label>
                <textarea class="form-control" id="info" placeholder="Masukan Info Bayar (Maks 150) Karakter ..." name="info" maxlength="150" style="min-height: 100px"  required>@if(isset($data->info)){{$data->info}} @else {{ "Pembayaran STT ".strtoupper($data->kode_stt)." Atas Nama ".strtoupper($data->pengirim_nm) }} @endif</textarea>
                
                @if ($errors->has('info'))
                <label style="color: red">
                    {{ $errors->first('info') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="row border border-default" id="divbayar">
            
            <div class="col-md-3">
                <label for="id_cr_byr" >Pilih Cara Bayar <span class="span-required"> *</span></label>
                
                <select class="form-control" id="id_cr_byr" name="id_cr_byr">
                    <option value=""> -- Cara Bayar --</option>
                    @foreach($cara as $key => $value)
                    @if(strtoupper($value->id_cr_byr_o)!="BYTJ")
                    <option value="{{ $value->id_cr_byr_o }}">{{ strtoupper($value->nm_cr_byr_o) }}</option>
                    @endif
                    @endforeach
                </select>
                
                @if ($errors->has('id_cr_byr'))
                <label style="color: red">
                    {{ $errors->first('id_cr_byr') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-3">
                <label for="no_bayar" >Nomor <span class="span-required"> *</span></label>
                <input type="number" class="form-control" id="no_bayar" name="no_bayar" value="@if(isset($data->no_bayar)){{$data->no_bayar}} @else {{ old("no_bayar") }} @endif">
                
                @if ($errors->has('no_bayar'))
                <label style="color: red">
                    {{ $errors->first('no_bayar') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-3">
                <label for="nm_bayar" >Nama<span class="span-required"> *</span></label>
                <input type="nama_rek" class="form-control" id="nm_bayar" name="nm_bayar" value="@if(isset($data->nm_bayar)){{$data->nm_bayar}} @else {{ old("nm_bayar") }} @endif" >
                
                @if ($errors->has('nm_bayar'))
                <label style="color: red">
                    {{ $errors->first('nm_bayar') }}
                </label>
                @endif
            </div>
            
            <div class="col-md-3">
                <label for="tgl_bg" >Tanggal Bayar<span class="span-required"> *</span></label>
                <input type="date" class="form-control" id="tgl_bg" name="tgl_bg" value="@if(isset($data->tgl_bg)){{$data->tgl_bg}} @else {{ old("tgl_bg") }} @endif">
                
                @if ($errors->has('tgl_bg'))
                <label style="color: red">
                    {{ $errors->first('tgl_bg') }}
                </label>
                @endif
            </div>
        </div>
        
        <div class="row">
            <input type="hidden" id="id_stt" name="id_stt" value="{{ $data->id_stt }}" />
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-md btn-success">
                    <span></span> <i class="fa fa-send"> </i> Bayar
                </button>
            </div>
        </div>
    </form>
    @section("script")
    
    <script>
        // hide
        $("#divbayar").hide();
        // cek if exists total
        @if(isset($data->x_n_piut) and $data->x_n_piut > 0)
        $("#n_bayar").val({{ $data->x_n_piut }});
        @else
        $("#n_bayar").val({{ $data->c_total }});
        @endif
        
        // today date
        var today = new Date().toISOString().split('T')[0];
        @if(isset($data->tgl)){{ $data->tgl }}@else $("#tgl").val(today); @endif
        @if(isset($data->tgl_bg)){{ $data->tgl_bg }}@else $("#tgl_bg").val(today); @endif
        
        @if($data->id_cr_byr=="cash" or strtoupper($data->id_cr_byr)=="bytj")
        $("#divbayar").hide();
        @else
        $("#divbayar").show();
        @endif
        
        @if(Request::segment(3)=="edit")
        $("#id_ac").val('{{ $bayar->ac4_d }}');
        $("#id_cr_byr").val('{{ $bayar->id_cr_byr }}');
        $("#n_bayar").val('{{ $bayar->n_bayar }}');
        $("#tgl").val('{{ $bayar->tgl }}');
        $("#info").val('{{ $bayar->info }}');
        $("#no_bayar").val('{{ $bayar->no_bayar }}');
        $("#nm_bayar").val('{{ $bayar->nm_bayar }}');
        $("#tgl_bg").val('{{ $bayar->tgl_bg }}');
        @endif
        
    </script>
    
    @endsection
    