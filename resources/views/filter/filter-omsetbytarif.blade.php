<form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
    <div class="m-form m-form--label-align-right m--margin-bottom-20">
		<div class="row align-items-center">
			<div class="col-xl-12">
				<div class="form-group row">
					<div class="col-md-12 row" style="padding-top:5px">
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
								Jenis Tarif
							</label>
							<select name="id_tarif" class="form-control" id="id_tarif">
                                <option value="0">SEMUA DATA</option>
                                <option value="1">BERAT</option>
                                <option value="2">VOLUME</option>
                                <option value="3">KUBIK</option>
                                <option value="4">BORONGAN</option>
                            </select>
						</div>
						<div class="col-md-4 mt-2">
							<label style="font-weight: bold;">
								Mode
							</label>
							<select name="mode" class="form-control" id="mode">
                                <option value="DETAIL" {{ $filter['mode'] == "DETAIL" ? 'selected' : '' }}>DETAIL STT</option>
                                <option value="REKAPITULASI" {{ $filter['mode'] == "REKAPITULASI" ? 'selected' : '' }}>REKAPITULASI</option>
                            </select>
						</div>
						<div class="col-md-8 row d-inline-block" style="padding-top:30px;">
							<div class="text-right">
								<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" style="margin-right : 5px"><span><i class="fa fa-search"></i></span></button>
								<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
</form>
<script>
    $("#id_tarif").select2();
    @if(isset($filter["id_tarif"]) and $filter["id_tarif"] != 0)
        $("#id_tarif").val('{{ $filter["id_tarif"] }}').trigger("change");
    @endif
</script>
