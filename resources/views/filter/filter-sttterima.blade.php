<div class="col-md-3">
	<label class="mt-1">
		No. STT
	</label>
	<select class="form-control" id="filterstt" name="filterstt"></select>
</div>

<div class="col-md-3">
	<label>
		Perusahaan Asal
	</label>
	<select class="form-control" id="filterperush" name="filterperush"></select>
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
		Tanggal Berangkat
	</label>
	<input type="date" class="form-control" name="tgl_berangkat" id="tgl_berangkat" value="@if(isset($filter["tgl_berangkat"])){{$filter["tgl_berangkat"]}}@endif">
</div>

<div class="col-md-3">
	<label class="mt-1">
		Tanggal Tiba
	</label>
	<input type="date" class="form-control" name="tgl_tiba" id="tgl_tiba" value="@if(isset($filter["tgl_tiba"])){{$filter["tgl_tiba"]}}@endif">
</div>

<div class="col-md-3">
	<label class="mt-1">
		Status Stt
	</label>
	<select class="form-control" id="filterstatusstt" name="filterstatusstt">
		<option value="">-- Pilih Status Stt --</option>
		@foreach($status as $key => $value)
		<option value="{{ $value->id_ord_stt_stat }}">{{ strtoupper($value->nm_ord_stt_stat)  }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-12 text-right mt-1">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>