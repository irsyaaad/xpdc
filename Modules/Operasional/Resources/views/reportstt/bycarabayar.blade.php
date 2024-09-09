@extends('template.document')

@section('data')
<form method="POST" action="{{ url(Request::segment(1)) }}">
    <div class="row">
        @csrf
        <div class="form-group m-form__group col-md-4">
            <label for="tgl_awal">
                <b>Tanggal Awal</b><span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control m-input m-input--square" name="tgl_awal" id="tgl_awal" required="required">
            
            @if ($errors->has('tgl_awal'))
            <label style="color: red">
                {{ $errors->first('tgl_awal') }}
            </label>
            @endif
        </div>
        
        <div class="form-group m-form__group col-md-4">
            <label for="tgl_akhir">
                <b>Tanggal Akhir</b><span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control m-input m-input--square" name="tgl_akhir" id="tgl_akhir" required="required">
            
            @if ($errors->has('tgl_akhir'))
            <label style="color: red">
                {{ $errors->first('tgl_akhir') }}
            </label>
            @endif
        </div>
        
        <div class="form-group m-form__group col-md-4">
            <button class="btn btn-md btn-primary" style="margin-top:30px">
                <i class="fa fa-search"></i> Cari
            </button>
            
            @if(isset($data))
            <a href="" class="btn btn-md btn-warning" style="margin-top:30px"><i class="fa fa-print"></i>  HTML</a>
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
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Tipe Kirim</th>
                    <th>No. Invoice</th>
                    <th>Total</th>
                    <th>Dibayar</th>
                    <th>Piutang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ strtoupper($value->id_stt) }}
                        </td>
                        <td>
                            {{ strtoupper($value->tgl_masuk) }}
                        </td>
                        <td>
                            {{ strtoupper($value->nm_pelanggan) }}
                        </td>
                        <td>
                            {{ strtoupper($value->pengirim_nm) }}
                        </td>
                        <td>
                            {{ strtoupper($value->penerima_nm) }}
                        </td>
                        <td>
                            {{ strtoupper($value->nm_tipe_kirim) }}
                        </td>
                        <td>

                        </td>
                        <td>
                            {{ strtoupper($value->c_total) }}
                        </td>
                        <td>
                            {{ strtoupper($value->x_n_bayar) }}
                        </td>
                        <td>
                            {{ strtoupper($value->x_n_piut) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection