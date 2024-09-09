
@extends('template.document')

@section('data')


<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('dmvendor') }}@else{{ url('dmvendor', $data->id_dm) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    @csrf
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="id_ven">
                <b>Vendor</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_ven" name="id_ven">
                <option value="">-- Pilih Vendor --</option>
                @foreach($vendor as $key => $value)
                    <option value="{{ $value->id_ven }}">{{ $value->nm_ven }}</option>
                @endforeach
            </select>
            
            <input type="hidden" name="nm_ven_2" id="nm_ven_2">
            
            @if ($errors->has('id_ven'))
            <label style="color: red">
                {{ $errors->first('id_ven') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="id_layanan">
                <b>Layanan</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control" id="id_layanan" name="id_layanan">
                @foreach($layanan as $key => $value)
                <option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_layanan'))
            <label style="color: red">
                {{ $errors->first('id_layanan') }}
            </label>
            @endif
        </div>

        <div class="col-md-3 mt-2">
            <label for="id_wil_asal">
                <b>Wilayah Asal</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control" id="id_wil_asal" name="id_wil_asal">
                <option> -- Pilih Wilayah Asal --</option>
                @foreach($wilayah as $key => $value)
                    <option value="{{ $value->value }}">{{ $value->label }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_wil_asal'))
            <label style="color: red">
                {{ $errors->first('id_wil_asal') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="id_wil">
                <b>Wilayah Tujuan</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control" id="id_wil" name="id_wil">
                <option> -- Pilih Wilayah Tujuan --</option>
                @foreach($wilayah as $key => $value)
                    <option value="{{ $value->value }}">{{ $value->label }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_wil'))
            <label style="color: red">
                {{ $errors->first('id_wil') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="tgl_berangkat">
                <b>Rencana Berangkat</b> <span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control" name="tgl_berangkat" id="tgl_berangkat" maxlength="16" value="@if(old("tgl_berangkat")!=null){{ old("tgl_berangkat") }}@elseif(isset($data->tgl_berangkat)){{ $data->tgl_berangkat }}@endif" required="required" style="background-color: #fff">
            
            @if ($errors->has('tgl_berangkat'))
            <label style="color: red">
                {{ $errors->first('tgl_berangkat') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="tgl_sampai">
                <b>Estimasi Sampai</b> <span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control" name="tgl_sampai" id="tgl_sampai" maxlength="16" value="@if(old("tgl_sampai")!=null){{ old("tgl_sampai") }}@elseif(isset($data->tgl_sampai)){{ $data->tgl_sampai }}@endif" required="required" style="background-color: #fff">
            
            @if ($errors->has('tgl_sampai'))
            <label style="color: red">
                {{ $errors->first('tgl_sampai') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="nm_dari">
                <b>Dari Pelabuhan</b>
            </label>
            
            <input type="text" class="form-control" name="nm_dari" id="nm_dari" value="@if(old("nm_dari")!=null){{ old("nm_dari") }}@elseif(isset($data->nm_dari)){{ $data->nm_dari }}@endif" style="background-color: #fff">
            
            @if ($errors->has('nm_dari'))
            <label style="color: red">
                {{ $errors->first('nm_dari') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="nm_tuju">
                <b>Ke Pelabuhan</b>
            </label>
            
            <input type="text" class="form-control" name="nm_tuju" id="nm_tuju" value="@if(old("nm_tuju")!=null){{ old("nm_tuju") }}@elseif(isset($data->nm_tuju)){{ $data->nm_tuju }}@endif" style="background-color: #fff">
            
            @if ($errors->has('nm_tuju'))
            <label style="color: red">
                {{ $errors->first('nm_tuju') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="nm_pj_dr">
                <b>Nama PJ Asal</b> <span class="span-required"> *</span>
            </label>
            
            <input type="text" class="form-control" name="nm_pj_dr" id="nm_pj_dr" maxlength="64" value="@if(isset($data->nm_pj_dr)){{ $data->nm_pj_dr }}@else{{ Auth::user()->nm_user }}@endif" required="required" style="background-color: #fff">
            
            @if ($errors->has('nm_pj_dr'))
            <label style="color: red">
                {{ $errors->first('nm_pj_dr') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="nm_pj_tuju">
                <b>Nama PJ Tujuan</b> <span class="span-required"></span>
            </label>
            
            <input type="text" class="form-control" name="nm_pj_tuju" id="nm_pj_tuju" maxlength="64" value="@if(old("nm_pj_tuju")!=null){{ old("nm_pj_tuju") }}@elseif(isset($data->nm_pj_tuju)){{ $data->nm_pj_tuju }}@endif" style="background-color: #fff;">
            
            @if ($errors->has('nm_pj_tuju'))
            <label style="color: red">
                {{ $errors->first('nm_pj_tuju') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2">
            <label for="cara_hitung">
                <b>Cara Hitung</b> <span class="span-required"> *</span>
            </label>
            <br>
            <label style="margin-left:20px"><input type="radio" id="cara" name="cara" value="1"  /> Kg</label>
            <label style="margin-left:20px"><input type="radio" id="cara" name="cara" value="2"  /> Kgv</label>
            <label style="margin-left:20px"><input type="radio" id="cara" name="cara" value="3"  /> M3</label>
            <label style="margin-left:20px"><input type="radio" id="cara" name="cara" value="4"  /> Borongan</label>
        </div>

        <div class="col-md-3 mt-2">
            <label for="n_harga">
                <b>Harga</b> <span class="span-required"> *</span>
            </label>
            
            <input type="text" class="form-control" name="n_harga" id="n_harga" maxlength="16" value="@if(old("n_harga")!=null){{ old("n_harga") }}@elseif(isset($data->n_harga)){{ $data->n_harga }}@endif" style="background-color: #fff">
            
            @if ($errors->has('n_harga'))
            <label class="text-danger">
                {{ $errors->first('n_harga') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2" id="lbl_container">
            <label for="no_container">
                <b>No. Container</b> <span class="span-required"> *</span>
            </label>
            
            <input type="text" class="form-control" name="no_container" id="no_container" maxlength="128" value="@if(old("no_container")!=null){{ old("no_container") }}@elseif(isset($data->no_container)){{ $data->no_container }}@endif" style="background-color: #fff" >
            
            @if ($errors->has('no_container'))
            <label style="color: red">
                {{ $errors->first('no_container') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-2" id="lbl_seal">
            <label for="no_seal">
                <b>No. Seal</b> <span class="span-required"> *</span>
            </label>
            
            <input type="text" class="form-control" name="no_seal" id="no_seal" maxlength="128" value="@if(old("no_seal")!=null){{ old("no_seal") }}@elseif(isset($data->no_seal)){{ $data->no_seal }}@endif" style="background-color: #fff">
            
            @if ($errors->has('no_seal'))
            <label style="color: red">
                {{ $errors->first('no_seal') }}
            </label>
            @endif
        </div>

        <div class="col-md-6 mt-1">
            <label for="keterangan">
                <b>Ketarangan</b>
            </label>

            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="10">
                @if(isset($data->keterangan)){{ $data->keterangan }}@else{{ old("keterangan") }}@endif
            </textarea>

            @if ($errors->has('keterangan'))
            <label style="color: red">
                {{ $errors->first('keterangan') }}
            </label>
            @endif
        </div>

        <div class="col-md-12 text-right" style="margin-top: -1%;">
            @include('template.inc_action')
        </div>
    </div>
</form>

@endsection

@section('script')
@include('operasional::daftarmuat.jsdmvendor')
@endsection