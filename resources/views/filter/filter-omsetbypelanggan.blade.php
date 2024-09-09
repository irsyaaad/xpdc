<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    <div class="m-form m-form--label-align-right m--margin-bottom-20">
		<div class="row align-items-center">
			<div class="col-xl-12">
				<div class="form-group row">
					<div class="col-md-9 row" style="padding-top:5px">
						<div class="col-md-6">
							<label style="font-weight: bold;">
								Dari Tanggal
							</label>
							<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="{{ isset($filter['dr_tgl']) ? $filter['dr_tgl'] : '' }}">
						</div>
						<div class="col-md-6">
							<label style="font-weight: bold;">
								Sampai Tanggal
							</label>
							<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="{{ isset($filter['sp_tgl']) ? $filter['sp_tgl'] : '' }}">
						</div>
					</div>
					<div class="col-md-3 row" style="padding-top:30px;">
						<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span></button>
						<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data" style="margin-right : 5px"><span><i class="fa fa-refresh"></i></span></a>
						<a href="{{ route('cetak-bypelanggan', [
                            'dr_tgl' => $filter['dr_tgl'], 
                            'sp_tgl' => $filter['sp_tgl'],
                            ]) }}" class="btn btn-md btn-success" data-toggle="tooltip" data-placement="bottom" title="Cetak" target="_blank"><span><i class="fa fa-print"></i></span></a>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</form>

