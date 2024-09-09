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
							<label class="mt-1">
								No. STT
							</label>
							<select class="form-control" id="id_stt" name="id_stt"></select>
						</div>
						<div class="col-md-4">
							<label class="mt-1">
								No. DM
							</label>
							<select class="form-control" id="id_dm" name="id_dm"></select>
						</div>
						<div class="col-md-4">
							<label class="mt-1">
								Nama Pelanggan
							</label>
							<select class="form-control" id="id_pelanggan" name="id_pelanggan"></select>
						</div>
						<div class="col-md-4 mt-2">
							<label style="font-weight: bold;">
								Mode
							</label>
							<select name="mode" class="form-control" id="mode">
                                <option value="SEMUA-STT" {{ $filter['mode'] == "SEMUA-STT" ? 'selected' : '' }}>SEMUA STT</option>
                                <option value="SUDAH-SAMPAI" {{ $filter['mode'] == "SUDAH-SAMPAI" ? 'selected' : '' }}>SUDAH SAMPAI</option>
								<option value="BELUM-SAMPAI" {{ $filter['mode'] == "BELUM-SAMPAI" ? 'selected' : '' }}>BELUM SAMPAI</option>
                            </select>
						</div>
						<div class="col-md-12 row d-inline-block" style="padding-top:30px;">
							<div class="text-right">
								<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data"><span><i class="fa fa-search"></i></span></button>
								<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
								<a href="{{ route('cetak-managerial-stt', [
									'dr_tgl' => $filter['dr_tgl'], 
									'sp_tgl' => $filter['sp_tgl'],
									'mode' => $filter['mode'],
									'id_stt' => isset($filter['id_stt']) ? $filter['id_stt'] : null,
									'id_dm' => isset($filter['id_dm']) ? $filter['id_dm'] : null,
									'id_pelanggan' => isset($filter['id_pelanggan']) ? $filter['id_pelanggan'] : null,
									]) }}" class="btn btn-md btn-success" data-toggle="tooltip" data-placement="bottom" title="Cetak Data" target="_blank"><span><i class="fa fa-print"></i></span></a>
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

	$('#id_stt').select2({
        placeholder: 'Cari STT ....',
        ajax: {
            url: '{{ url('getSttPerush') }}',
            minimumInputLength: 1,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#id_stt').empty();
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            cache: true
        }
    });

	$('#id_dm').select2({
		placeholder: 'Cari Nomor DM ....',
		ajax: {
			url: '{{ url('dmtrucking/get-all-dm') }}',
			minimumInputLength: 1,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_dm').empty();
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.value,
							id: item.kode
						}
					})
				};
			},
			cache: true
		}
	});

	$('#id_pelanggan').select2({
        placeholder: 'Cari Pelanggan ....',
        ajax: {
            url: '{{ url('getPelanggan') }}',
            minimumInputLength: 1,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#id_pelanggan').empty();
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.value,
                            id: item.kode
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
