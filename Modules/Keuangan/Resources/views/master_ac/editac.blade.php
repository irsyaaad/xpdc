@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('masterac', $data->id_ac) }}" enctype="multipart/form-data">
	{{ method_field("PUT") }} 
	@csrf
	<div class="form-group m-form__group">
		<label for="nama">
			<b>Nama</b> <span class="span-required"> * </span>
		</label>
		
		<input type="text" class="form-control m-input m-input--square" name="nama" id="nama" required="required" value="@if(isset($data->nama)){{$data->nama}}@endif">
		
		@if ($errors->has('nama'))
		<label style="color: red">
			{{ $errors->first('nama') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="tipe">
			<b>Tipe</b></span>
		</label>
		
		<select class="form-control m-input m-input--square" name="tipe" id="tipe">
			@if(isset($tipe) and $tipe == "D")
			<option value="D" >Debit</option>
			@else
			<option value="K" >Kredit</option>
			@endif
			<option value="">-- Pilih Tipe --</option>
			<option value="D" >Debit</option>
			<option value="K" >Kredit</option>
		</select>
		
		@if ($errors->has('nm_status'))
		<label style="color: red">
			{{ $errors->first('nm_status') }}
		</label>
		@endif
	</div>
	
	<div class="form-group m-form__group">
		<label for="level">
			<b>Level</b>
		</label>
		<select class="form-control m-input m-input--square" name="level" id="level">
			<option value="">-- Pilih Level --</option>
			<option value="1" >LEVEL 1</option>
			<option value="2" >LEVEL 2</option>
			<option value="3" >LEVEL 3</option>
		</select>
		
	</div>
	
	<div class="form-group m-form__group" id="form-parent1">
		<label for="parent1">
			<b>Parent 1</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent1" id="parent1">
			@if(isset($parent1))
			<option value="{{ $parent1->id_ac }}">{{ strtoupper($parent1->nama) }}</option>
			@endif
			<option value="">-- Pilih Jenis --</option>
		</select>	
	</div>
	
	<div class="form-group m-form__group" id="form-parent2">
		<label for="parent2">
			<b>Parent 2</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent2" id="parent2">
			@if(isset($parent2))
			<option value="{{ $parent2->id_ac }}">{{ strtoupper($parent2->nama) }}</option>
			@endif
			
			<option value="">-- Pilih Parent 2 --</option>
		</select>
		
	</div>

	
	<div class="form-group m-form__group col-md-6">
		<label for="is_aktif">
			<b>Is Aktif</b>
		</label>
		
		<div class="row">
			<div class="col-md-12 checkbox">
				<label><input type="checkbox" value="1" id="is_aktif" name="is_aktif"> Aktif ?</label>
			</div>
		</div>
		
		@if ($errors->has('is_aktif'))
		<label style="color: red">
			{{ $errors->first('is_aktif') }}
		</label>
		@endif
	</div>
	
	@include('template.inc_action')
	
</form>

<script type="text/javascript">
	
	@if(isset($data->tipe))
	$("#tipe").val('{{ $data->tipe }}');
	@endif
	
	@if(isset($data->is_aktif) and $data->is_aktif==1)
		$("#is_aktif").prop("checked", true);
		@endif
	
	@if(isset($data->level))
	$("#level").val('{{ $data->level }}');
	@endif
	
	@if(isset($data->level) and $data->level = 2)
	$("#form-parent1").prop("hidden", false);
	@else
	$("#form-parent1").prop("hidden", true);
	@endif
	
	@if(isset($data->level) and $data->level = 3)
	$("#form-parent2").prop("hidden", false);
	@else
	$("#form-parent2").prop("hidden", true);
	@endif
	
	$('#level').on('change', function() {
		if (this.value > 1 ) {
            $("#form-parent1").prop("hidden", false);
			$.ajax({
				type: "GET", 
				url: "{{ url("getAC1") }}", 
				dataType: "json",
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){
						$("#parent1").empty();
						$("#parent1").append('<option value=' + 0 + '>' + "-- Pilih Parent --" + '</option>');
					$.each(response,function(key, value)
					{
	                    $("#parent1").append('<option value=' + value.kode + '>' + value.value + '</option>');
	                });

				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});

        }else{
            $("#form-parent1").prop("hidden", true);
    		$("#form-parent2").prop("hidden", true);
    		$("#form-parent3").prop("hidden", true);
        }
    });

	$('#parent1').on('change', function() {
		var id = this.value;
        $.ajax({
				type: "GET", 
				url: "{{ url('getAC2') }}/"+id,
				dataType: "json",
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){
						$("#parent2").empty();
						$("#parent2").append('<option value=' + 0 + '>' + "-- Pilih Parent --" + '</option>');
					$.each(response,function(key, value)
					{
	                    $("#parent2").append('<option value=' + value.kode + '>' + value.value + '</option>');
	                });

				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});

			if($('#level').val() > 2){
				$("#form-parent2").prop("hidden", false);
			}

		if (this.value == 0) {
			$("#form-parent2").prop("hidden", true);
		}
    });

	$('#parent2').on('change', function() {
		var id = this.value;
        $.ajax({
				type: "GET", 
				url: "{{ url('getAC3') }}/"+id,
				dataType: "json",
				beforeSend: function(e) {
					if(e && e.overrideMimeType) {
						e.overrideMimeType("application/json;charset=UTF-8");
					}
				},
				success: function(response){
						$("#parent3").empty();
						$("#parent3").append('<option value=' + 0 + '>' + "-- Pilih Parent --" + '</option>');
					$.each(response,function(key, value)
					{
	                    $("#parent3").append('<option value=' + value.kode + '>' + value.value + '</option>');
	                });

				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});

			if($('#level').val() > 3){
				$("#form-parent3").prop("hidden", false);
			}

		if (this.value == 0) {
			$("#form-parent3").prop("hidden", true);
		}
    });
	
	function ganti(params) {
		
	}
	
</script>
@endsection