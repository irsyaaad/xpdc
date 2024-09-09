<div class="col-md-2">
	<div class="m-form__group m-form__group--inline">
		<div class="m-form__control">
			<label style="font-weight: bold;">
				Group Armada
			</label>
			<select class="form-control" id="f_group" name="f_group">
				<option value="">-- Pilih Group Armada --</option>
                <option value="1">Darat</option>
                <option value="2">Laut</option>
                <option value="3">Udara</option>
			</select>
		</div>
	</div>
</div>

<div class="col-md-3 text-left" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>

