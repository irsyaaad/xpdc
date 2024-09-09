<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Group Pelanggan
	</label>
	<select class="form-control" id="f_id_group" name="f_id_group">
		<option value="">-- Cari Group Pelanggan --</option>
		@foreach ($group as $key => $value)
		<option value="{{ $value->id_plgn_group }}">{{ "( ".$value->kode_plgn_group." )".$value->nm_group }}</option>
		@endforeach
	</select>
</div>

<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Pelanggan
	</label>
	<select class="form-control" id="f_id_pelanggan" name="f_id_pelanggan">
		<option value="">-- Cari Pelanggan --</option>
		@foreach ($pelanggan as $key => $value)
		<option value="{{ $value->id_pelanggan }}">{{ $value->nm_pelanggan }}</option>
		@endforeach
	</select>
</div>


<div class="col-md-4">
	<label style="font-weight: bold;" class="mt-1">
		Tipe Data
	</label>
	<select class="form-control" id="tipe_data" name="tipe_data">
		<option value="">SEMUA DATA</option>
		<option value="SUDAH LUNAS" {{ $filter['tipe_data'] == 'SUDAH LUNAS' ? 'selected' : '' }}>SUDAH LUNAS</option>
		<option value="BELUM LUNAS" {{ $filter['tipe_data'] == 'BELUM LUNAS' ? 'selected' : '' }}>BELUM LUNAS</option>
	</select>
</div>

<div class="col-md-4 mt-2">
	<label style="font-weight: bold;" class="mt-1">
		Tgl Awal
	</label>
	<input type="date" class="form-control" value="{{ $filter["f_start"] }}" id="f_start" name="f_start" />
</div>

<div class="col-md-4 mt-2">
	<label style="font-weight: bold;" class="mt-1">
		Tgl Akhir
	</label>
	<input type="date" class="form-control" value="{{ $filter["f_end"] }}" id="f_end" name="f_end" />
</div>

<div class="col-md-12 text-right mt-2">
	<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"> </i> Cari</button>
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
    <div class="dropdown d-inline-block">
		<button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		   <i class="fa fa-print"> </i> Cetak Piutang
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="{{ url(Request::segment(1).'/cetaksemuadata?'.$filter["urls"]) }}" target="_blank" rel="nofollow"><i class="fa fa-print"> </i>Cetak Semua Data</a>
            <a class="dropdown-item" href="{{ url(Request::segment(1).'/cetaklunas?'.$filter["urls"]) }}" target="_blank" rel="nofollow"><i class="fa fa-print"> </i>Cetak Piutang Lunas</a>
            <a class="dropdown-item" href="{{ url(Request::segment(1).'/cetakbelumlunas?'.$filter["urls"]) }}" target="_blank" rel="nofollow"><i class="fa fa-print"> </i>Cetak Piutang Belum Lunas</a>
		</div>
	</div>
</div>
