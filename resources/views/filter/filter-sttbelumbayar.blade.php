<div class="col-md-4">
	<label class="mt-1">
		No. STT
	</label>
	<select class="form-control" id="f_id_stt" name="f_id_stt">
		<option value="">-- Pilih Stt --</option>
		@foreach($stt as $key => $value)
		<option value="{{ $value->id_stt }}">{{ strtoupper($value->kode_stt) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-4">
	<label class="mt-1">
		Pelanggan
	</label>
	<select class="form-control" id="f_id_pelanggan" name="f_id_pelanggan">
		<option value="">-- Pilih Pelanggan --</option>
		@foreach($pelanggan as $key => $value)
		<option value="{{ $value->id_pelanggan }}">{{ strtoupper($value->nm_pelanggan) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-4">
	<label class="mt-1">
		Layanan
	</label>
	<select class="form-control" id="f_id_layanan" name="f_id_layanan">
		<option value="">-- Pilih Layanan --</option>
		@foreach($layanan as $key => $value)
		<option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-4">
	<label class="mt-1">
		Tanggal Awal Masuk
	</label>
	<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-4">
	<label class="mt-1">
		Tanggal Akhir Masuk
	</label>
	<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<div class="col-md-4 mt-5">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<div class="dropdown d-inline-block">
		<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		   <i class="fa fa-print"> </i> Cetak Data
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="{{ url(Request::segment(1).'/cetak') }}" target="_blank" rel="nofollow"><i class="fa fa-print"> </i> Cetak All Data</a>
		</div>
	</div>
</div>
