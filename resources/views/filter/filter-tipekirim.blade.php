<div class="col-md-3">
	<label style="font-weight: bold;">
		Tipe Kirim 
	</label>
	<select class="form-control" id="f_id_tipe_kirim" name="f_id_tipe_kirim"></select>
</div>

<div class="col-md-6 mt-5 text-left">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<div class="dropdown d-inline-block">
	</div>
</div>