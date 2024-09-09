
@extends('template.document')

@section('data')

<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	@csrf
	<div class="row">
		<div class="col-md-12">
			<table class="table table-responsive table-striped"  width="100%">
				<thead style="background-color: grey; color : #ffff">
					<tr>
						<th>No</th>
						<th>No. DM</th>
						<th>Vendor</th>
						<th>Kota Asa > Tujuan</th>
						<th>Tgl Masuk</th>
						<th>Tgl Berangkat</th>
						<th>Tgl Tiba</th>
						<th>Jumlah STT</th>
						<th>Status DM</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					@if(count($data)==null)
					<tr>
						<td colspan="11" class="text-center"> Tidak ada data </td>
					</tr>
					@endif
					@foreach($data as $key => $value)
					<tr>
						<td width="5%">{{ ($key+1) }}</td>
						<td>
							<a href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/show' }}" class="class-edit">{{ strtoupper($value->kode_dm) }}</a>
						</td>
						<td>
							@if(isset($value->vendor->nm_ven))
							{{ strtoupper($value->vendor->nm_ven) }}
							@elseif(isset($value->perush_tujuan->nm_perush))
							{{ strtoupper($value->perush_tujuan->nm_perush) }}
							@endif
						</td>
						<td>
							@if(isset($value->wilayah->nama_wil)){{ strtoupper($value->wilayah->nama_wil) }}@endif
							<br>
							>
							@if(isset($value->wilayah_tujuan->nama_wil)){{ strtoupper($value->wilayah_tujuan->nama_wil) }}@endif
						</td>
						<td>{{ dateindo($value->created_at) }}</td>
						<td>{{ dateindo($value->tgl_berangkat) }}</td>
						<td>{{ dateindo($value->tgl_sampai) }}</td>
						<td>{{ $value->stt }}</td>
						<td>
							@if($value->id_status=="1")
							<span class="label label-info label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@elseif($value->id_status > 1 and $value->id_status<7)
							<span class="label label-primary label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@else
							<span class="label label-success label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@endif
						</td>
						<td class="text-center">
							<div class="dropdown">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/show") }}"><i class="fa fa-eye"></i> Detail</a>
									@if($value->id_status==1 and $value->status_dm_ven ==1)
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
									<a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_dm) }}')"><i class="fa fa-trash"></i> Hapus</a>
									@endif
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetak") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak DM</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetak-no-keterangan") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak DM (Tanpa Keterangan)</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetaknotarif") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak DM Tanpa Tarif</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetaklistbarcode") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> List STT DM (Barcode)</a>
                                    <a class="dropdown-item" href="{{route('updatestatus',$value->id_dm)}}" target="_blank" rel="nofollow"><i class="fa fa-edit"></i> Update Status</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@include('template.paginate')
	</div>
</form>

@include('operasional::daftarmuat.confirm')
@endsection

@section('script')
<script type="text/javascript">

	$("#shareselect").on("change", function(e) {$("#form-select").submit();});

	$('#id_dm').select2({
		placeholder: 'Cari Nomor DM ....',
		ajax: {
			url: '{{ url('dmvendor/getdmvendor') }}',
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

	$('#filterstt').select2({
        placeholder: 'Cari STT ....',
        ajax: {
            url: '{{ url('getSttPerush') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                $('#filterstt').empty();
                return {
                    results: $.map(data, function(item) {
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

	@if(isset($filter["id_status"]))
	$("#id_status").val('{{ $filter["id_status"] }}');
	@endif

	@if(isset($filter["id_layanan"]))
	$("#f_layanan").val('{{ $filter["id_layanan"] }}');
	@endif

	@if(isset($filter["id_ven"]))
		$("#id_ven").val('{{ $filter["id_ven"] }}').trigger('change');
	@endif

	@if(isset($filter["id_asal"]))
	$("#f_asal").val('{{ $filter["id_asal"] }}').trigger('change');
	@endif

	@if(isset($filter["id_tujuan"]))
	$("#id_tujuan").val('{{ $filter["id_tujuan"] }}').trigger('change');
	@endif

	@if (isset($filter['id_stt']->kode_stt))
    $("#filterstt").empty();
    $("#filterstt").append('<option value="{{ $filter['id_stt']->id_stt }}">{{ strtoupper($filter['id_stt']->kode_stt) }}</option>');
    @endif

	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

	$('#f_asal').select2();
	$('#f_tujuan').select2();

</script>
@endsection
