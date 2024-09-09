<div class="col-md-3">
	<label style="font-weight: bold;">
		Nomor DM
	</label>
	<select class="form-control" id="id_dm" name="id_dm"></select>
</div>

<div class="col-md-3">
    <label style="font-weight : bold ">
        Vendor Luar
    </label>
    <select class="form-control" id="id_ven" name="id_ven">
        <option value="">-- Pilih Vendor Luar --</option>
        @foreach($vendor as $key => $value)
        <option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label style="font-weight : bold ">
        Asal
    </label>
    <select class="form-control" id="f_asal" name="f_asal">
        <option value=""> -- Pilih Wilayah Asal --</option>
        @foreach($wilayah as $key => $value)
        <option value="{{ $value->id_wil }}">{{ $value->nama_wil }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label style="font-weight : bold ">
        Tujuan
    </label>
    <select class="form-control" id="f_tujuan" name="f_tujuan">
        <option value=""> -- Pilih Wilayah Tujuan --</option>
        @foreach($wilayah as $key => $value)
        <option value="{{ $value->id_wil }}">{{ $value->nama_wil }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Layanan
    </label>
    <select class="form-control" id="f_layanan" name="f_layanan">
        <option value="">-- Pilih Layanan --</option>
        @foreach($layanan as $key => $value)
        <option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3 mt-1">
    <label style="font-weight: bold;">
        Tanggal Berangkat
    </label>
    <input type="date" name="tglberangkat" id="tglberangkat" class="form-control" value="@if(isset($filter["tglberangkat"])){{ $filter["tglberangkat"] }}@endif" placeholder="Cari Tanggal">
</div>

<div class="col-md-3 mt-1">
    <label style="font-weight: bold;">
        Tanggal Tiba
    </label>
    <input type="date" name="tglsampai" id="tglsampai" class="form-control" value="@if(isset($filter["tglsampai"])){{ $filter["tglsampai"] }}@endif" placeholder="Cari Tanggal">
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
		Action
	</label>
	<select class="form-control" id="is_tiba" name="is_tiba">
		<option value="">-- Semua Data Dm --</option>
        <option value="1">DM ( BELUM ) TIBA</option>
        <option value="2">DM ( SUDAH ) TIBA</option>
	</select>
</div>
<div class="col-md-3 mt-1">
	<label style="font-weight: bold;">
		Stt
	</label>
	<select class="form-control" id="filterstt" name="filterstt"></select>
</div>

<div class="col-md-12 text-right">
    <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
    <div class="dropdown d-inline-block">

    </div>
</div>
