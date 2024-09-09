<div class="col-md-4">
	<label style = "font-weight : bold">
        Perusahaan Asal
    </label>
    <select class="form-control" id="f_id_perush" name="f_id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($perush as $key => $value)
    <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
    @endforeach
    </select>
</div>

<div class="col-md-4">
	<label style="font-weight: bold;">
		Karyawan
	</label>
	<select class="form-control" id="f_id_karyawan" name="f_id_karyawan">
		<option value="">-- Pilih Karyawan --</option>
		@foreach($karyawan as $key => $value)
		<option value="{{ $value->id_karyawan }}">{{ strtoupper($value->nm_karyawan) }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-4 text-left" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>