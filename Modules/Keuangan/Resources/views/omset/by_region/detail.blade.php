@extends('template.document2')

@section('data')
    <style>
        th {
            text-align: center;
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }

        td {
            padding: 5px 20px 5px 20px !important;
            vertical-align: center;
        }
    </style>
    <div class="col-md-12 text-center">
        <h5><b>Detail Laporan Omset By Region {{ $filter['tipe'] }} {{ $wilayah->nama_wil }}</b><br>
            Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</h5>
    </div>
    <div class="table-responsive mt-3" style="display: block; overflow-x: auto;white-space: nowrap;">
        <table class="table table-sm table-hover" id="jurnal-table">
            <thead style="background-color: grey; color : #ffff">
                <th>No</th>
                <th>Kode STT</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>Berat</th>
                <th>Volume</th>
                <th>Kubik</th>
                <th>Koli</th>
                <th>Total (Rp.)</th>
            </thead>
            <tbody>
                @foreach ($layanan as $item)
                    @isset($data[$item->id_layanan])
                        <tr style="background-color: rgb(204, 202, 202)" class="tr-bold">
                            <td colspan="9">{{ $item->nm_layanan }}</td>
                        </tr>
                        @php
                            $total_berat = 0;
                            $total_volume = 0;
                            $total_kubik = 0;
                            $total_koli = 0;
                            $total = 0;
                        @endphp
                        @foreach ($data[$item->id_layanan] as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->kode_stt }}</td>
                                <td>{{ dateindo($item->tgl_masuk) }}</td>
                                <td>{{ strtoupper($item->nm_pelanggan) }}</td>
                                <td>{{ $item->n_berat }}</td>
                                <td>{{ $item->n_volume }}</td>
                                <td>{{ $item->n_kubik }}</td>
                                <td>{{ $item->n_koli }}</td>
                                <td>{{ toNumber($item->c_total) }}</td>
                            </tr>
                            @php
                                $total_berat += $item->n_berat;
                                $total_volume += $item->n_volume;
                                $total_kubik += $item->n_kubik;
                                $total_koli += $item->n_koli;
                                $total += $item->c_total;
                            @endphp
                        @endforeach
                        <tr class="tr-bold">
                            <td colspan="4" class="text-center">Total</td>
                            <td>{{ $total_berat }}</td>
                            <td>{{ $total_volume }}</td>
                            <td>{{ $total_kubik }}</td>
                            <td>{{ $total_koli }}</td>
                            <td>{{ toNumber($total) }}</td>
                        </tr>
                    @endisset
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
