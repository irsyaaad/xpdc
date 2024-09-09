<div class="col-md-4">
	<label class="mt-1">
		Kode DM
	</label>
	<select class="form-control" id="f_id_dm" name="f_id_dm">
		<option value="">-- Pilih Nomor DM --</option>
		@foreach ($no_dm as $key => $value)
		<option value="{{ $value->id_dm }}">{{ $value->kode_dm }}</option>	
		@endforeach
	</select>
</div>

<div class="col-md-4">
	<label class="mt-1">
		Kode STT
	</label>
	<select class="form-control" id="f_id_stt" name="f_id_stt">
		<option value="">-- Pilih Nomor STT --</option>
		@foreach ($no_stt as $key => $value)
		<option value="{{ $value->id_stt }}">{{ $value->kode_stt }}</option>	
		@endforeach
	</select>
</div>

<div class="col-md-4">
	<label  class="mt-1">
		Vendor Tujuan
	</label>
	<select class="form-control" id="f_id_ven" name="f_id_ven">
		<option value="">-- Pilih Vendor --</option>
		@foreach ($vendor as $key => $value)
		<option value="{{ $value->id_ven }}">{{ $value->nm_ven }}</option>	
		@endforeach
	</select>
</div>

<div class="col-md-4">
	<label class="mt-1">
		Berangkat (Dari)
	</label>
	<input class="form-control" type="date" name="dr_tgl" id="dr_tgl" >
</div>

<div class="col-md-4">
	<label  class="mt-1">
		Berangkat (Sampai)
	</label>
	<input class="form-control" type="date" name="sp_tgl" id="sp_tgl">
</div>

<div class="col-md-4">
	<label  class="mt-1">
		No. Container
	</label>
	<select class="form-control" id="f_no" name="f_no">
		<option value="">-- Pilih No. Container --</option>
		@foreach ($no_container as $key => $value)
		<option value="{{ $value->no_container }}">{{ $value->no_container }}</option>	
		@endforeach
	</select>
</div>

<div class="col-md-12 text-right mt-1">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<div class="dropdown d-inline-block">
		<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-print"> </i> Cetak
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="{{ url("sttnodm/cetak") }}" target="_blank" rel="nofollow">Stt Inventory</a>
		</div>
	</div>
</div>