@extends('template.document2')

@section('data')
@if(Request::segment(1)=="biayahandling" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include("template.filter-collapse")
    <div class="row-mt 1">
        <div class="col-md-12" style="overflow-x:auto">
            <table class="table table-responsive table-striped" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th rowspan="2" >No. Handling</th>
                        <th colspan="3" class="text-center">Tanggal</th>
                        <th rowspan="2" class="text-center">Jumlah STT</th>
                        <th colspan="3" class="text-center">Nominal</th>
                        <th rowspan="2" class="text-center">Status Bayar</th>
                        <th rowspan="2" class="text-center">Action</th>
                    </tr>
                    <tr>
                        <td>Dibuat</td>
                        <td>Berangkat</td>
                        <td>Selesai</td>

                        <td>Biaya</td>
                        <td>Dibayar</td>
                        <td>Kurang</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $value->kode_handling }}</td>
                        <td>{{ dateindo($value->created_at) }}</td>
                        <td>@if($value->tgl_berangkat!=null){{ dateindo($value->tgl_berangkat) }}@endif</td>
                        <td>@if($value->tgl_selesai!=null){{ dateindo($value->tgl_selesai) }}@endif</td>
                        <td class="text-center">{{ $value->stt }}</td>
                        <td>{{ tonumber($value->total) }}</td>
                        <td>{{ tonumber($value->bayar) }}</td>
                        <td>{{ tonumber($value->total-$value->bayar) }}</td>
                        <td class="text-center">
                            @if($value->total==$value->bayar)
                            <i class="fa fa-check" style="color: green"></i>
                            @else
                            <i class="fa fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>
                            <a href="{{ Request::segment(1)."/".$value->id_handling }}/show" class="btn btn-sm btn-primary"  data-placement="bottom" title="Detail Biaya Handling">
                                <span><i class="fa fa-eye"></i></span>  Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row" style="margin-top: 4%; font-weight: bold;">
            @include("template.paginator")
        </div>
    </div>
</form>
@endif
@endsection


@section('script')

<script>
    $.ajax({
        type: "GET",
        url: "{{ url("getPerusahaan") }}",
        dataType: "json",
        beforeSend: function (e) {
            if (e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function (response) {
            $.each(response, function (key, value) {
                $("#filterperush").append('<option value=' + value.kode + '>' + value.value + '</option>');
            });

            @if(Session('id_perush')!=null)
            $("#filterperush").val('{{ Session('id_perush') }}');
            @endif
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError);
        }
    });

    $('#id_handling').select2({
        placeholder: 'Cari Handling ....',
        ajax: {
            url: '{{ url('getDmHandling') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#id_pendapatan').empty();
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
    @if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

	@if(isset($filter["handling"]->kode_handling))
	$("#id_handling").empty();
	$("#id_handling").append('<option value="{{ $filter["handling"]->id_handling }}">{{ strtoupper($filter["handling"]->kode_handling) }}</option>');
	@endif

	@if(get_admin() and isset($filter["id_perush"]))
	$("#filterperush").val('{{ $filter["id_perush"] }}');
	@endif

	$("#shareselect").on("change", function(e) {
		$("#form-select").submit();
	});
</script>
@endsection
