<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		No Transaksi
	</label>
	<select class="form-control" id="f_id_pendapatan" name="f_id_pendapatan">
		<option value="">-- Cari Nomor Transaksi --</option>
		@foreach($pendapatan as $key => $value)
			<option value="{{ $value->id_pendapatan }}">{{ $value->kode_pendapatan }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-3">
	<label style="font-weight: bold;" class="mt-1">
		Akun
	</label>
	<select class="form-control" id="f_id_ac" name="f_id_ac">
		<option value="">-- Cari Akun --</option>
		@foreach($akun as $key => $value)
			<option value="{{ $value->id_ac }}">{{ $value->nama }}</option>
		@endforeach
	</select>
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

<div class="col-md-12 text-right mt-1">
	<button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Cari Data"  onclick="goFilter()"><i class="fa fa-search"></i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
	<div class="dropdown d-inline-block">
		<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		   <i class="fa fa-print"> </i> Cetak
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="{{ route('cetakallpendapatan', [
                'dr_tgl' => $filter['dr_tgl'],
                'sp_tgl' => $filter['sp_tgl'],
                ]) }}" target="_blank" rel="nofollow">Cetak Pendapatan</a>
		</div>
	</div>
</div>
