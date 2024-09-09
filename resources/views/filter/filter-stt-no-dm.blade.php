@if(get_admin())
<div class="col-md-3">
	<label>
		Perusahaan Asal
	</label>
	<select class="form-control" id="filterperush" name="filterperush"></select>
</div>
@endif

<div class="col-md-3">
	<label class="mt-1">
		No. STT
	</label>
	<select class="form-control" id="filterstt" name="filterstt"></select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		Kota Asal
	</label>
	<select class="form-control" id="filterasal" name="filterasal"></select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		Kota Tujuan
	</label>
	<select class="form-control" id="filtertujuan" name="filtertujuan"></select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		Status Stt
	</label>
	<select class="form-control" id="filterstatusstt" name="filterstatusstt">
		<option value="">-- Pilih Status Stt --</option>
		@foreach($status as $key => $value)
		<option value="{{ $value->id_ord_stt_stat }}">{{ strtoupper($value->nm_ord_stt_stat) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		Layanan
	</label>
	<select class="form-control" id="filterlayanan" name="filterlayanan">
		<option value="">-- Pilih Layanan --</option>
		@foreach($layanan as $key => $value)
		<option value="{{ $value->id_layanan }}">{{ strtoupper($value->nm_layanan)  }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		Tanggal Awal Masuk
	</label>
	<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-3">
	<label class="mt-1">
		Tanggal Akhir Masuk
	</label>
	<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<div class="col-md-3">
	<label class="mt-1">
		Cara Bayar
	</label>
	<select class="form-control" id="filtercarabayar" name="filtercarabayar">
		<option value="">-- Pilih Cara Bayar --</option>
		@foreach($cara as $key => $value)
		<option value="{{ $value->id_cr_byr_o }}">{{ strtoupper($value->nm_cr_byr_o) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		No. Awb
	</label>
	<select class="form-control" id="f_awb" name="f_awb">
		<option value="">-- Cari No. Awb--</option>
	</select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		Pelanggan
	</label>
	<select class="form-control" id="f_pelanggan" name="f_pelanggan">
		<option value="">-- Cari Pelanggan --</option>
	</select>
</div>

<div class="col-md-6" style="margin-top:30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<div class="dropdown d-inline-block">
		<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		   <i class="fa fa-print"> </i> Cetak Stt
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="{{ url(Request::segment(1)). '/cetak' . \Request::getRequestUri() }}" target="_blank" rel="nofollow">Cetak Pdf</a>
		</div>
	</div>
</div>