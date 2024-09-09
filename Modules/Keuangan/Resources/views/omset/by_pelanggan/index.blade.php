@extends('template.document2')

@section('data')
    @include('filter.filter-' . Request::segment(1))
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
    <div class="table-responsive" style="display: block;overflow-x: auto; white-space: nowrap; margin-top:15px">
        <table class="table table-responsive table-sm table-bordered" width="100%">
            <thead style="background-color: grey; color : #ffff">
                @php
                    $arrNamaBulan = [
                        '1' => 'Januari',
                        '2' => 'Februari',
                        '3' => 'Maret',
                        '4' => 'April',
                        '5' => 'Mei',
                        '6' => 'Juni',
                        '7' => 'Juli',
                        '8' => 'Agustus',
                        '9' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ];
                @endphp
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    @foreach ($arrNamaBulan as $key => $item)
                        <th>{{ $item }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($group as $key => $value)
                    <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                        <td colspan="15">{{ $value->nm_group }}</td>
                    </tr>
                    @isset($pelanggan[$value->id_plgn_group])
                        @php
                            for ($i = 1; $i <= 12; $i++) {
                                $total_by_pelanggan[$i] = 0;
                            }
                        @endphp
                        @foreach ($pelanggan[$value->id_plgn_group] as $key2 => $value2)
                            <tr>
                                <td>{{ $key2 }}</td>
                                <td>{{ $value2 }}</td>
                                @php
                                    $totalOmsetPelanggan = 0;
                                @endphp
                                @foreach ($arrNamaBulan as $key3 => $value3)
                                    @php
                                        $omsetPelanggan = isset($omset[$key2][$key3]) ? $omset[$key2][$key3] : 0;
                                        $totalOmsetPelanggan += $omsetPelanggan;
                                        $total_by_pelanggan[$key3] += $omsetPelanggan;
                                    @endphp
                                    <td class="text-right">{{ toNumber($omsetPelanggan) }}</td>
                                @endforeach
                                <td class="text-right tr-bold" style="background-color: rgb(221, 218, 218)">
                                    {{ toNumber($totalOmsetPelanggan) }}
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                    <tr style="background-color: rgb(231, 231, 231)" class="tr-bold">
                        <td colspan="2" class="text-center">Total</td>
                        @foreach ($total_by_pelanggan as $item)
                            <td>{{ toNumber($item) }}</td>
                        @endforeach
                        <td>{{ toNumber(array_sum($total_by_pelanggan)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
