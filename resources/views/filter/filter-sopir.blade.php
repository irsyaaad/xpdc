<div class="col-md-3">
	<div class="m-form__control">
		<label style="font-weight: bold;">
			Nama Sopir
		</label>
		<select class="form-control" id="f_id_sopir" name="f_id_sopir"></select>
	</div>
</div>

<div class="col-md-3">
	<div class="m-form__control">
		<label style="font-weight: bold;">
				Default Armada
			</label>
			<select class="form-control" id="f_def_armada" name="f_def_armada"></select>
		</div>
</div>

<div class="col-md-3 text-left" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>

