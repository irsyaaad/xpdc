<script type="text/javascript">
	$(document).ready(function() {
		$("#text-tujuan").text($('#penerima_id_region').text());
		$("#text-asal").text($('#pengirim_id_region').text());
	});
	
	@if(isset($data->id_layanan))
	$("#id_layanan").prop('disabled', false);
	$("#id_layanan").val('{{ $data->id_layanan }}');
	@endif

	@if(isset($data->id_packing))
	$("#id_packing").val('{{ $data->id_packing }}');
	@endif
	
	@if(isset($data->is_packing) and $data->is_packing != 0)
	$("#is_packing").attr("checked", true);
	@endif
	
	@if(isset($data->n_koli))
	$("#n_koli").val('{{ $data->n_koli }}');
	@endif

	@if(isset($data->id_cr_byr_o))
	$("#id_cr_byr_o").val('{{ $data->id_cr_byr_o }}');
	@endif


	@if(isset($data->id_asuransi))
	$("#id_asuransi").val({{ $data->id_asuransi }});
	@endif
	@if(isset($data->n_asuransi))
	$("#n_asuransi").attr('readonly', false);
	$("#n_asuransi").val({{ $data->n_asuransi }});
	@endif

	@if(isset($data->n_tarif_borongan))
	$("#n_tarif_borongan").attr("readonly", false);
	@endif

	@if(isset($data->perush_tujuan->nm_perush))
	$("#id_perush_tujuan").append('<option value=' + {{ $data->perush_tujuan->id_perush }} + '>'+"{{ strtoupper($data->perush_tujuan->nm_perush) }}"+'</option>');
	@endif

	@if(isset($data->is_ppn) and $data->is_ppn==1)
	$("#is_ppn").prop("checked", true);
	@endif

	@if(isset($data->is_asuransi) and $data->is_asuransi==1)
	$("#is_asuransi").prop("checked", true);
	@endif

	@if(isset($data->asal->id_wil))
	$("#pengirim_id_region").empty();
	$("#pengirim_id_region").append('<option value=' + {{ $data->asal->id_wil }} + '>'+"{{ strtoupper($data->asal->nama_wil) }}"+'</option>');
	@endif

	@if(isset($data->pelanggan->id_pelanggan))
	$("#id_pelanggan").empty();
	$("#id_pelanggan").append('<option value=' + {{ $data->pelanggan->id_pelanggan }} + '>'+"{{ strtoupper($data->pelanggan->nm_pelanggan) }}"+'</option>');
	@endif

	@if(isset($data->id_marketing))
	$("#id_marketing").val('{{ $data->id_marketing }}');
	@endif

	@if(isset($data->tujuan->id_wil))
	$("#penerima_id_region").empty();
	$("#penerima_id_region").append('<option value=' + {{ $data->tujuan->id_wil }} + '>'+"{{ strtoupper($data->tujuan->nama_wil) }}"+'</option>');
	@endif

	@if(isset($data->perush_asal->id_wil))
	$("#id_packing").empty();
	$("#id_packing").append('<option value=' + {{ $data->perush_asal->id_wil }} + '>'+"{{ strtoupper($data->perush_asal->id_wil) }}"+'</option>');
	@endif
	
	@if(isset($data->tipekirim->id_tipe_kirim))
	$("#id_tipe_kirim").val('{{ $data->tipekirim->id_tipe_kirim }}').trigger("change");
	@endif
	
	@if(isset($data->tarif->id_tarif))
		$('#id_tarif').empty();
		$('#id_tarif').append('<option value="">-- Pilih Tarif --</option>');
		@if($data->tarif->is_standart==1)
		$('#id_tarif').append('<option value="{{ $data->tarif->id_tarif }}">Standart</option>');
		@else
		$('#id_tarif').append('<option value="{{ $data->tarif->id_tarif }}">{{ strtoupper($data->tarif->info) }}</option>');
		@endif

		$('#id_tarif').val({{ $data->tarif->id_tarif }});
	@endif
	
	@if(isset($data->tarif->min_brt))
		$("#cm_brt").val({{ $data->tarif->min_brt }});
	@endif

	@if(isset($data->tarif->min_vol))
		$("#cm_vol").val({{ $data->tarif->min_vol }});
	@endif

	@if(isset($data->c_tarif))
	$('#c_hitung[value="{{ $data->c_tarif }}"]').prop('checked', true);
	@endif
</script>
