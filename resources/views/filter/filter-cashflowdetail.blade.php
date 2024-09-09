<div class="col-md-3">
	<label style="font-weight: bold;">
		Dari Tanggal
	</label>
	<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-3">
	<label style="font-weight: bold;">
		Sampai Tanggal
	</label>
	<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

<!-- <div class="row">
	<div class="col">
		<h6>Dari Tanggal</h6>
		<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(Session('dr_tgl')!= null){{Session('dr_tgl')}}@endif">
	</div>
	<div class="col">
		<h6>Sampai Tanggal</h6>
		<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(Session('dr_tgl')!= null){{Session('dr_tgl')}}@endif">
	</div>
</div> -->

@section('script')
<script>
	var d = new Date();
	var date = d.getDate();
	var month = d.getMonth() + 1;
	var bln  ="0"+month;
	var tahun = d.getFullYear();
	console.log(bln);
	console.log(tahun);
	@if(Session('bulan') != null)
	$("#bulan").val('{{ Session('bulan') }}');
	@else
	$("#bulan").val(bln);
	@endif
	@if(Session('tahun') != null)
	$("#tahun").val('{{ Session('tahun') }}');
	@else
	$("#tahun").val(tahun);
	@endif
	function html(){
		window.location = "{{ url(Request::segment(1)."/cetak") }}";
	}
	function excel(){
		window.location = "{{ url(Request::segment(1)."/excel") }}";
	}
</script>
@endsection