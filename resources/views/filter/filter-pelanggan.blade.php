<div class="col-md-3">
	<div class="m-form__control">
		<label style="font-weight: bold;">
			Nama Pelanggan
		</label>
		<select class="form-control" id="f_id_pelanggan" name="f_id_pelanggan"></select>
	</div>
</div>

<div class="col-md-3">
	<div class="m-form__control">
		<label style="font-weight: bold;">
			Grup Pelanggan
		</label>
		<select class="form-control" id="f_id_plgn_grup" name="f_id_plgn_grup">
			<option value="">-- Pilih Grup Pelanggan --</option>
			@foreach($group as $key => $value)
			<option value="{{ $value->id_plgn_group }}">{{ strtoupper($value->nm_group." (".$value->kode_plgn_group." )") }}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="col-md-3">
	<div class="m-form__control">
		<label style="font-weight: bold;">
			Kota Pelanggan
		</label>
		<select class="form-control" id="f_id_wil" name="f_id_wil"></select>
	</div>
</div>

<div class="col-md-3 text-left" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<a href="{{ url(Request::segment(1)."/import") }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Import</a>
</div>
