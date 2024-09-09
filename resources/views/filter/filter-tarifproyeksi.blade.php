<div class="col-md-3">
    <label style="font-weight: bold;">
        Perusahaan tujuan
    </label>
    <select class="form-control" id="f_id_perush_tj" name="f_id_perush_tj">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($perush as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label style="font-weight: bold;">
        Layanan
    </label>
    <select class="form-control" id="f_id_layanan" name="f_id_layanan">
        <option value="">-- Pilih Layanan --</option>
        @foreach($layanan as $key => $value)
        <option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-6 text-left" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<a href="{{ url(Request::segment(1)."/create") }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Proyeksi</a>
</div>