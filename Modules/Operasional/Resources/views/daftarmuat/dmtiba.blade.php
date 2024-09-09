
@extends('template.document2')

@section('data')

@if(Request::segment(1)=="dmtiba" && Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	<div class="row">
		<div class="col-md-12">
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff;">
					<tr>
						<th>No</th>
						<th>No. DM</th>
						<th class="text-center" colspan="3">Pengirim Dan Penerima</th>
						<th>Sopir / Armada</th>
						<th>Informasi</th>
						<th>Status DM</th>
						<th>Action</th>
					</tr>
				</thead>
				
				<tbody >
					@foreach($data as $key => $value)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							<a href="{{ url('dmtiba') }}/{{ $value->id_dm }}/show">
								<b>{{ strtoupper($value->kode_dm) }}</b>
							</a>
							<br>@if(isset($value->nm_layanan)){{ strtoupper($value->nm_layanan) }}@endif
						</td>
						<td>
							<a href="#" onclick="detaildm('{{ $value->id_dm }}')" class="class-edit" style="color:black; text-decoration:none">
								@if(isset($value->perush_asal)) {{ strtoupper($value->perush_asal) }} @endif
							</a><br>berangkat:<br>{{ dateindo($value->tgl_berangkat) }}
						</td>
						<td>
							<i class="fa fa-arrow-right"></i>
						</td>
						<td >
							<a href="#" onclick="detaildm('{{ $value->id_dm }}')" class="class-edit" style="color:black; text-decoration:none">
								@if(isset($value->perush_tujuan)) {{ strtoupper($value->perush_tujuan) }} @endif
							</a><br>Est tiba:<br>{{ dateindo($value->tgl_sampai) }}
						</td>

						<td>@if(isset($value->nm_sopir)){{ strtoupper($value->nm_sopir) }}@endif<br>armada:<br>@if(isset($value->nm_armada)){{ strtoupper($value->nm_armada) }}@endif</td>
						<td>
							Kg : {{ $value->total_berat }}<br>
							KgV : {{ $value->total_volume }} <br>
							M3 : {{ $value->total_kubik }} <br>
							Jumlah Stt : {{ $value->total_stt }}
						</td>
						<td>

							@if($value->id_status == "1")
							<span class="label label-info label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@elseif($value->id_status == "5")
							<span class="label label-success label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@else
							<span class="label label-primary label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@endif
						</td>

						<td class="text-center">

							<div class="dropdown">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
									<a href="{{ url('dmtiba') }}/{{ $value->id_dm }}/show" class="dropdown-item" data-toggle="tooltip" data-placement="bottom" title="Detail DM">
										<span><i class="fa fa-eye"></i></span> Detail
									</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetak") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak DM</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetaknotarif") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak DM Tanpa Tarif</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetaktally") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak Tally DM</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetaklistbarcode") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> List STT DM (Barcode)</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
					@if(count($data) < 1)
					<tr>
						<td colspan="10" class="text-center">Data Kosong</td>
					</tr>
					@endif
				</tbody>

			</table>
		</div>
		@include("template.paginator")
	</div>
</form>

@include('operasional::daftarmuat.confirm')
@elseif(Request::segment(3)=="show")
<br>
@if(isset($detail))
@include('operasional::daftarmuat.inc_detail')
@endif
@endif

@endsection

@section('script')
<script type="text/javascript">
	$("#id_layanan").select2();
	$("#id_perush_dr").select2();

	$('#id_dm').select2({
		placeholder: 'Cari Nomor DM ....',
		ajax: {
			url: '{{ url('dmtrucking/getdmtiba') }}',
			minimumInputLength: 3,
			dataType: 'json',
			delay: 250,
			processResults: function (data) {
				$('#id_dm').empty();
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.value,
							id: item.kode
						}
					})
				};
			},
			cache: true
		}
	});

	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});

	@if(isset($filter["id_dm"]->id_dm))
	$("#id_dm").empty();
	$("#id_dm").append('<option value="{{ $filter["id_dm"]->id_dm }}">{{ strtoupper($filter["id_dm"]->kode_dm) }}</option>');
	@endif

	@if(isset($filter["id_perush_dr"]))
	$("#id_perush_dr").val('{{ $filter["id_perush_dr"] }}');
	@endif

	@if(isset($filter["id_layanan"]))
	$("#id_layanan").val('{{ $filter["id_layanan"] }}');
	@endif

	@if(isset($filter["id_status"]))
	$("#id_status").val('{{ $filter["id_status"] }}');
	@endif

	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

    @if(isset($filter["is_tiba"]))
	$("#is_tiba").val('{{ $filter["is_tiba"] }}');
	@endif

</script>
@endsection
