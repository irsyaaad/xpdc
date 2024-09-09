@extends('template.document')
@section('data')
@if(Request::segment(2)==null or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
	@include('template.filter-collapse')
	@csrf
	<input type="hidden" name="_method" value="GET">
	<div class="row mt-0">
		<div class="col-md-12">
			<table class="table table-responsive table-hover">
				<thead style="background-color: grey; color : #ffff; font-size:11pt;">
					<tr>
						<th>No</th>
						<th>No. DM</th>
						<th class="text-center" colspan="3">Asal Dan Tujuan</th>
						<th>Sopir > Armada</th>
						<th>PJ Asal > PJ Tujuan</th>
						<th>Jumlah STT</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td width="5%">{{ ($key+1) }}</td>
						<td>
							<a href="{{ url(Request::segment(1)).'/'.$value->id_dm.'/show' }}" class="class-edit">{{ strtoupper($value->kode_dm) }}</a>
							<br>{{ dateindo($value->created_at) }}
						</td>
						<td>
							<a href="#" onclick="detaildm('{{ $value->id_dm }}')" class="class-edit" style="color:black; text-decoration:none">
								@if(isset($value->perush_asal)) {{ strtoupper($value->perush_asal) }} @endif
							</a><br>berangkat:<br>
							@if(isset($value->atd) and $value->atd != null)
							{{ dateindo($value->atd) }}
							@endif
						</td>
						<td>
							<i class="fa fa-arrow-right"></i>
						</td>
						<td >
							<a href="#" onclick="detaildm('{{ $value->id_dm }}')" class="class-edit" style="color:black; text-decoration:none">
								@if(isset($value->perush_tj)) {{ strtoupper($value->perush_tj) }} @endif
							</a><br>Est tiba:<br>
							@if(isset($value->ata) and $value->ata != null)
							{{ dateindo($value->ata) }}
							@endif
						</td>
						<td>
							@if(isset($value->nm_sopir)){{ strtoupper($value->nm_sopir) }}@endif<br> > <br>@if(isset($value->nm_armada)){{ strtoupper($value->nm_armada) }}@endif
						</td>
						<td>
							{{ strtoupper($value->nm_pj_dr) }}
							<br> > {{ strtoupper($value->nm_pj_tuju) }}
						</td>

						<td>
							{{ $value->stt }}
						</td>
						<td>
							@if(isset($value->nm_status) and $value->id_status == "1")
							<span class="label label-info label-inline mr-7">{{ strtoupper($value->nm_status) }}</span>
							@elseif(isset($value->nm_status) and $value->id_status == "5")
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
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/show") }}"><i class="fa fa-eye"></i> Detail</a>
									@if($value->id_status==1)
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/edit") }}"><i class="fa fa-pencil"></i> Edit</a>
									<a class="dropdown-item" href="#" onclick="CheckDelete('{{ url(Request::segment(1).'/'.$value->id_dm) }}')"><i class="fa fa-trash"></i> Hapus</a>
									@endif

									@php
									$a_status = $value->id_status;
									$a_status = (Int)$a_status+1;
									@endphp
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetak") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak Manifest</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetak-no-keterangan") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak Manifest (Tanpa Keterangan)</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetaknotarif") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> Cetak Manifest Tanpa Tarif</a>
									<a class="dropdown-item" href="{{ url(Request::segment(1).'/'.$value->id_dm."/cetaklistbarcode") }}" target="_blank" rel="nofollow"><i class="fa fa-print"></i> List Resi Manifest (Barcode)</a>
                                    <a class="dropdown-item" href="{{route('updatestatus',$value->id_dm)}}" target="_blank" rel="nofollow"><i class="fa fa-edit"></i> Update Status</a>
								</div>
							</div>
						</td>
					</tr>
					@endforeach
					@if(count($data) < 1)
					<tr>
						<td colspan="11" class="text-center">Data Kosong</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		@include('template.paginator')
	</div>
</form>
{{-- @include('operasional::modal') --}}
@include('operasional::daftarmuat.confirm')
@elseif(Request::segment(2)=="create" or Request::segment(3)=="edit")
@include('operasional::daftarmuat.createdmtrucking')
@endif
@endsection

@section('script')
@if(Request::segment(2)==null or  Request::segment(2)=="filter")
<script type="text/javascript">
	$(document).ready(function(){
		$("#shareselect").on("change", function(e) {
			$("#form-select").submit();
		});
	});

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
				$("#id_armada").val(response.id_armada);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
	});

	$('#id_dm').select2({
		placeholder: 'Cari Nomor DM ....',
		ajax: {
			url: '{{ url('dmtrucking/getdm') }}',
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

	@if(isset($filter["id_sopir"]))
	$("#id_sopir").val('{{ $filter["id_sopir"] }}');
	@endif

	@if(isset($filter["id_armada"]))
	$("#id_armada").val('{{ $filter["id_armada"] }}');
	@endif

	@if(isset($filter["id_status"]))
	$("#id_status").val('{{ $filter["id_status"] }}');
	@endif

	@if(isset($filter["id_perush_tj"]))
	$("#id_perush_tj").val('{{ $filter["id_perush_tj"] }}');
	@endif

    @if(isset($filter["is_tiba"]))
	$("#is_tiba").val('{{ $filter["is_tiba"] }}');
	@endif

	@if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

	@if(isset($filter["id_dm"]->kode_dm))
	$("#id_dm").empty();
	$("#id_dm").append('<option value="{{ $filter["id_dm"]->id_dm }}">{{ strtoupper($filter["id_dm"]->kode_dm) }}</option>');
	@endif

	@if (isset($filter['id_stt']->kode_stt))
    $("#filterstt").empty();
    $("#filterstt").append('<option value="{{ $filter['id_stt']->id_stt }}">{{ strtoupper($filter['id_stt']->kode_stt) }}</option>');
    @endif

</script>
@endif

@if(Request::segment(3)=="edit" or  Request::segment(2)=="create")
@include('operasional::daftarmuat.js-dm')
@endif
@endsection
