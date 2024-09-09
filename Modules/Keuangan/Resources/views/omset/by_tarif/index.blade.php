@extends('template.document2')

@section('data')

@include("filter.filter-".Request::segment(1))
<style>
    th {
        text-align: center;
    }
</style>
<div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
    @if ($filter['mode'] == "DETAIL")
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>No Stt</th>
                    <th>Tanggal Masuk</th>
                    <th>Nama Pelanggan</th>
                    <th>Tipe Barang</th>
                    <th>Koli</th>
                    <th>Berat</th>
                    <th>Volume</th>
                    <th>Total (Rp.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tarif as $tarif)
                    @isset($data[$tarif['id']])
                        @php
                            $total_berat    = 0;
                            $total_volume   = 0;
                            $total_koli     = 0;
                            $total_omset    = 0;
                        @endphp
                        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                            <td></td>
                            <td colspan="8">{{ $tarif['nama'] }}</td>
                        </tr>
                        @foreach ($data[$tarif['id']] as $key => $value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->kode_stt }}</td>
                                <td>{{ isset($value->tgl_masuk)? daydate($value->tgl_masuk).", ".dateindo($value->tgl_masuk) : '-' }}</td>
                                <td>{{ strtoupper($value->nm_pelanggan) }}</td>
                                <td>{{ $value->nm_tipe_kirim }}</td>
                                <td>{{ $value->n_koli }}</td>
                                <td>{{ $value->n_berat }}</td>
                                <td>{{ $value->n_volume }}</td>
                                <td class="text-right">{{ number_format($value->c_total, 0, ',', '.')}}</td>
                            </tr>
                            @php
                                $total_berat    += $value->n_berat;
                                $total_volume   += $value->n_volume;
                                $total_koli     += $value->n_koli;
                                $total_omset    += $value->c_total;
                            @endphp
                        @endforeach
                        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                            <td class="text-center" colspan="5">Sub Total {{ $tarif['nama'] }}</td>
                            <td>{{ $total_koli }}</td>
                            <td>{{ $total_berat }}</td>
                            <td>{{ $total_volume }}</td>
                            <td class="text-right">{{ number_format($total_omset, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    @endisset
                @endforeach
            </tbody>
        </table>
    @else
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Tarif</th>
                    <th>STT</th>
                    <th>Berat</th>
                    <th>Volume</th>
                    <th>Koli</th>
                    <th>Omset</th>
                    <th>Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $stt     = 0;
                    $volume  = 0;
                    $koli    = 0;
                    $berat   = 0;
                    $omset   = 0;
                    $rata    = 0;
                @endphp
                @foreach ($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->c_tarif }}</td>
                        <td>
                            @php
                                switch ($value->c_tarif) {
                                    case '1':
                                        echo 'BERAT';
                                        break;
                                    case '2':
                                        echo 'VOLUME';
                                        break;
                                    case '3':
                                        echo 'KUBIK';
                                        break;
                                    case '4':
                                        echo 'BORONGAN';
                                        break;
                                    default:
                                        echo '-';
                                        break;
                                }
                            @endphp
                        </td>
                        <td>{{ $value->total_stt }}</td>
                        <td>{{ $value->total_berat }}</td>
                        <td>{{ $value->total_volume }}</td>
                        <td>{{ $value->total_koli }}</td>
                        <td class="text-right">{{ number_format($value->total_omset, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($value->rata_rata_omset, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $stt     += $value->total_stt;
                        $volume  += $value->total_volume;
                        $koli    += $value->total_koli;
                        $berat   += $value->total_berat;
                        $omset   += $value->total_omset;
                        $rata    += $value->rata_rata_omset;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3">Total</td>
                    <td>{{ $stt }}</td>
                    <td>{{ $berat }}</td>
                    <td>{{ $volume }}</td>
                    <td>{{ $koli }}</td>
                    <td class="text-right">{{ number_format($omset, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($rata, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</div>
@endsection