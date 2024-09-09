@extends('template.document')

@section('data')
<form method="POST" action="{{ url(Request::segment(1)) }}/filter">
    <div class="row">
        @csrf
        <div class="form-group m-form__group col-md-3">
            <label for="tgl_awal">
                <b>Tanggal Awal</b><span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control m-input m-input--square" name="tgl_awal" id="tgl_awal" value="@if(isset($dr_tgl)){{$dr_tgl}}@endif">
            
            @if ($errors->has('tgl_awal'))
            <label style="color: red">
                {{ $errors->first('tgl_awal') }}
            </label>
            @endif
        </div>
        
        <div class="form-group m-form__group col-md-3">
            <label for="tgl_akhir">
                <b>Tanggal Akhir</b><span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control m-input m-input--square" name="tgl_akhir" id="tgl_akhir" value="@if(isset($sp_tgl)){{$sp_tgl}}@endif">
            
            @if ($errors->has('tgl_akhir'))
            <label style="color: red">
                {{ $errors->first('tgl_akhir') }}
            </label>
            @endif
        </div>
        
        <div class="form-group m-form__group col-md-6">
            <button class="btn btn-md btn-primary" style="margin-top:30px">
                <i class="fa fa-search"></i> Cari
            </button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" style="margin-top:30px"><i class="fa fa-refresh"></i>  Refresh</a>
            
            @if(isset($data))
            <a href="" class="btn btn-md btn-info" style="margin-top:30px"><i class="fa fa-print"></i>  HTML</a>
            <a href="" class="btn btn-md btn-success" style="margin-top:30px"><i class="fa fa-print"></i>  EXCEL</a>
            @endif
        </div>
    </div>
</form>
@if(isset($data))
<div class="row">
    <div class="col-md-12">
        <table class="table table-responsive table-stripped" width="100%" style="margin-top: 2%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. STT</th>
                    <th>Tgl Masuk</th>
                    <th>Pelanggan</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Tipe Kirim</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ strtoupper($value->kode_stt) }}
                        </td>
                        <td>
                            {{ dateindo($value->tgl_masuk) }}
                        </td>
                        <td>
                            @isset($value->pelanggan->nm_pelanggan)
                                {{ strtoupper($value->pelanggan->nm_pelanggan) }}
                            @endisset
                        </td>
                        <td>
                            @isset($value->asal->nama_wil)
                                {{ strtoupper($value->asal->nama_wil) }}
                            @endisset
                        </td>
                        <td>
                            @isset($value->tujuan->nama_wil)
                                {{ strtoupper($value->tujuan->nama_wil) }}
                            @endisset
                        </td>
                        <td>
                            @isset($value->tipekirim->nm_tipe_kirim)
                                {{ strtoupper($value->tipekirim->nm_tipe_kirim) }}
                            @endisset
                        </td>
                        <td>
                            {{ number_format($value->c_total, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection