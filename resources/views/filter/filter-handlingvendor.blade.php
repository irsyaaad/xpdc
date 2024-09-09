<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		No Handling
	</label>
	<select class="form-control" id="id_handling" name="id_handling"></select>
</div>

<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		Vendor Tujuan
	</label>

	<select class="form-control" id="id_ven" name="id_ven">
		<option value="">-- Pilih Vendor --</option>
		@foreach($vendor as $key => $value)
		<option value="{{ $value->id_ven }}">{{ strtoupper($value->nm_ven) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		Wilayah Tujuan
	</label>
	<select class="form-control" id="id_wil" name="id_wil"></select>
</div>

<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		Sopir
	</label>
	<select class="form-control" id="id_sopir" name="id_sopir">
		<option value="">-- Pilih Sopir --</option>
		@foreach($sopir as $key => $value)
		<option value="{{ $value->id_sopir }}">{{ strtoupper($value->nm_sopir) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		Armada
	</label>
	<select class="form-control" id="id_armada" name="id_armada">
		<option value="">-- Pilih Armada --</option>
		@foreach($armada as $key => $value)
		<option value="{{ $value->id_armada }}">{{ strtoupper($value->nm_armada) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		Dari Tgl Berangkat
	</label>
	<input type="date" class="form-control" id="dr_tgl" name="dr_tgl" value="@if(isset($filter["dr_tgl"])){{ $filter["dr_tgl"] }}@endif" />
</div>

<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		Sampai Tgl Berangkat
	</label>
	<input type="date" class="form-control" id="sp_tgl" name="sp_tgl" value="@if(isset($filter["sp_tgl"])){{ $filter["sp_tgl"] }}@endif" />
</div>

<div class="col-md-3 mt-2">
	<label style="font-weight: bold;">
		Status Handling
	</label>

	<select class="form-control" id="id_status" name="id_status">
		<option value="">-- Pilih Status --</option>
		@foreach($status_handling as $key => $value)
		<option value="{{ $value->id_status }}">{{ strtoupper($value->nm_status) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-12 mt-2 text-right">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>