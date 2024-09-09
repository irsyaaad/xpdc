@extends('template.document')

@section('data')
<form class="m-form m-form--fit m-form--label-align-right" method="POST" action="{{ url('proyeksi') }}" enctype="multipart/form-data">
	@csrf	
	<div class="form-group m-form__group">
		<label for="level">
			<b>Jenis</b>
		</label>
		<select class="form-control m-input m-input--square" name="level" id="level">
            <option value="">-- Pilih Jenis --</option>
            <option value="N" >NERACA</option>
			<option value="R" >RUGI LABA</option>
		</select>
		
	</div>

    <div class="form-group m-form__group" id="form-parent1">
		<label for="parent1">
			<b>AC 1</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent1" id="parent1">
			<option value="0">-- Pilih AC 1 --</option>
		</select>
		
	</div>

    <div class="form-group m-form__group" id="form-parent2">
		<label for="parent2">
			<b>AC 2</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent2" id="parent2">
			<option value="">-- Pilih AC 2 --</option>
		</select>
		
	</div>

	<div class="form-group m-form__group" id="form-parent3">
		<label for="parent3">
			<b>AC3 3</b>
		</label>
		<select class="form-control m-input m-input--square" name="parent3" id="parent3">
			<option value="">-- Pilih AC 3 --</option>
		</select>
		
	</div>

	<div class="form-group m-form__group" id="form-parent3">
		<label for="parent3">
			<b>Proyeksi (Rp.)</b>
		</label>
		<input type="text" name="n_proyeksi" id="n_proyeksi" class="form-control">
		
	</div>


	@include('template.inc_action')

</form>

<script type="text/javascript">
	$("#form-parent1").prop("hidden", true);
    $("#form-parent2").prop("hidden", true);
    $("#form-parent3").prop("hidden", true);

    $('#level').on('change', function() {
		$("#jenis").val('');
		var id = this.value;
		if (this.value != '' ) {
            $("#form-parent1").prop("hidden", false);
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
						console.log(response);
						$("#parent1").empty();
						$("#parent1").append('<option value=' + 0 + '>' + "-- Pilih AC1 --" + '</option>');
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
           
        }
    });

	$('#parent1').on('change', function() {
		var id = this.value;
		$("#form-parent2").prop("hidden", false);
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
						$("#parent2").append('<option value=' + 0 + '>' + "-- Pilih AC 2 --" + '</option>');
					$.each(response,function(key, value)
					{
	                    $("#parent2").append('<option value=' + value.kode + '>' + value.value + '</option>');
	                });

				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});
    	});

	$('#parent2').on('change', function() {
		var id = this.value;
		$("#form-parent3").prop("hidden", false);
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
						$("#parent3").append('<option value=' + 0 + '>' + "-- Pilih AC 3 --" + '</option>');
					$.each(response,function(key, value)
					{
	                    $("#parent3").append('<option value=' + value.kode + '>' + value.value + '</option>');
	                });

				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(thrownError);
				}
			});
    });		

</script>
@endsection