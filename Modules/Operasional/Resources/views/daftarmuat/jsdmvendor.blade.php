<script type="text/javascript">
	$("input[name=jenis1][value='1']").prop('checked', true);
	$('#tgl_berangkat').val('{{ date("Y-m-d") }}');
	$('#id_ven').select2();
	$('#id_wil').select2();
	$('#id_wil_asal').select2();
	$("#lbl_container").hide();
	$("#lbl_seal").hide();
	
	$('#id_layanan').on("change", function(e) {
		if($('#id_layanan').val()== "2" || $('#id_layanan').val() =="3"){
			$("#lbl_container").show();
			$("#lbl_seal").show();
		}else{
			$("#lbl_container").hide();
			$("#lbl_seal").hide();
		}
		$("#no_container").val('');
		$("#no_seal").val('');
	});

	@if(old("id_wil") != null)
	$("#id_wil").val('{{ old("id_wil") }}').trigger("change");
	@elseif(isset($data->id_wil_tujuan))
	$("#id_wil").val('{{ $data->id_wil_tujuan }}').trigger("change");
	@endif
	
	@if(old("id_wil_asal") != null)
	$("#id_wil_asal").val('{{ old("id_wil_asal") }}').trigger("change");
	@elseif(isset($data->id_wil_asal))
	$("#id_wil_asal").val('{{ $data->id_wil_asal }}').trigger("change");
	@endif

	@if(old("id_layanan") != null)
	$("#id_layanan").val('{{ old("id_layanan") }}').trigger("change");
	@elseif(isset($data->id_layanan))
	$("#id_layanan").val('{{ $data->id_layanan }}').trigger("change");
	@endif

	@if(old("id_ven") != null)
	$("#id_ven").val('{{ old("id_ven") }}').trigger("change");
	@elseif(isset($data->id_ven))
	$("#id_ven").val('{{ $data->id_ven }}').trigger("change");
	@endif
	
	@if(old("cara")=="1")
	$("input[name=cara][value='1']").prop('checked', true);
	@elseif(old("cara")=="2")
	$("input[name=cara][value='2']").prop('checked', true);
	@elseif(old("cara")=="3")
	$("input[name=cara][value='3']").prop('checked', true);
	@elseif(old("cara")=="4")
	$("input[name=cara][value='4']").prop('checked', true);
	@elseif(isset($data->cara))
	$("input[name=cara][value='{{ $data->cara }}']").prop('checked', true);
	@endif
	
	@if(old("id_layanan") != null  and old("id_layanan") == "2")
	$("#lbl_container").show();
	$("#lbl_seal").show();
	$("#no_container").val('{{ old("no_container") }}');
	$("#no_seal").val('{{ old("no_seal") }}');
	@elseifold("id_layanan") != null  and  (old("id_layanan") != "2")
	$("#lbl_container").hide();
	$("#lbl_seal").hide();
	$("#no_container").val('');
	$("#no_seal").val('');
	@elseif(isset($data->id_layanan) and $data->id_layanan == "2")
	$("#lbl_container").show();
	$("#lbl_seal").show();
	$("#no_container").val('{{ $data->no_container }}');
	$("#no_seal").val('{{ $data->no_seal }}');
	@elseif(isset($data->id_layanan) and $data->id_layanan != "2")
	$("#lbl_container").hide();
	$("#lbl_seal").hide();
	$("#no_container").val('');
	$("#no_seal").val('');
	@endif

</script>