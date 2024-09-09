<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    <div class="m-form m-form--label-align-right m--margin-bottom-20">
		<div class="row align-items-center">
			<div class="col-xl-12">
				<div class="form-group row">
					<div class="col-md-6 row" style="padding-top:5px">
						<div class="col-md-6">
							<label style="font-weight: bold;">
								Pilih Bulan
							</label>
							<select class="form-control" id="bulan" name="bulan">
								<option value="">-- Pilih Bulan --</option>
								<option value="01">  Januari  </option>
								<option value="02">  Februari  </option>
								<option value="03">  Maret  </option>
								<option value="04">  April  </option>
								<option value="05">  Mei  </option>
								<option value="06">  Juni  </option>
								<option value="07">  Juli  </option>
								<option value="08">  Agustus  </option>
								<option value="09">  September  </option>
								<option value="10">  Oktober  </option>
								<option value="11">  November  </option>
								<option value="12">  Desember  </option>
							</select>
						</div>
						<div class="col-md-6">
							<label style="font-weight: bold;">
								Pilih Tahun
							</label>
							<select name="tahun" class="form-control" id="tahun" name="tahun">
								<option selected="selected" value="">-- Pilih Tahun --</option>
								<?php for($i=date('Y'); $i>=date('Y')-10; $i-=1){ ?>
								<option value="{{ $i }}">{{ $i }}</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-3 row" style="padding-top:30px;">
						<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span></button>
						<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning mr-2" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
						<a href="{{ route('cetak-laporan-budgeting', [
									'bulan' => $filter['bulan'],
									'tahun' => $filter['tahun'],
									]) }}" style="color:white;" class="btn btn-md btn-accent" data-toggle="tooltip" data-placement="top" title="Cetak pdf" target="_blank" rel="nofollow"><i class="fa fa-print"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$("#bulan").select2();
	$("#tahun").select2();

	@if(isset($filter["bulan"]))
    $("#bulan").val("{{ $filter['bulan'] }}").trigger('change');
    @endif
    
    @if(isset($filter["tahun"]))
    $("#tahun").val("{{ $filter['tahun'] }}").trigger('change');
    @endif
</script>
