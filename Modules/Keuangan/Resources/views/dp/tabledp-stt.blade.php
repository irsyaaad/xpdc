@extends('template.document')

@section('data')
<form action="{{ url(Request::segment(1)."/create") }}" class="col-xl-12" name="form-filter" id="form-filter" method="post">
            @csrf
            <div class="form-group row">
                @include('keuangan::bayar.filter-stt')
                <div class="col-md-2" style="padding-top:4px">
                    <br>
                    <button class="btn btn-md btn-primary"><span><i class="fa fa-search"></i></span></button>
                </div>
            </div>
        </form>
<table class="table" id="html_table" width="100%" style="margin-top: 1%">
    <thead>
        <tr>
            <th>Id stt</th>
            <th>Nama Pengirim</th>
            <th>Asal</th>
            <th>Tujuan</th>
            <th>No Hp</th>
            <th>Layanan</th>
            <th>Total</th>
            <th>Bayar DP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stt as $key => $value)
            <tr>
                <td>{{ $value->id_stt }}</td>
                <td>{{ $value->pengirim_nm }}</td>
                <td>{{ $value->nama_wil }}</td>
                <td>{{ $value->nama_wil }}</td>
                <td>{{ $value->pengirim_telp }}</td>
                <td>{{ $value->nm_layanan }}</td>
                <td>{{ $value->c_total }}</td>
                <td>
                    <a href="{{ url(Request::segment(1)).'/'.$value->id_stt.'/dp' }}" class="btn btn-sm btn-success">
                        <i class="fa fa-money"></i> DP
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
$('#stt').select2({
        placeholder: 'Masukkan STT yang dicari',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '{{ url('getStt') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                $('#stt').empty();
                return {
                    results: $.map(data, function (item) {
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
</script>
@endsection