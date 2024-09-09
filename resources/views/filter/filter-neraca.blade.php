<div class="row">
	<div class="col-md-4">
		<label style="font-weight: bold;">
			Dari Tanggal
		</label>
		<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="">
	</div>
	
	<div class="col-md-4">
		<label style="font-weight: bold;">
			Sampai Tanggal
		</label>
		<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="">
	</div>
	
	<div class="col-md-4" style="margin-top: 25px">
		<button type="submit" class="btn btn-md btn-primary" class="btn btn-primary" title="Cari Data">
			<i class="fa fa-search"></i> Cari
		</button>
		<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh">
			<i class="fa fa-refresh"></i> Reset
		</a>
		<div class="dropdown d-inline-block">
			<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Cetak
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a href="@if(isset($filter["cetak"])){{ $filter["cetak"] }}@endif" class="dropdown-item" target="_blank">
					<i class="fa fa-file-pdf-o"></i>Pdf
				</a>
				<a href="@if(isset($filter["excel"])){{ $filter["excel"] }}@endif" class="dropdown-item" >
					<i class="fa fa-print"></i>Excel
				</a>
			</div>
		</div>
	</div>
</div>
