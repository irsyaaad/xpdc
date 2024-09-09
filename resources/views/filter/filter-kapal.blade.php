<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<div class="m-form__control">
			<label style="font-weight: bold;">
				Nama Kapal
			</label>
			<select class="form-control" id="f_id_kapal" name="f_id_kapal"></select>
		</div>
	</div>
</div>

<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<div class="m-form__control">
			<label style="font-weight: bold;">
				Perusahaan Kapal
			</label>
			<select class="form-control" id="f_id_perush" name="f_id_perush">
				<option value="">-- Pilih Perusahaan --</option>
				@foreach($perush as $key => $value)
				<option value="{{ $value->id_kapal_perush }}">{{ strtoupper($value->nm_kapal_perush) }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>

<div class="col-md-6 text-left" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<div class="dropdown d-inline-block">
	</div>
</div>