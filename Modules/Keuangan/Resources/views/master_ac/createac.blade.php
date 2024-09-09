@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('masterac') }}" enctype="multipart/form-data">
	@csrf
	<div class="form-group m-form__group">
		<label for="nama">
			<b>Nama</b> <span class="span-required"> * </span>
		</label>

		<input type="text" class="form-control m-input m-input--square" name="nama" id="nama" required="required">
		
		@if ($errors->has('nama'))
		<label style="color: red">
			{{ $errors->first('nama') }}
		</label>
		@endif
	</div>

	<div class="form-group m-form__group">
		<label for="tipe">
			<b>Tipe</b> <span class="span-required"> * </span>
		</label>

		<select class="form-control m-input m-input--square" name="tipe" id="tipe">
            <option value="">-- Pilih Tipe --</option>
            <option value="D" >Debit</option>
			<option value="K" >Kredit</option>
		</select>
		
		@if ($errors->has('tipe'))
		<label style="color: red">
			{{ $errors->first('tipe') }}
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

	<div class="form-group m-form__group" id="form-jenis">
		<label for="jenis">
			<b>Jenis</b> <span class="span-required"> * </span>
		</label>

		<select class="form-control m-input m-input--square" name="jenis" id="jenis">
            <option value="">-- Pilih Jenis --</option>
            <option value="N" >Neraca</option>
			<option value="R" >Rugi Laba</option>
		</select>
		
		@if ($errors->has('jenis'))
		<label style="color: red">
			{{ $errors->first('jenis') }}
		</label>
		@endif
	</div>

    <div class="form-group m-form__group" id="form-parent1">
		<label for="parent1">
			<b>Parent 1</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent1" id="parent1">
			<option value="0">-- Pilih Parent 1 --</option>
		</select>
		
	</div>

    <div class="form-group m-form__group" id="form-parent2">
		<label for="parent2">
			<b>Parent 2</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent2" id="parent2">
			<option value="">-- Pilih Parent 2 --</option>
		</select>
		
	</div>

	<div class="form-group m-form__group" id="form-parent3">
		<label for="parent3">
			<b>Parent 3</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent3" id="parent3">
			<option value="">-- Pilih Parent 3 --</option>
		</select>
	</div>


	@include('template.inc_action')

</form>

<script type="text/javascript">
    $("#form-parent1").prop("hidden", true);
    $("#form-parent2").prop("hidden", true);
    $("#form-parent3").prop("hidden", true);
	$("#form-jenis").prop("hidden", true);
	
    $('#level').on('change', function() {
		$("#form-jenis").prop("hidden", false);
		$("#jenis").val('');
		
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
						console.log(response);
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
	
	$('#jenis').on('change', function() {
		var id = this.value;
		$.ajax({
			type: "GET", 
			url: "{{ url('getAC1') }}/"+id,
			dataType: "json",
			beforeSend: function(e) {
				if(e && e.overrideMimeType) {
					e.overrideMimeType("application/json;charset=UTF-8");
				}
			},
			success: function(response){
				$("#parent1").empty();
				console.log(response)
				$.each(response,function(key, value)
				{
					$("#parent1").append('<option value=' + value.kode + '>' + value.value + '</option>');
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
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
						console.log(response);
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
						console.log(response);
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