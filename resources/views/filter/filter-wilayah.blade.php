<div class="col-md-3">
	<label class="mt-1">
		Wilayah
	</label>
	<select class="form-control" id="f_id_wil" name="f_id_wil"></select>
</div>

<div class="col-md-3 mt-5">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>
