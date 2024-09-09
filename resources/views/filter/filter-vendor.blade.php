<div class="col-md-3">
	<div class="m-form__control">
		<label style = "font-weight : bold">
			Nama Vendor
		</label>
		<select class="form-control" id="f_id_ven" name="f_id_ven"></select>
	</div>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;">
		Group Vendor
	</label>
	<select class="form-control" id="f_id_grup_ven" name="f_id_grup_ven">
		<option value="">-- Pilih Group --</option>
		@foreach($group as $key => $value)
		<option value="{{ $value->id_grup_ven }}">{{ strtoupper($value->nm_grup_ven) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3 text-left" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>