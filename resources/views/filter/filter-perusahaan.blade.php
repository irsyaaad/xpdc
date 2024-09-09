<div class="col-md-3">
	<label class="mt-1">
		Provinsi
	</label>
	<select class="form-control" id="f_id_wil" name="f_id_wil">
        <option value="">-- Pilih Kota Asal --</option>
        @foreach($wilayah as $key => $value)
        <option value="{{ $value->id_region }}">{{ strtoupper($value->nama_wil) }}</option>
        @endforeach
    </select>
</div>


<div class="col-md-3">
    <label class="mt-1">
		Cari Perusahaan
	</label>
	<select class="form-control" id="f_id_perush" name="f_id_perush">
        <option value="">-- Pilih Perusahaan --</option>
        @foreach($perusahaan as $key => $value)
        <option value="{{ $value->id_perush }}">{{ strtoupper($value->nm_perush) }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3" style="margin-top: 30px">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>
