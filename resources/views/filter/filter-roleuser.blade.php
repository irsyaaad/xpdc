<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<label style="font-weight: bold;">
			User
		</label>
		<div class="m-form__control">
			<select class="form-control" id="f_id_user" name="f_id_user">
				<option value="">-- Pilih User --</option>
				@foreach($user as $key => $value)
				@if(isset($value->karyawan->nm_karyawan))
				<option value="{{ $value->id_user }}">
					{{ $value->karyawan->nm_karyawan." ( ".$value->username." )" }}
				</option>
				@endif
				@endforeach
			</select>
		</div>
	</div>
</div>

<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<label style="font-weight: bold;">
			Role
		</label>
		<div class="m-form__control">
			<select class="form-control" id="f_id_role" name="f_id_role">
				<option value="">-- Pilih Role --</option>
				@foreach($role as $key => $value)
				<option value="{{ $value->id_role }}">{{ strtoupper($value->nm_role) }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>


<div class="col-md-3">
	<div class="m-form__group m-form__group--inline">
		<label style="font-weight: bold;">
			Perusahaan
		</label>
		<div class="m-form__control">
			<select class="form-control" id="f_id_perush" name="f_id_perush">
				<option value="">-- Pilih Perusahaan --</option>
				@foreach($perusahaan as $key => $value)
				<option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>

<div class="col-md-3" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>
