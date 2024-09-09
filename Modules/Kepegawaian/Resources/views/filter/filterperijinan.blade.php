<div class="col-md-3">
    <div class="m-form__label">
        <label style="font-weight: bold;">
            Perusahaan
        </label>
    </div>
    <div class="m-form__control">
        <select class="form-control" id="f_id_perush" name="f_id_perush">
            <option value="">-- Pilih Perusahaan --</option>
            @foreach ($perush as $key => $value)
                <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="m-form__label">
        <label style="font-weight: bold;">
            Karyawan
        </label>
    </div>
    <div class="m-form__control">
        <select class="form-control" id="f_id_karyawan" name="f_id_karyawan">
            <option value="">-- Pilih Karyawan --</option>
            @foreach ($karyawan as $key => $value)
                <option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-md-none m--margin-bottom-10"></div>
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Tgl Pengajuan (Dari)
    </label>
    <input type="date" class="form-control" name="f_dr_tgl" id="f_dr_tgl"
        value="@if(isset($filter['f_dr_tgl']) and $filter['f_dr_tgl'] != null){{ $filter['f_dr_tgl'] }}@endif">
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Tgl Pengajuan (Sampai)
    </label>
    <input type="date" class="form-control" name="f_sp_tgl" id="f_sp_tgl"
        value="@if(isset($filter['f_sp_tgl']) and $filter['f_sp_tgl'] != null){{ $filter['f_sp_tgl'] }}@endif">
</div>

<div class="col-md-3">
    <div class="m-form__label">
        <label style="font-weight: bold;">
            Jenis Perijinan
        </label>
    </div>
    <div class="m-form__control">
        <select class="form-control" id="f_id_jenis" name="f_id_jenis">
            <option value="">-- Jenis Perijinan --</option>
            @foreach ($jenis as $key => $value)
                <option value="{{ $value->id_jenis }}">{{ strtoupper($value->nm_jenis) }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-md-none m--margin-bottom-10"></div>
</div>

<div class="col-md-9" style="margin-top: 30px">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"
        onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top"
        title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>

    <div class="dropdown d-inline-block">
        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-plus"> </i> Tambah Ijin
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ url(Request::segment(1) . '/create') }}">Ijin Karyawan</a>
            <a class="dropdown-item" href="{{ url(Request::segment(1) . '/creategroup') }}">Ijin Group Karyawan</a>
        </div>
    </div>
    <button type="button" onclick="goKonfirmasi()" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Konfirmasi
        Ijin</button>
</div>
