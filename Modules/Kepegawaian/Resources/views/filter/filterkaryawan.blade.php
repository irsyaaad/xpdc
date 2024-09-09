<div class="col-md-3">
    <label style="font-weight : bold">
        Perusahaan Asal
    </label>
    <select class="form-control" id="f_id_perush" name="f_id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach ($perusahaan as $key => $value)
            <option value="{{ $value->id_perush }}" {{ $filter['f_id_perush'] == $value->id_perush ? 'selected' : '' }} >{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label style="font-weight : bold">
        Karyawan
    </label>
    <select class="form-control" id="f_karyawan" name="f_karyawan">
        <option value="">-- Pilih Karyawan --</option>
        @foreach ($karyawan as $key => $value)
            <option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label style="font-weight : bold">
        Status Aktif
    </label>
    <select class="form-control" id="f_is_aktif" name="f_is_aktif">
        <option value="">-- Pilih Status --</option>
        <option value="1">Aktif</option>
        <option value="0">Non Aktif</option>
    </select>
</div>

<div class="col-md-3" style="margin-top: 30px">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"
        onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top"
        title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
</div>
