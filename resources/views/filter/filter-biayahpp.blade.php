<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Kode DM
	</label>
	<select class="form-control" id="f_id_dm" name="f_id_dm">
		<option value="">-- Pilih Nomor DM --</option>
		@foreach ($no_dm as $key => $value)
		<option value="{{ $value->id_dm }}">{{ $value->kode_dm }}</option>	
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Perusahaan Tujuan
	</label>
	<select class="form-control" id="f_perushtj" name="f_perushtj">
		<option value="">-- Pilih Tujuan --</option>
		@foreach ($perush as $key => $value)
		<option value="{{ $value->id_perush }}">{{ $value->nm_perush." ( ".$value->kode_perush." )" }}</option>	
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Berangkat (Dari)
	</label>
	<input class="form-control" type="date" name="dr_tgl" id="dr_tgl" >
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Berangkat (Sampai)
	</label>
	<input class="form-control" type="date" name="sp_tgl" id="sp_tgl">
</div>

<div class="col-md-12 text-right mt-1">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>
