<div class="col-md-4">
    <label style="font-weight: bold;">
        Perusahaan / Devisi
    </label>
    <select class="form-control" id="f_id_perush" name="f_id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($role_perush as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label style="font-weight: bold;">
        Tanggal Awal Libur
    </label>
    <input class="form-control" id="f_dr_tgl" name="f_dr_tgl" value="@if(isset($filter["f_dr_tgl"])){{ $filter["f_dr_tgl"] }}@endif" placeholder="Masukan Tanggal Akhir Libur" type="date"/>
</div>

<div class="col-md-4">
    <label style="font-weight: bold;">
        Tanggal Akhir Libur
    </label>
    <input class="form-control" id="f_sp_tgl" name="f_sp_tgl" value="@if(isset($filter["f_sp_tgl"])){{ $filter["f_sp_tgl"] }}@endif" placeholder="Masukan Tanggal Akhir Libur" type="date"/>
</div>

<div class="col-md-12 text-right mt-2">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
    <button type="button" class="btn btn-sm btn-success" onclick="goSetting()"><i class="fa fa-copy"></i>Copy</button>
    <a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
</div>