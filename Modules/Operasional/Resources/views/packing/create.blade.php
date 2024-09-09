@extends('template.document2')

@section('data')
<form method="POST" action="@if(Request::segment(3)=="edit"){{  url(Request::segment(1)."/".Request::segment(2)) }}"@else {{ url(Request::segment(1)) }}" @endif enctype="multipart/form-data">
    @csrf
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    <div class="row" style="margin-top: -1%">
        <div class="col-md-6" >
            <h5><b> > Data Packing : </b></h5>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning"><i class="fa fa-reply"> </i> Kembali </a>
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
            
            <select class="form-control" name="id_pelanggan" id="id_pelanggan" required> 
                @foreach($pelanggan as $key => $value)
                    <option value="{{ $value->id_pelanggan }}">{{ strtoupper($value->nm_pelanggan) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group col-md-3">
            <label for="nm_pengirim" style="margin-top:10px;">
                <b>Nama Pengirim</b> <span class="span-required"> *</span>
            </label>
            <input class="form-control m-input m-input--square" value="@if(isset($data->nm_pengirim)){{ $data->nm_pengirim }}@else {{ old("pengirim_nm") }} @endif" type="text" name="nm_pengirim" id="nm_pengirim" />
        </div>

        <div class="form-group col-md-3">
            <label for="no_awb" style="margin-top:10px;">
                <b>Tgl. Masuk Barang</b>
            </label>
            <input class="form-control" value="@if(isset($data->created_at)){{ dateindo($data->created_at) }}@endif" type="text"/>
        </div>

        <div class="form-group col-md-3">
            <label for="keterangan" style="margin-top:10px;">
                <b>Keterangan</b>
            </label>
            <textarea  class="form-control" id="keterangan" name="keterangan" style="height: 100px">@if(isset($data->keterangan)){{ $data->keterangan }}@endif</textarea>
        </div>

        <div class="form-group col-md-12 text-right">
            <button class="btn btn-md btn-success" type="submit"><i class="fa fa-save"> </i> Simpan</button>
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-danger"><i class="fa fa-times"> </i> Batal </a>
        </div>
    </div>
</form>
@endsection