@extends('template.document')

@section('data')

@if(Request::segment(1)=="dporder" && (Request::segment(2)==null or Request::segment(2)=="page" or Request::segment(2)=="filter"))
@include("template.filter2")

<table class="table table-responsive table-striped" id="html_table" width="100%">
    <thead style="background-color: grey; color : #ffff">
        <tr>
            <th>No</th>
            <th>ID STT</th>
            <th>Perusahaan</th>
            <th>Pelanggan</th>
            <th>Tanggal DP</th>
            <th>Nilai DP</th>
            <th>Info DP</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>@if(isset($value->id_stt)){{ $value->id_stt}}@endif</td>
                <td>@if(isset($value->perusahaan->nm_perush)){{ strtoupper($value->perusahaan->nm_perush) }}@endif</td>
                <td>@if(isset($value->pelanggan->nm_pelanggan)){{ strtoupper($value->pelanggan->nm_pelanggan) }}@endif</td>
                <td>@if(isset($value->tgl_dp)){{ dateindo($value->tgl_dp) }}@endif</td>
                <td>{{ number_format($value->n_dp, 0, ',', '.')}}</td>
                <td>{{ $value->info_dp }}</td>
                <td>
                    {!! inc_dropdown($value->id_dp) !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="row" style="margin-top: 4%; font-weight: bold;">
    <div class="col-md-2">
        Halaman : <b>{{ $data->currentPage() }}</b>
    </div>
    <div class="col-md-2">
        Jumlah Data : <b>{{ $data->total() }}</b>
    </div>
    <div class="col-md-3">
        {{-- rubah setia view disini --}}
        @if(Request::segment(2)=="filter")
        <form method="POST" action="{{ url('dporder/filter') }}" id="form-share" name="form-share">
            @else
            <form method="POST" action="{{ url('dporder/page') }}" id="form-share" name="form-share">
                @endif
                @csrf
                <select class="form-control" style="font-weight: bold;" id="shareselect" name="shareselect">
                    <option value="10">-- Tampil 10 Data --</option>
                    <option value="50">50 Data</option>
                    <option value="100">100 Data</option>
                    <option value="500">500 Data</option>
                </select>
            </form>
        </div>
        <div class="col-md-5" style="width: 100%">
            {{ $data->links() }}
        </div>
    </div>
@endif

@endsection

@section("script")

<script type="text/javascript">
@if(isset($page))
	$("#shareselect").val('{{ $page }}');
@endif
    $("#shareselect").on("change", function(e) {
    	$("#form-share").submit();
	});
$('#id_pelanggan').select2({
    placeholder: 'Cari Pelanggan ....',
    minimumInputLength: 3,
    allowClear: true,
    ajax: {
        url: '{{ url('getPelanggan') }}',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            $('#id_pelanggan').empty();
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

$('#id_pelanggan').on("change", function(e) { 
   $("#nm_pelanggan").val($("#id_pelanggan").val());
});

@if(Request::segment(3)=="edit")
    $('#id_pelanggan').append($('<option>', {value: {{ $data->id_pelanggan }},text: '{{ $pelanggan->nm_pelanggan }}'}));
    
    @if(isset($data->tgl_dp))
        $("#tgl_dp").val('{{ $data->tgl_dp }}');
    @endif

    @if(isset($data->n_dp))
        $("#n_dp").val('{{ $data->n_dp }}');
    @endif
    
@endif

</script>

@endsection