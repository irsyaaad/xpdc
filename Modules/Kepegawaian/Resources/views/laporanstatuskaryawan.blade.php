@extends('template.document')

@section('data')
@if(Request::segment(1)=="laporanstatuskaryawan" or Request::segment(2)=="filter")
@include('kepegawaian::filter.statuskaryawan')
<div class="col-md-12" style="overflow-x:auto;">
	<table class="table table-striped table-responsive" style="margin-top: 10px">
		<thead style="background-color: grey; color : #ffff">
			<tr>
				<th>No</th>
				<th>Nama Karyawan</th>
				<th>Jenis Kelamin</th>
				<th>Bagian</th>
				<th>Jabatan</th>
				<th>Tgl masuk</th>
				<th>Status Karyawan</th>
				<th>Exp Date Status Karyawan</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $value)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>@if(isset($value->nm_karyawan)){{ strtoupper($value->nm_karyawan) }}@endif</td>
				<td>@if(isset($value->jenis_kelamin)){{ strtoupper($value->jenis_kelamin) }}@endif</td>
				<td>@if(isset($value->id_jenis)){{ strtoupper($value->jenis->nm_jenis) }}@endif</td>
				<td>@if(isset($value->id_jabatan)){{ strtoupper($value->jabatan->nm_jabatan) }}@endif</td>
				<td>@if(isset($value->tgl_masuk)){{ dateindo($value->tgl_masuk) }}@endif</td>
				<td>@if(isset($value->id_status_karyawan)){{ strtoupper($value->status_karyawan->nm_status_karyawan) }}@endif</td>
				@php $date=date("Y-m-d"); 
				
				@endphp
				@if(isset($value->tgl_selesai_sk) and strtotime($value->tgl_selesai_sk) < strtotime($date))
				<td style="color:red">@if(isset($value->tgl_selesai_sk)){{ dateindo($value->tgl_selesai_sk) }} (Exp)@endif</td>
				@else
				<td style="color:green">@if(isset($value->tgl_selesai_sk)){{ dateindo($value->tgl_selesai_sk) }}@endif</td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endif

@endsection
@section('script')
<script>
	 $("#f_perush").val('{{ Session("perusahaan")["id_perush"] }}').trigger("change");
	 
	@if(isset($filter["f_perush"]))
	$("#f_perush").val('{{ $filter["f_perush"] }}').trigger("change");
	@endif

	@if(isset($filter["f_id_jenis"]))
	$("#f_id_jenis").val('{{ $filter["f_id_jenis"] }}').trigger("change");
	@endif

	@if(isset($filter["f_id_status"]))
	$("#f_id_status").val('{{ $filter["f_id_status"] }}').trigger("change");
	@endif
	
	$("#f_perush").select2();
    $("#f_id_status").select2();
	$("#f_id_jenis").select2();
</script>
@endsection