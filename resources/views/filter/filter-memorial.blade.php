@if(get_admin())
<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Perusahaan
	</label>
	<select class="form-control" id="filterperush" name="filterperush">
		<option value="">-- Pilih Perusahaan --</option>
	</select>
</div>
@endif

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Akun Debet
	</label>
	<select class="form-control" id="id_memo" name="id_memo"></select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Akun Debet
	</label>
	<select class="form-control" id="debet" name="debet"></select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Akun Kredit
	</label>
	<select class="form-control" id="kredit" name="kredit"></select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Dari Tanggal Bayar
	</label>
	<input class="form-control" type="date" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Sampai Tanggal Bayar
	</label>
	<input class="form-control" type="date" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<div class="col-md-3" style="margin-top: 30px">	
	<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"> </i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
</div>