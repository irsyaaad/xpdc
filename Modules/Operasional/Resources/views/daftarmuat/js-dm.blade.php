<script type="text/javascript">
	$("#id_perush_tj").select2();
	$("#id_kapal").select2();

	@if(!is_null(old('id_kapal')))
	$('#id_kapal').val('{{ old("id_kapal") }}').trigger("change");
	@endif
	
	@if(!is_null(old('id_sopir')))
	$('#id_sopir').val('{{ old("id_sopir") }}');
	@endif
	
	@if(!is_null(old('id_armada')))
	$('#id_armada').val('{{ old("id_armada") }}');
	@endif
	
	@if(isset($data->tgl_berangkat))
	$("#tgl_berangkat").val('{{ date("Y-m-d", strtotime($data->tgl_berangkat)) }}');
	@else 
	$("#tgl_berangkat").val('{{ date("Y-m-d") }}'); 
	@endif
	
	$('#id_perush_tj').on("change", function(e) { 
		activeLayanan();
		setPenerima();
	});
	
	function activeLayanan() {
		var id_asal = '{{ Session("perusahaan")["id_perush"] }}';
		var id_tujuan = $("#id_perush_tj").val();
		
		if(id_asal == id_tujuan){
			$("#text-modal").text("Perusahaan tidak boleh sama");
			$('#tarif-modal').modal();
			$("#id_perush_tj").val("");
			$("#id_perush_tj").text("");
			$("#nm_tujuan").val("");
		}
		
		if(id_asal!=null && id_tujuan!=null){
			$("#nm_tujuan").val($("#id_perush_tj").text());
		}
	}
	
	function setPenerima() {
		var id_perush = $("#id_perush_tj").val();
		$.ajax({
			type: "GET", 
			url: "{{ url("getOpsTerima") }}/"+id_perush, 
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				$.each(response,function(key, value)
				{					
					$("#nm_pj_tuju").val(value.nm_user);
					$("#id_penerima").val(value.id_user);
				});
				
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	}
	
	$('#id_sopir').on("change", function(e) {
		$.ajax({
			type: "GET", 
			url: "{{ url("ChainArmada") }}/"+$("#id_sopir").val(), 
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){ 
				console.log(response.id_armada);
				$("#id_armada").val(response.id_armada);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	});
	
	@if(Request::segment(3)=="edit" or Request::segment(3)=="show")
	
	@if(isset($data->perush_tujuan->id_perush))
	$("#id_perush_tj").val('{{ $data->perush_tujuan->id_perush }}').trigger("change");
	@endif
	
	@if(isset($data->kapal->id_kapal))
	$("#id_kapal").val('{{ $data->kapal->id_kapal }}').trigger("change");
	@endif

	@if(isset($data->id_perush_tj))
	$("#id_perush_tj").val('{{ $data->id_perush_tj }}');
	@endif
	
	@if(isset($data->sopir->id_sopir))
	$("#id_sopir").val('{{ $data->sopir->id_sopir }}');
	@endif
	
	@if(isset($data->armada->id_armada))
	$("#id_armada").prop('disabled', false);
	$("#id_armada").empty();
	$("#id_armada").append('<option value="{{ $data->armada->id_armada }}">{{ strtoupper($data->armada->nm_armada) }}</option>');
	@endif
	
	$("#btn-time").on("click", function(e) { 
		$('#modal-time').modal();
	});
	
	@endif
</script>