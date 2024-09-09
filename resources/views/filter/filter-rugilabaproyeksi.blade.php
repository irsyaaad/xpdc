<div class="col-md-4">
	<label style="font-weight: bold;">
		Dari Tanggal
	</label>
	<input type="date" class="form-control" name="dr_tgl" id="dr_tgl" value="@if(isset($filter['dr_tgl'])){{$filter['dr_tgl']}}@endif">
</div>

<div class="col-md-4">
	<label style="font-weight: bold;">
		Sampai Tanggal
	</label>
	<input type="date" class="form-control" name="sp_tgl" id="sp_tgl" value="@if(isset($filter['sp_tgl'])){{$filter['sp_tgl']}}@endif">
</div>

@section('script')
<script>
	// var d = new Date();
	// var date = "01";
	// var month = d.getMonth() + 1;
	// var bln  ="0"+month;
	// var tahun = d.getFullYear();

	// var tanggal = date+'/'+(month)+'/'+(tahun);
	// var cuks	= tahun+'-'+month+'-'+date;
	// console.log(tanggal);

	// var dr_tgl = new Date("01/01/2022").getFullYear()+'-'+new Date("01/01/2022").getMonth()+1+'-'+new Date("01/01/2022").getDate();
	// console.log(dr_tgl);

	// $("#dr_tgl").val(dr_tgl);

	function html(){
		window.location = "{{ url(Request::segment(1)."/cetak") }}";
	}
	function excel(){
		window.location = "{{ url(Request::segment(1)."/excel") }}";
	}
</script>
@endsection
