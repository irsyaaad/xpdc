<div class="col-md-3">
	<label class="mt-2">
		No. DM
	</label>
	<select class="form-control" id="filterdm" name="filterdm"></select>
</div>

<div class="col-md-3">
	<label class="mt-2">
		No. Awb
	</label>
	<select class="form-control" id="filterawb" name="filterawb"></select>
</div>

<div class="col-md-3">
	<label class="mt-2">
		Kota Asal
	</label>
	<select class="form-control" id="filterasal" name="filterasal"></select>
</div>

<div class="col-md-3">
	<label class="mt-2">
		Kota Tujuan
	</label>
	<select class="form-control" id="filtertujuan" name="filtertujuan"></select>
</div>

<div class="col-md-3">
	<label class="mt-2">
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
	<label class="mt-2">
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
	<label class="mt-2">
		Tanggal Awal Masuk
	</label>
	<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-3">
	<label class="mt-2">
		Tanggal Akhir Masuk
	</label>
	<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<div class="col-md-12 text-right mb-2" style="margin-top: 1%">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
    <button type="button" class="btn btn-sm btn-primary" onclick="goUpdate()"><i class="fa fa-bell"> </i> Update Status</button>
</div>