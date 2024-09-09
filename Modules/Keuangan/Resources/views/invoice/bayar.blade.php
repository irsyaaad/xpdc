@extends('template.document')

@section('data')
<form method="POST" action="{{ url(Request::segment(1)).'/bayar' }}" enctype="multipart/form-data">
    
    <div class="row">
        @csrf
        <div class="col-md-3">
            <label for="id_ac">Pilih Akun<span class="span-required"> *</span></label>
            
            <select class="form-control" id="id_ac" name="id_ac">
                <option value=""> -- Perkiraan Akun --</option>
                @foreach($akun as $key => $value)
                <option value="{{ $value->id_ac }}">{{ strtoupper($value->id_ac." - ".$value->nama) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_ac'))
            <label style="color: red">
                {{ $errors->first('id_ac') }}
            </label>
            @endif
            
        </div>
        <input type="hidden" name="id_invoice" value="{{$invoice->id_invoice}}">
        <input type="hidden" name="id_plgn" value="{{$invoice->id_plgn}}">
        <div class="col-md-3">
            <label for="date" >Nominal Bayar<span class="span-required"> *</span></label>
            <input type="number" class="form-control" id="n_bayar" name="n_bayar" value="{{$total}}" readonly required>
            @if ($errors->has('n_bayar'))
            <label style="color: red">
                {{ $errors->first('n_bayar') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3">
            <label for="date" >Tanggal Bayar<span class="span-required"> *</span></label>
            <input type="date" class="form-control" id="tgl" name="tgl" value="" required="required">
            
            @if ($errors->has('tgl'))
            <label style="color: red">
                {{ $errors->first('tgl') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3">
            <label for="info" >Info Bayar<span class="span-required"> *</span></label>
            <textarea class="form-control" id="info" placeholder="Masukan Info Bayar (Maks 150) Karakter ..." name="info" maxlength="150" style="min-height: 100px"  required></textarea>
            
            @if ($errors->has('info'))
            <label style="color: red">
                {{ $errors->first('info') }}
            </label>
            @endif
        </div>
    </div>
    
    <br>
    <div class="row " id="divbayar">
        
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
            <label for="nm_bayar" >Nama Pembayar<span class="span-required"> *</span></label>
            <input type="text" class="form-control" id="nm_bayar" name="nm_bayar" value="" >
            
            @if ($errors->has('nm_bayar'))
            <label style="color: red">
                {{ $errors->first('nm_bayar') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3">
            <label for="no_bayar" >Nomor Referensi </label>
            <input type="number" class="form-control" id="no_bayar" name="no_bayar" value="">
            
            @if ($errors->has('no_bayar'))
            <label style="color: red">
                {{ $errors->first('no_bayar') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3">
            <label style="font-weight: bold; color: white">
                Action
            </label>
            <div class="form-control" style="border: 0px; padding: 0px">
                <button type="submit" class="btn btn-md btn-success">
                    <span></span> <i class="fa fa-send"> </i> Bayar
                </button>
                
                <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning">
                    <span></span> <i class="fa fa-reply"> </i> Batal
                </a>

            </div>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-12">
            <h4><i class="fa fa-arrow-right"></i>
                <b>Data STT</b>
            </h4>
            
            @if(isset($detail))
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>ID STT</th>
                        <th>Kode STT</th>
                        <th>Pelanggan</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Total Piutang</th>
                        <th>Dibayar</th>
                        <th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $n_kurang = 0;
                    @endphp
                    @foreach ($detail as $key => $value)
                    <tr>
                        <td>@if(isset($value->id_stt)){{$value->id_stt}}
                            <input type="hidden" name="id_stt[]" id="id_stt" value="{{$value->id_stt}}">
                            @endif
                        </td>
                        <th>@if(isset($value->kode_stt)){{$value->kode_stt}}@endif</th>
                        <td>@if(isset($value->pelanggan->nm_pelanggan)){{$value->pelanggan->nm_pelanggan}}@endif</td>
                        <td>@if(isset($value->asal->nama_wil)){{$value->asal->nama_wil}}@endif</td>
                        <td>@if(isset($value->tujuan->nama_wil)){{$value->tujuan->nama_wil}}@endif</td>
                        @php
                        $n_kurang += $value->x_n_piut;
                        @endphp
                        <td>
                           {{ toRupiah($value->x_n_piut) }}
                        </td>
                        <input type="hidden" name="kurang[]" id="kurang" value="{{$value->x_n_piut}}">
                    </tr>
                    @endforeach
                    <hr>
                    <tr>
                        <td colspan="5" class="text-center">Total</td>
                        <td>
                            {{ toRupiah($value->x_n_piut) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
        <div class="col-md-4">
            
        </div>
    </div>
</form>
<script>
    var today = new Date().toISOString().split('T')[0];
    $("#tgl").val(today)
    var nm = '{{$invoice->nm_pelanggan}}';
    $("#nm_bayar").val(nm)
</script>
@endsection
