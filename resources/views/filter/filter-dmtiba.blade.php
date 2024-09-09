<div class="col-md-3">
	<label style="font-weight: bold;">
		Nomor DM
	</label>
	<select class="form-control" id="id_dm" name="id_dm"></select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold">
		Perusahaan Pengirim
	</label>
	<select class="form-control"  data-live-search="true" id="id_perush_dr" name="id_perush_dr">
		<option value="">-- Pilih Pengirim --</option>
		@foreach($perusahaan as $key => $value)
		<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold">
		Layanan
	</label>
	<select class="form-control" id="id_layanan" name="id_layanan">
		<option value="">-- Pilih Layanan --</option>
		@foreach($layanan as $key => $value)
		<option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
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
		Dari Tgl Tiba
	</label>
	<input type="date" name="tgl_awal" id="tgl_awal" class="form-control" value="@if(isset($filter["tgl_awal"])){{ $filter["tgl_awal"] }}@endif" placeholder="Cari Tanggal">
</div>

<div class="col-md-3 mt-1">
	<label style="font-weight: bold;">
		Sampai Tgl Tiba
	</label>
	<input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" value="@if(isset($filter["tgl_akhir"])){{ $filter["tgl_akhir"] }}@endif" placeholder="Cari Tanggal">
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

<div class="col-md-12 text-right">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<div class="dropdown d-inline-block">
		{{-- <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-print"> </i> Cetak Dm
		</button> --}}
	</div>
</div>
