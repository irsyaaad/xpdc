<div class="col-md-3">
	<label class="mt-1">
		Role
	</label>
	<select class="form-control" id="f_id_role" name="f_id_role">
		<option value="">-- Pilih Role --</option>
		@foreach($role as $key => $value)
		<option value="{{ $value->id_role }}">{{ strtoupper($value->nm_role) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label class="mt-1">
		Module
	</label>
	<select class="form-control" id="f_id_module" name="f_id_module">
		<option value="">-- Pilih Module --</option>
		@foreach($module as $key => $value)
		<option value="{{ $value->id_module }}">{{ strtoupper($value->nm_module) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>

