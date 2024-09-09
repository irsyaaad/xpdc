<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan
    </label>
    <select class="form-control" id="filterperush" name="filterperush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($role_perush as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>
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
            @foreach($karyawan as $key => $value)
            <option value="{{ $value->id_karyawan }}">{{ ucfirst($value->nm_karyawan) }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-md-none m--margin-bottom-10"></div>
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Awal Tanggal Piutang
    </label>
    <input type="date" class="form-control" name="f_tgl_awal" id="f_tgl_awal" >
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Akhir Tanggal Piutang 
    </label>
    <input type="date" class="form-control" name="f_tgl_akhir" id="f_tgl_akhir">
</div>
<div class="col-md-12 text-right mt-2">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>