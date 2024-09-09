@extends('template.document2')

@section('data')
    <form method="GET" action="{{ url(Request::segment(1)) }}" enctype="multipart/form-data" id="form-select">
        <div class="card-body row">
            @include('filter.filter-'.Request::segment(1))
            <input type="hidden" name="_method" value="GET">
        </div>
        @csrf
        <div class="col-md-12">
            <div class="table-responsive" style="display: block;overflow-x: auto; margin-top:15px">
                <table class="table table-hover">
                    <thead style="background-color: grey; color : #ffff; font-size:11pt;">
                        <tr>
                            <th>No</th>
                            <th>Kode STT</th>
                            <th>Marketing</th>
                            <th>Layanan</th>
                            <th>Pelanggan > No. AWB</th>
                            <th>Pengirim > Asal</th>
                            <th>Penerima > Tujuan</th>
                            <th>Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a
                                        href="{{ url('stt') . '/' . $item->id_stt . '/show' }}">{{ $item->kode_stt }}</a><br>{{ dateindo($item->tgl_masuk) }}
                                </td>
                                <td>{{ $item->marketing->nm_marketing ?? 'DATANG SENDIRI' }}</td>
                                <td>{{ $item->layanan->nm_layanan ?? '' }}</td>
                                <td>{{ $item->pelanggan->nm_pelanggan ?? '' }}<br>{{ $item->no_awb ?? '' }}</td>
                                <td>{{ $item->pengirim_nm }}<br>{{ $item->asal->nama_wil ?? '' }}</td>
                                <td>{{ $item->penerima_nm }}<br>{{ $item->tujuan->nama_wil ?? '' }}</td>
                                <td>{{ toRupiah($item->c_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <script>
        $('#filterasal').select2({
            placeholder: 'Cari Kota Asal ....',
            ajax: {
                url: '{{ url('getwilayah') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#filterasal').empty();
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

        $('#filtertujuan').select2({
            placeholder: 'Cari Kota Tujuan ....',
            ajax: {
                url: '{{ url('getwilayah') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#filtertujuan').empty();
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

        $('#f_awb').select2({
            placeholder: 'Cari No Awb ....',
            ajax: {
                url: '{{ url('getAwb') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#f_awb').empty();
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

        $('#f_pelanggan').select2({
            placeholder: 'Cari Pelanggan ....',
            ajax: {
                url: '{{ url('getPelanggan') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#f_pelanggan').empty();
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

        $('#f_penerima').select2({
            placeholder: 'Cari Penerima ....',
            ajax: {
                url: '{{ url('getPenerima') }}',
                minimumInputLength: 3,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $('#f_penerima').empty();
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
    </script>
@endsection
