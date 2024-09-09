<div class="col-md-3">
	<label style="font-weight: bold;">
		Pilih Bulan
	</label>
	<select class="form-control" id="bulan" name="bulan">
		<option value="0">-- Pilih Bulan --</option>
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

<div class="col-md-3">
	<label style="font-weight: bold;">
		Pilih Tahun
	</label>
	<select name="tahun" class="form-control" id="tahun" name="tahun">
		<option selected="selected" value="0">-- Pilih Tahun --</option>
		<?php
		for($i=date('Y'); $i>=date('Y')-10; $i-=1){
			echo"<option value='$i'> $i </option>";
		}
		?>
	</select>
</div>

@section('script')
<script>
	@if(Session('bulan') != null)
	$("#bulan").val('{{ Session('bulan') }}');
	@else
	$("#bulan").val('{{ date("m") }}');
	@endif
	@if(Session('tahun') != null)
	$("#tahun").val('{{ Session('tahun') }}');
	@else
	$("#tahun").val('{{ date("Y") }}');
	@endif
</script>

@endsection
