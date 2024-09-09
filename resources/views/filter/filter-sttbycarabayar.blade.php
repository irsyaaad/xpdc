<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    <div class="m-form m-form--label-align-right m--margin-bottom-20">
		<div class="row align-items-center">
			<div class="col-xl-12">
				<div class="form-group row">
					<div class="col-md-4">
						<label style="font-weight: bold;">
							Dari Tanggal
						</label>
						<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="{{ isset($filter['dr_tgl']) ? $filter['dr_tgl'] : '' }}">
					</div>
					<div class="col-md-4">
						<label style="font-weight: bold;">
							Sampai Tanggal
						</label>
						<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="{{ isset($filter['sp_tgl']) ? $filter['sp_tgl'] : '' }}">
					</div>
					<div class="col-md-4">
						<label style="font-weight: bold;">
							Cara Bayar
						</label>
						<select name="cara_bayar" class="form-control" id="cara_bayar">
							<option value="0">SEMUA DATA</option>
							@foreach ($carabayar as $item)
								<option value="{{ $item->id_cr_byr_o }}" {{ $item->id_cr_byr_o == $filter['cara_bayar'] ? 'selected' : '' }} > {{ strtoupper($item->nm_cr_byr_o) }} </option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4">
						<label style="font-weight: bold;">
							Status Piutang
						</label>
						<select name="status_lunas" class="form-control" id="status_lunas">
							<option value="0" {{ $filter['status_lunas'] == 0 ? 'selected' : '' }}>SEMUA DATA</option>
							<option value="1" {{ $filter['status_lunas'] == 1 ? 'selected' : '' }}>SUDAH LUNAS</option>
							<option value="2" {{ $filter['status_lunas'] == 2 ? 'selected' : '' }}>BELUM LUNAS</option>
						</select>
					</div>
					<div class="col-md-3 row" style="padding-top:30px;">
						<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span></button>
						<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</form>

