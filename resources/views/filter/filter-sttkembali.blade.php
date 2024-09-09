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
							Kode STT
						</label>
						<select class="form-control" id="id_stt" name="id_stt"></select>
					</div>
					<div class="col-md-4 mt-2">
						<label style="font-weight: bold;">
							Agenda STT
						</label>
						<select class="form-control" id="agenda" name="agenda"></select>
					</div>				
					
					<div class="col-md-12 row d-inline-block mt-2">
						<div class="text-right">
							<button class="btn btn-md btn-primary" data-toggle="tooltip" data-placement="bottom" title="Cari Data" ><span><i class="fa fa-search"></i></span></button>
							<a href="{{ url(Request::segment(1)) }}" class="btn btn-md btn-warning" data-toggle="tooltip" data-placement="bottom" title="Refresh Data"><span><i class="fa fa-refresh"></i></span></a>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	$('#id_stt').select2({
        placeholder: 'Cari STT ....',
        ajax: {
            url: '{{ url('getSttPerush') }}',
            minimumInputLength: 3,
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

	$('#agenda').select2({
        placeholder: 'Cari Agenda ....',
        ajax: {
            url: '{{ route("get-agenda-stt-kembali") }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#agenda').empty();
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

	@if (isset($filter['stt']->kode_stt))
    $("#id_stt").empty();
    $("#id_stt").append('<option value="{{ $filter['stt']->id_stt }}">{{ strtoupper($filter['stt']->kode_stt) }}</option>');
    @endif
</script>

