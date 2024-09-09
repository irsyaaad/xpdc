<div class="col-md-3">
    <label style="font-weight: bold;">
        Nomor DM Kota - Kota
    </label>
    <select class="form-control" id="id_dm" name="id_dm"></select>
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Kota Tujuan
    </label>
    <select class="form-control" id="id_wil_tujuan" name="id_wil_tujuan"></select>
</div>

@if(get_admin())
<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan asal
    </label>
    <select class="form-control" id="id_perush" name="id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($perusahaan as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>
</div>
@endif

<div class="col-md-3">
    <label style="font-weight: bold;">
        Sopir
    </label>
    <select class="form-control" id="id_sopir" name="id_sopir">
        <option value="">-- Semua Sopir --</option>
        @foreach($sopir as $key => $value)
        <option value="{{ $value->id_sopir }}">{{ $value->nm_sopir }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3 mt-1">
    <label style="font-weight: bold;">
        Armada
    </label>
    <select class="form-control" id="id_armada" name="id_armada">
        <option value="">-- Semua Armada --</option>
        @foreach($armada as $key => $value)
        <option value="{{ $value->id_armada }}">{{ $value->nm_armada }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3 mt-1">
    <label style="font-weight: bold;">
        Status DM
    </label>
    <select class="form-control" id="id_status" name="id_status">
        <option value="">-- Semua Status --</option>
        @foreach($status as $key => $value)
        <option value="{{ $value->id_status }}">{{ $value->nm_status }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3 mt-1">
    <label style="font-weight: bold;">
        Dari Tanggal Berangkat
    </label>
    <input type="date" name="dr_tgl" id="dr_tgl" class="form-control" value="@if(isset($filter["dr_tgl"])){{ $filter["dr_tgl"] }}@endif" placeholder="dari Tanggal Berangkat ...">
</div>

<div class="col-md-3 mt-1">
    <label style="font-weight: bold;">
        Sampai Tanggal Berangkat
    </label>
    <input type="date" name="sp_tgl" id="sp_tgl" class="form-control" value="@if(isset($filter["sp_tgl"])){{ $filter["sp_tgl"] }}@endif" placeholder="sampai Tanggal Berangkat ...">
</div>

<div class="col-md-3 mt-5">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
    {{-- <div class="dropdown d-inline-block">
        <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-print"> </i> Cetak Dm
        </button>
    </div> --}}
</div>




