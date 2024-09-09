<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan
    </label>
    <select class="form-control" id="f_id_perush" name="f_id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($perusahaan as $key => $value)
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
            <option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-md-none m--margin-bottom-10"></div>
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Dari tanggal
    </label>
    <input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter["dr_tgl"])){{ $filter["dr_tgl"] }}@endif">
</div>
<div class="col-md-3">
    <label style="font-weight: bold;">
        Sampai tanggal
    </label>
    <input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter["sp_tgl"])){{$filter["sp_tgl"]}}@endif">
</div>

{{-- @if($rm->c_insert==true or $rm->c_other==true) --}}
<div class="col-md-12 text-right" style="margin-top: 10px">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i> Refresh</a>
    <div class="dropdown d-inline-block">
        @if(get_admin())
        <a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-sm btn-accent" id="download"><i class="fa fa-plus"></i> Tambah Absensi</a>
        @endif
        <div class="dropdown d-inline-block">
			<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-download"></i> Download Data
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="#" class="dropdown-item" onclick="goDownload()"><i class="fa fa-building" aria-hidden="true"></i>  By Perusahaan</a>
				{{-- <a href="#" class="dropdown-item" onclick="goDownloadMesin()"><i class="fa fa-gear" aria-hidden="true"></i>  By Mesin Finger</a> --}}
			</div>
		</div>
        <button type="button" class="btn btn-sm btn-info" id="download" onclick="goPindah()"><i class="fa fa-retweet"></i> Pindah Absensi</button>
    </div>
</div>
{{-- @endif --}}
