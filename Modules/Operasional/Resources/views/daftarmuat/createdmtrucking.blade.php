<form method="POST" action="@if(Request::segment(2)=="create"){{ url('dmtrucking') }}@else{{ url('dmtrucking', $data->id_dm) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }} 
    @endif
    @csrf
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="id_dm">
                <b>No. Manifest</b> <span class="span-required"> *</span>
            </label>
            
            <input type="text" class="form-control" name="id_dm" id="id_dm" maxlength="16" value="@if(isset($data->kode_dm)){{ $data->kode_dm }}@else{{ old("id_dm") }}@endif" readonly="readonly" style="background-color: #e8edf0">
            
            @if ($errors->has('id_dm'))
            <label style="color: red">
                {{ $errors->first('id_dm') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="id_perush_tj">
                <b>Perusahaan Tujuan</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_perush_tj" name="id_perush_tj">
                <option value=""> -- Pilih Perusahaan --</option>
                @foreach($perush_tj as $key => $value)
                <option value="{{ strtoupper($value->id_perush) }}"> {{ strtoupper($value->nm_perush) }} </option>
                @endforeach
            </select>
            
            <input type="hidden" name="nm_tujuan" id="nm_tujuan">
            
            @if ($errors->has('id_perush_tj'))
            <label style="color: red">
                {{ $errors->first('id_perush_tj') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="id_layanan">
                <b>Layanan</b> <span class="span-required"> *</span>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_layanan" name="id_layanan">
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
        
        <div class="col-md-3 mt-1">
            <label for="id_kapal">
                <b>Nama Kapal</b>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_kapal" name="id_kapal">
                <option value="">-- Pilih Kapal --</option>
                @foreach($kapal as $key => $value)
                <option value="{{ $value->id_kapal }}">{{ strtoupper($value->nm_kapal) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_kapal'))
            <label style="color: red">
                {{ $errors->first('id_kapal') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="id_sopir">
                <b>Nama Sopir</b>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_sopir" name="id_sopir">
                <option value="">-- Pilih Sopir --</option>
                @foreach($sopir as $key => $value)
                <option value="{{ $value->id_sopir }}">{{ strtoupper($value->nm_sopir) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_sopir'))
            <label style="color: red">
                {{ $errors->first('id_sopir') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="id_armada">
                <b>Armada</b>
            </label>
            
            <select class="form-control m-input m-input--square" id="id_armada" name="id_armada">
                <option value="">-- Pilih Armada --</option>
                @foreach($armada as $key => $value)
                <option value="{{ $value->id_armada }}">{{ strtoupper($value->nm_armada." - ".$value->no_plat) }}</option>
                @endforeach
            </select>
            
            @if ($errors->has('id_armada'))
            <label style="color: red">
                {{ $errors->first('id_armada') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="tgl_berangkat">
                <b>Rencana Berangkat</b> <span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control" name="tgl_berangkat" id="tgl_berangkat" maxlength="16" value="@if(isset($data->tgl_berangkat)){{ $data->tgl_berangkat }}@else{{ old("tgl_berangkat") }}@endif" required="required" style="background-color: #fff">
            
            @if ($errors->has('tgl_berangkat'))
            <label style="color: red">
                {{ $errors->first('tgl_berangkat') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="tgl_sampai">
                <b>Estimasi Sampai</b> <span class="span-required"> *</span>
            </label>
            
            <input type="date" class="form-control" name="tgl_sampai" id="tgl_sampai" maxlength="16" value="@if(isset($data->tgl_sampai)){{ $data->tgl_sampai }}@else{{ old("tgl_sampai") }}@endif" required="required" style="background-color: #fff">
            
            @if ($errors->has('tgl_sampai'))
            <label style="color: red">
                {{ $errors->first('tgl_sampai') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="nm_dari">
                <b>Dari Pelabuhan</b>
            </label>
            
            <input type="text" class="form-control" name="nm_dari" id="nm_dari" value="@if(isset($data->nm_dari)){{ $data->nm_dari }}@else{{ old("nm_dari") }}@endif" style="background-color: #fff">
            
            @if ($errors->has('nm_dari'))
            <label style="color: red">
                {{ $errors->first('nm_dari') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
            <label for="nm_tuju">
                <b>Ke Pelabuhan</b>
            </label>
            
            <input type="text" class="form-control" name="nm_tuju" id="nm_tuju" value="@if(isset($data->nm_tuju)){{ $data->nm_tuju }}@else{{ old("nm_tuju") }}@endif" style="background-color: #fff">
            
            @if ($errors->has('nm_tuju'))
            <label style="color: red">
                {{ $errors->first('nm_tuju') }}
            </label>
            @endif
        </div>
        
        <div class="col-md-3 mt-1">
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
        
        <div class="col-md-3 mt-1">
            <label for="nm_pj_tuju">
                <b>Nama PJ Tujuan</b> <span class="span-required"></span>
            </label>
            
            <input type="text" class="form-control" name="nm_pj_tuju" id="nm_pj_tuju" maxlength="64" value="@if(isset($data->nm_pj_tuju)){{ $data->nm_pj_tuju }}@else{{ old("nm_pj_tuju") }}@endif" style="background-color: #fff;">
            <input  type="hidden" name="id_penerima" id="id_penerima"/>
            
            @if ($errors->has('nm_pj_tuju'))
            <label style="color: red">
                {{ $errors->first('nm_pj_tuju') }}
            </label>
            @endif
        </div>

        <div class="col-md-6 mt-1">
            <label for="keterangan">
                <b>Ketarangan</b>
            </label>

            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="10">@if(isset($data->keterangan)){{ $data->keterangan }}@else{{ old("keterangan") }}@endif</textarea>

            @if ($errors->has('keterangan'))
            <label style="color: red">
                {{ $errors->first('keterangan') }}
            </label>
            @endif
        </div>
        <div class="col-md-12 text-right mt-2">
            @include('template.inc_action')
        </div>
    </div>
</form>