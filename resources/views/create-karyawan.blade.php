<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="@if(Request::segment(2)=="create"){{ url('karyawan') }}@else{{ route('karyawan.update', $data->id_karyawan) }}@endif" enctype="multipart/form-data">
    @if(Request::segment(3)=="edit")
    {{ method_field("PUT") }}
    @endif

    @csrf
    <div class="row">
        <div class="form-group m-form__group col-md-6" style="margin-top: 15px">
            <label for="nm_karyawan">
                <b>Nama Karyawan</b> <span class="span-required">*</span>
            </label>

            <input type="text" class="form-control m-input m-input--square" id="nm_karyawan" name="nm_karyawan" required="required" maxlength="64" value="@if(isset($data->nm_karyawan)){{ $data->nm_karyawan }}@else{{ old("nm_karyawan") }}@endif">

            @if ($errors->has('nm_karyawan'))
            <label style="color: red">
                {{ $errors->first('nm_karyawan') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6">
            <label for="id_jenis">
                <b>Jenis Karyawan</b> <span class="span-required">*</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_jenis" name="id_jenis" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach($jenis as $key => $value)
                <option value="{{ $value->id_jenis }}">{{ strtoupper($value->nm_jenis) }}</option>
                @endforeach
            </select>

            @if ($errors->has('id_jenis'))
            <label style="color: red">
                {{ $errors->first('id_jenis') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6">
            <label for="jenis_kelamin">
                <b>Jenis Kelamin</b> <span class="span-required">*</span>
            </label>

            <select class="form-control m-input m-input--square" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="">-- Jenis Kelamin--</option>
                <option value="L">Laki - Laki</option>
                <option value="P">Perempuan</option>
            </select>

            @if ($errors->has('jenis_kelamin'))
            <label style="color: red">
                {{ $errors->first('jenis_kelamin') }}
            </label>
            @endif
        </div>


        <div class="form-group m-form__group col-md-6">
            <label for="id_perush">
                <b>Perusahaan Asal</b> <span class="span-required">*</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_perush" name="id_perush" required>
                @foreach($perusahaan as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
                @endforeach
            </select>

            @if ($errors->has('id_perush'))
            <label style="color: red">
                {{ $errors->first('id_perush') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6">
            <label for="no_hp">
                <b>No Telp / Hp / WA</b> <span class="span-required">*</span>
            </label>

            <input type="number" class="form-control m-input m-input--square" id="no_hp" name="no_hp" required="required" maxlength="16" value="@if(isset($data->no_hp)){{ $data->no_hp }}@else{{ old("no_hp") }}@endif">

            @if ($errors->has('no_hp'))
            <label style="color: red">
                {{ $errors->first('no_hp') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6">
            <label for="tgl_masuk">
                <b>Tanggal Masuk</b>
            </label>

            <input type="date" class="form-control m-input m-input--square" id="tgl_masuk" name="tgl_masuk" maxlength="16" value="@if(isset($data->tgl_masuk)){{ $data->tgl_masuk }}@else{{ old("tgl_masuk") }}@endif">

            @if ($errors->has('tgl_masuk'))
            <label style="color: red">
                {{ $errors->first('tgl_masuk') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6">
            <label for="id_jam_kerja">
                <b>Shift Jam Kerja</b> <span class="span-required">*</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_jam_kerja" name="id_jam_kerja" required>
                <option value="">-- Pilih Jam Kerja--</option>
                @foreach($jam as $key => $value)
                <option value="{{ $value->id_setting }}">{{ strtoupper($value->shift." ( ".$value->jam_masuk." - ".$value->jam_pulang." )") }}</option>
                @endforeach
            </select>

            @if ($errors->has('id_jam_kerja'))
            <label style="color: red">
                {{ $errors->first('id_jam_kerja') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6">
            <label for="id_mesin">
                <b>Mesin Absensi</b> <span class="span-required">*</span>
            </label>

            <select class="form-control m-input m-input--square" id="id_mesin" name="id_mesin" required>
                <option value="">-- Pilih Mesin Finger --</option>
                @foreach($mesin as $key => $value)
                <option value="{{ $value->id_mesin }}">{{ strtoupper($value->nm_mesin) }}</option>
                @endforeach
            </select>

            @if ($errors->has('id_mesin'))
            <label style="color: red">
                {{ $errors->first('id_mesin') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6">
            <label for="no_rekening">
                <b>ID Finger</b>
            </label>

            <input type="text" class="form-control m-input m-input--square" id="id_finger" name="id_finger" maxlength="16" value="@if(isset($data->id_finger)){{ $data->id_finger }}@else{{ old("id_finger") }}@endif">

            @if ($errors->has('id_finger'))
            <label style="color: red">
                {{ $errors->first('id_finger') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6" >
            <label for="no_rekening">
                <b>Jabatan</b>
            </label>

            <select class="form-control m-input m-input--square" id="id_jabatan" name="id_jabatan" required>
                <option value="">-- Pilih Jabatan --</option>
                @foreach($jabatan as $key => $value)
                <option value="{{ $value->id_jabatan }}">{{ strtoupper($value->nm_jabatan) }}</option>
                @endforeach
            </select>

            @if ($errors->has('id_jabatan'))
            <label style="color: red">
                {{ $errors->first('id_jabatan') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6 row">

            <div class="col-md-6">
                <label for="golongan">
                    <b>Golongan</b>
                </label>
                <input class="form-control" type="text" name="golongan" id="golongan" value="@if(isset($data->golongan)){{ $data->golongan }}@else{{ old("golongan") }}@endif" placeholder="ex : 1,2,3,4,5">
            </div>
            <div class="col-md-6">
                <label for="pangkat">
                    <b>Pangkat</b>
                </label>
                <input class="form-control" type="text" name="pangkat" id="pangkat" value="@if(isset($data->pangkat)){{ $data->pangkat }}@else{{ old("pangkat") }}@endif" placeholder="ex : A,B,C,D,E">
            </div>

            @if ($errors->has('id_jabatan'))
            <label style="color: red">
                {{ $errors->first('id_jabatan') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6" style="margin-left: 30px">
            <label for="no_rekening">
                <b>Status Karyawan</b>
            </label>

            <select class="form-control m-input m-input--square" id="id_status_karyawan" name="id_status_karyawan">
                <option value="">-- Pilih Status Karyawan --</option>
                @foreach($statuskaryawan as $key => $value)
                <option value="{{ $value->id_status_karyawan }}">{{ strtoupper($value->nm_status_karyawan) }}</option>
                @endforeach
            </select>

            @if ($errors->has('id_status_karyawan'))
            <label style="color: red">
                {{ $errors->first('id_status_karyawan') }}
            </label>
            @endif
        </div>

        <div class="form-group m-form__group col-md-6 row">

            <div class="col">
                <label for="golongan">
                    <b>Tgl Mulai Status karyawan</b>
                </label>
                <input type="date" class="form-control m-input m-input--square" id="tgl_mulai_sk" name="tgl_mulai_sk" maxlength="16" value="@if(isset($data->tgl_mulai_sk)){{ $data->tgl_mulai_sk }}@else{{ old("tgl_mulai_sk") }}@endif">
            </div>
            <div class="col">
                <label for="pangkat">
                    <b>Tgl Selesai Status karyawan</b>
                </label>
                <input type="date" class="form-control m-input m-input--square" id="tgl_selesai_sk" name="tgl_selesai_sk" maxlength="16" value="@if(isset($data->tgl_selesai_sk)){{ $data->tgl_selesai_sk }}@else{{ old("tgl_selesai_sk") }}@endif">
            </div>

            @if ($errors->has('id_jabatan'))
            <label style="color: red">
                {{ $errors->first('id_jabatan') }}
            </label>
            @endif
        </div>
        
        <div class="form-group m-form__group col-md-6 row" style="margin-left: 20px; margin-top:20px;">
            <div class="col">
                <label><input type="checkbox" value="1" id="is_sopir" name="is_sopir"> Sopir</label>
                @if ($errors->has('is_sopir'))
                <label style="color: red">
                    {{ $errors->first('is_sopir') }}
                </label>
                @endif
            </div>
            <div class="col">
                <label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif</label>
                @if ($errors->has('is_aktif'))
                <label style="color: red">
                    {{ $errors->first('is_aktif') }}
                </label>
                @endif
            </div>

            @if ($errors->has('id_jabatan'))
            <label style="color: red">
                {{ $errors->first('id_jabatan') }}
            </label>
            @endif
        </div>

        <div class="col-md-12 text-right">
            @include('template.inc_action')
        </div>
    </div>

</form>
