@extends('template.document2')

@section('data')

@if(Request::segment(1)=="sttterima" && Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter")
<form method="GET" action="{{ url(Request::segment(1)."/filter") }}" enctype="multipart/form-data" id="form-select">
    @include('template.filter-collapse')
    <div class="row mt-1">
        <div class="col-md-12">
            <table class="table table-responsive table-hover" style="width=100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No. STT</th>
                        <th>Perusahaan Asal</th>
                        <th>Layanan</th>
                        <th>Pengirim</th>
                        <th>Penerima</th>
                        <th>Tgl Berangkat</th>
                        <th>Tgl Tiba</th>
                        <th>Status</th>
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
                        <td>
                            <a href="{{ url(Request::segment(1)).'/'.$value->id_stt.'/show' }}" class="class-edit">
                                {{ strtoupper($value->kode_stt) }}
                            </a>
                            <br>
                            <label style="font-size: 8pt">  > {{ dateindo($value->tgl_masuk) }}</label>
                        </td>
                        <td>
                            @if(isset($value->nm_perush)){{ $value->nm_perush }}@endif
                        </td>
                        <td>
                            @if(isset($value->nm_layanan)){{ $value->nm_layanan }}@endif
                        </td>
                        <td>
                            @if(isset($value->pengirim_nm))
                            {{ strtoupper($value->pengirim_nm) }}
                            @endif
                            <br>
                            <label  style="font-size: 8pt"> > {{ $value->kota_asal }}</label>
                        </td>
                        <td>
                            {{ $value->penerima_nm }}
                            <br>
                            <label style="font-size: 8pt">  > {{ $value->kota_tujuan }}</label>
                        </td>
                        <td>
                            {{ dateindo($value->tgl_berangkat) }}
                        </td>
                        <td>
                            {{ dateindo($value->tgl_sampai) }}
                        </td>
                        <td>
                            @if(isset($value->nm_status)){{ $value->nm_status }}@endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include("template.paginator")
    </div>
</form>
@endif
@endsection

@section('script')
<script>
    
    $("#shareselect").on("change", function(e) {
        $("#form-select").submit();
    });
    
    $('#filterasal').select2({
        placeholder: 'Cari Kota Asal ....',
        ajax: {
            url: '{{ url('getwilayah') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterasal').empty();
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
    
    $('#filtertujuan').select2({
        placeholder: 'Cari Kota Tujuan ....',
        ajax: {
            url: '{{ url('getwilayah') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filtertujuan').empty();
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
            url: '{{ url('getSttTerima') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterstt').empty();
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
    
    $('#filterperush').select2({
        placeholder: 'Cari Perusahaan ....',
        ajax: {
            url: '{{ url('getPerusahExcept') }}',
            minimumInputLength: 3,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#filterperush').empty();
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

    @if(isset($filter["id_tujuan"]->nama_wil))
	$("#filtertujuan").empty();
	$("#filtertujuan").append('<option value="{{ $filter["id_tujuan"]->id_wil }}">{{ strtoupper($filter["id_tujuan"]->nama_wil) }}</option>');
	@endif
	
	@if(isset($filter["id_asal"]->nama_wil))
	$("#filterasal").empty();
	$("#filterasal").append('<option value="{{ $filter["id_asal"]->id_wil }}">{{ strtoupper($filter["id_asal"]->nama_wil) }}</option>');
	@endif
    
	@if(isset($filter["id_stt"]->kode_stt))
	$("#filterstt").empty();
	$("#filterstt").append('<option value="{{ $filter["id_stt"]->id_stt }}">{{ strtoupper($filter["id_stt"]->kode_stt) }}</option>');
	@endif

    @if(isset($filter["id_perush_asal"]->nm_perush))
	$("#filterperush").empty();
	$("#filterperush").append('<option value="{{ $filter["id_perush_asal"]->id_perush }}">{{ strtoupper($filter["id_perush_asal"]->nm_perush) }}</option>');
	@endif

    @if(isset($filter["id_layanan"]))
    $("#filterlayanan").val('{{ $filter["id_layanan"] }}');
    @endif

    @if(isset($filter["id_status"]))
    $("#filterstatusstt").val('{{ $filter["id_status"] }}');
    @endif
    
    @if(isset($filter["page"]))
	$("#shareselect").val('{{ $filter["page"] }}');
	@endif

</script>
@endsection