<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <title>Cetak Rugilaba Proyeksi | Lsj Express Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style type="text/css">
        html { margin: 15px}
        body{
            font-family: sans-serif !important;
        }
        .page-break {
            page-break-after: always;
        }
        .table1 {
            font-size: 8pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }
        .table1 tr th{
            background: grey;
            color: #fff;
            font-weight: bold;
        }
        .table1, th, td {
            text-align: left;
        }
        .text-center{
            text-align: center;
        }
        .text-title{
            margin-left: 10pt;
        }

        .heading{
            text-align: center;
            padding-top: 10px;
            line-height: 15px;
        }

        .table2{

            margin-top: -5px;
            font-size: 8pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }

        .text-body{
            font-size: 7pt !important;
        }

        .stt{
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-top: -40px;
            text-decoration: underline;
        }

        .isi{
            padding : 20px;
        }

        .table-isi{
            font-size: 12px;
        }

        .isi-content{
            border-bottom : 1px solid black;
        }

        .footer{
            padding : 20px;
        }

        .table-footer{
            font-size: 12px;
            text-align:center;
        }

        th {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size : 12px;
        }

        td {
            font-size : 10px;
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 5px;
            padding-right: 5px;
        }
        .t {
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 10px;
            font-size : 10px;
        }

        .head{
            font-size: 18px;
            font-weight: bold;
            height: 50px;
        }
        .text-center{
            text-align : center;
        }
        .text-right{
            text-align : right;
        }
        .heading{
            text-align: center;
            font-size: 14px;
        }
        .kepada{
            margin-top: -40px;
            line-height: 10px;
        }
        .kepada td{
            font-size : 12px;
        }
        .hr{
            border-top: 1px solid red;
            margin-top : 10px;
        }
        .headnote{
            border-top: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
            text-align: left;
            font-size : 10px;
        }
        .headnote td {
            padding-left : 10px;
        }
        .note{
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
            text-align: left;
            font-size : 10px;
            height : 50px;
        }
        .hrhead{
            border: 1px solid black;
        }
        .penutup td{
            font-size: 12px;
        }
        .setelah-garis p {
            font-size: 12px;
        }
        .tr-bold{
            font-weight: bold !important;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $total_pendapatan = 0;
            $total_rugilaba   = 0;

            $total_pendapatan_s = 0;
            $total_rugilaba_s   = 0;

            $total_pendapatan_p = 0;
            $total_rugilaba_p   = 0;

            $total_pencapaian   = 0;
            $total_rugilaba_pen = 0;

            $total_pertumbuhan  = 0;
            $total_rugilaba_per = 0;

        @endphp
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th></th>
                    <th colspan="2" class="text-center">Realisasi Sebelum</th>
                    <th colspan="2" class="text-center">Proyeksi</th>
                    <th colspan="2" class="text-center">Realisasi</th>
                    <th colspan="4" class="text-center">Rasio</th>
                </tr>
                <tr>
                    <th></th>
                    <th colspan="2">
                        @php
                            $dr_tgl = date('Y-m-d', strtotime('-1 year', strtotime($filter['dr_tgl'])));
                            $sp_tgl = date(
                                'Y-m-t',
                                strtotime('-1 year', strtotime(date('Y-m-01', strtotime($filter['sp_tgl'])))),
                            );
                            echo dateindo($dr_tgl) . ' s/d ' . dateindo($sp_tgl);
                        @endphp
                    </th>
        
                    <th colspan="4" class="text-center">{{ dateindo($filter['dr_tgl']) }} s/d
                        {{ dateindo($filter['sp_tgl']) }}</th>
                    <th colspan="2" class="text-center">Pencapaian</th>
                    <th colspan="2" class="text-center">Pertumbuhan</th>
                </tr>
                <tr>
                    <th></th>
                    <th class="text-center">A</th>
                    <th class="text-center">%</th>
                    <th class="text-center">B</th>
                    <th class="text-center">%</th>
                    <th class="text-center">C</th>
                    <th class="text-center">%</th>
                    <th class="text-center">C - B</th>
                    <th>%</th>
                    <th class="text-center">C - A</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data1 as $key => $value)
                    @if ($value->id_ac == 4)
                        <tr>
                            <td>{{ $value->nama }}</td>
                        </tr>
                        @if (isset($data2[$value->id_ac]))
                            @foreach ($data2[$value->id_ac] as $key2 => $value2)
                                @if (isset($data3[$value2->id_ac]))
                                    @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px">{{ $value3->nama }}</td>
        
                                            {{-- Saldo sebelum --}}
                                            @if (isset($sebelum[$value3->id_ac]))
                                                <td class="text-right">{{ number_format($sebelum[$value3->id_ac], 0, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ ($sebelum[$value3->id_ac] && $total_omset_sebelum) != 0 ? round(($sebelum[$value3->id_ac] / $total_omset_sebelum) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pendapatan_s -= $sebelum[$value3->id_ac];
                                                    } else {
                                                        $total_pendapatan_s += $sebelum[$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
        
                                            {{-- Proyeksi --}}
                                            @if (isset($proyeksi[$value3->id_ac]))
                                                <td class="text-right">
                                                    {{ number_format($proyeksi[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ ($proyeksi[$value3->id_ac] && $pendapatan_proyeksi) != 0 ? round(($proyeksi[$value3->id_ac] / $pendapatan_proyeksi) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pendapatan_p -= $proyeksi[$value3->id_ac];
                                                    } else {
                                                        $total_pendapatan_p += $proyeksi[$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
        
                                            {{-- Realisasi --}}
                                            @if (isset($nilai[$value3->id_ac]))
                                                <td class="text-right">{{ number_format($sebelum[$value3->id_ac], 0, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ ($nilai[$value3->id_ac] && $total_omset) != 0 ? round(($nilai[$value3->id_ac] / $total_omset) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pendapatan -= $nilai[$value3->id_ac];
                                                    } else {
                                                        $total_pendapatan += $nilai[$value3->id_ac];
                                                    }
        
                                                @endphp
                                            @endif
        
                                            {{-- Pencapaian --}}
        
                                            @if (isset($nilai[$value3->id_ac]) and isset($proyeksi[$value3->id_ac]))
                                                <td class="text-right">
                                                    {{ number_format($nilai[$value3->id_ac] - $proyeksi[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ ($nilai[$value3->id_ac] && $proyeksi[$value3->id_ac]) != 0 ? round(($nilai[$value3->id_ac] / $proyeksi[$value3->id_ac]) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pencapaian -= $nilai[$value3->id_ac] - $proyeksi[$value3->id_ac];
                                                    } else {
                                                        $total_pencapaian += $nilai[$value3->id_ac] - $proyeksi[$value3->id_ac];
                                                    }
        
                                                @endphp
                                            @else
                                                <td>0</td>
                                            @endif
        
                                            {{-- Pertumbuhan --}}
        
                                            @if (isset($nilai[$value3->id_ac]) and isset($sebelum[$value3->id_ac]))
                                                <td class="text-right">
                                                    {{ number_format($nilai[$value3->id_ac] - $sebelum[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ ($nilai[$value3->id_ac] && $sebelum[$value3->id_ac]) != 0 ? round((($nilai[$value3->id_ac] - $sebelum[$value3->id_ac]) / $sebelum[$value3->id_ac]) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pertumbuhan -= $nilai[$value3->id_ac] - $sebelum[$value3->id_ac];
                                                    } else {
                                                        $total_pertumbuhan += $nilai[$value3->id_ac] - $sebelum[$value3->id_ac];
                                                    }
        
                                                @endphp
                                            @else
                                                <td>0</td>
                                            @endif
        
        
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                <td class="text-center">Sub Total PENDAPATAN</td>
                                <td class="text-right"> {{ number_format($total_pendapatan_s, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ ($total_pendapatan_s && $total_omset_sebelum) != 0 ? round(($total_pendapatan_s / $total_omset_sebelum) * 100, 2) : 0 }}
                                </td>
                                <td class="text-right"> {{ number_format($total_pendapatan_p, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ ($total_pendapatan_p && $pendapatan_proyeksi) != 0 ? round(($total_pendapatan_p / $pendapatan_proyeksi) * 100, 2) : 0 }}
                                </td>
                                <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ ($total_pendapatan && $total_omset) != 0 ? round(($total_pendapatan / $total_omset) * 100, 2) : 0 }}
                                </td>
                                <td class="text-right"> {{ number_format($total_pencapaian, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ ($total_pencapaian && $total_omset) != 0 ? round(($total_pencapaian / $total_omset) * 100, 2) : 0 }}
                                </td>
                                <td class="text-right">{{ number_format($total_pertumbuhan, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ ($total_pertumbuhan && $total_omset) != 0 ? round(($total_pertumbuhan / $total_omset) * 100, 2) : 0 }}
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
        
                @foreach ($data1 as $key => $value)
                    @if ($value->id_ac == 5)
                        <tr>
                            <td>{{ $value->nama }}</td>
                        </tr>
                        @if (isset($data2[$value->id_ac]))
                            @foreach ($data2[$value->id_ac] as $key2 => $value2)
                                @php
                                    $total_sebelum = 0;
                                    $total_proyeksi = 0;
                                    $total = 0;
                                    $total_pen = 0;
                                    $total_per = 0;
                                @endphp
                                <td style="padding-left:10px">
                                    <p>{{ $value2->nama }}</p>
                                </td>
                                @if (isset($data3[$value2->id_ac]))
                                    @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px">{{ $value3->nama }}</td>
        
                                            {{-- Realisasi sebelum --}}
        
                                            @if (isset($sebelum[$value3->id_ac]))
                                                <td class="text-right">{{ number_format($sebelum[$value3->id_ac], 0, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ ($sebelum[$value3->id_ac] && $total_omset_sebelum) != 0 ? round(($sebelum[$value3->id_ac] / $total_omset_sebelum) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    if ($value3->tipe == 'K') {
                                                        $total_sebelum += $sebelum[$value3->id_ac];
                                                    } else {
                                                        $total_sebelum -= $sebelum[$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
        
                                            {{-- Proyeksi --}}
        
                                            @if (isset($proyeksi[$value3->id_ac]))
                                                <td class="text-right">
                                                    {{ number_format($proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ ($proyeksi[$value3->id_ac] && $pendapatan_proyeksi) != 0 ? round(($proyeksi[$value3->id_ac] / $pendapatan_proyeksi) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    $total_proyeksi += $proyeksi[$value3->id_ac];
                                                @endphp
                                            @endif
        
                                            {{-- Realisasi --}}
        
                                            @if (isset($nilai[$value3->id_ac]))
                                                <td class="text-right">{{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ $nilai[$value3->id_ac] != 0 && $total_omset != 0 ? round(($nilai[$value3->id_ac] / $total_omset) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    if ($value3->tipe == 'K') {
                                                        $total += $nilai[$value3->id_ac];
                                                    } else {
                                                        $total -= $nilai[$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
        
                                            {{-- Pencapaian --}}
                                            @if (isset($nilai[$value3->id_ac]) and isset($proyeksi[$value3->id_ac]))
                                                <td class="text-right">
                                                    {{ number_format($nilai[$value3->id_ac] - $proyeksi[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ ($nilai[$value3->id_ac] && $proyeksi[$value3->id_ac]) != 0 ? round(($nilai[$value3->id_ac] / $proyeksi[$value3->id_ac]) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    $total_pen += $nilai[$value3->id_ac] - $proyeksi[$value3->id_ac];
                                                @endphp
                                            @endif
        
                                            {{-- Pertumbuhan --}}
                                            @if (isset($nilai[$value3->id_ac]) and isset($sebelum[$value3->id_ac]))
                                                <td class="text-right">
                                                    {{ number_format($nilai[$value3->id_ac] - $sebelum[$value3->id_ac], 0, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ ($nilai[$value3->id_ac] && $sebelum[$value3->id_ac]) != 0 ? round((($nilai[$value3->id_ac] - $sebelum[$value3->id_ac]) / $sebelum[$value3->id_ac]) * 100, 2) : 0 }}
                                                </td>
                                                @php
                                                    $total_per += $nilai[$value3->id_ac] - $sebelum[$value3->id_ac];
                                                @endphp
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="text-center">Sub Total {{ $value2->nama }}</td>
                                    <td class="text-right"> {{ number_format($total_sebelum, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total_sebelum && $total_omset_sebelum) != 0 ? round(($total_sebelum / $total_omset_sebelum) * 100, 2) : 0 }}
                                    </td>
                                    <td class="text-right"> {{ number_format($total_proyeksi, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total_proyeksi && $pendapatan_proyeksi) != 0 ? round(($total_proyeksi / $pendapatan_proyeksi) * 100, 2) : 0 }}
                                    </td>
                                    <td class="text-right"> {{ number_format($total, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total && $total_omset) != 0 ? round(($total / $total_omset) * 100, 2) : 0 }}
                                    </td>
                                    <td class="text-right"> {{ number_format($total_pen, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total_pen && $total_proyeksi) != 0 ? round(($total_pen / $total_proyeksi) * 100, 2) : 0 }}
                                    </td>
                                    <td class="text-right"> {{ number_format($total_per, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total_sebelum && $total) != 0 ? round(($total - $total_sebelum / $total_sebelum) * 100, 2) : 0 }}
                                    </td>
                                    @php
                                        $total_pendapatan += $total;
                                        $total_pendapatan_s += $total_sebelum;
                                        $total_pendapatan_p -= $total_proyeksi;
                                        $total_pencapaian -= $total_pen;
                                        $total_pertumbuhan -= $total_per;
                                    @endphp
                                </tr>
                                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                    @if ($value2->id_ac == 50)
                                        <td class="text-center"> LABA KOTOR </td>
                                    @elseif ($value2->id_ac == 51)
                                        <td class="text-center"> LABA OPERASIONAL </td>
                                    @elseif ($value2->id_ac == 52)
                                        <td class="text-center"> LABA SETELAH POKOK DAN BUNGA </td>
                                    @elseif ($value2->id_ac == 53)
                                        <td class="text-center"> LABA SETELAH PENDAPATAN DAN BIAYA LAIN-LAIN </td>
                                    @elseif ($value2->id_ac == 54)
                                        <td class="text-center"> LABA SETELAH PAJAK </td>
                                    @endif
                                    <td class="text-right"> {{ number_format($total_pendapatan_s, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total_pendapatan_s && $total_omset_sebelum) != 0 ? round(($total_pendapatan_s / $total_omset_sebelum) * 100, 2) : 0 }}
                                    </td>
                                    <td class="text-right"> {{ number_format($total_pendapatan_p, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total_pendapatan_p && $pendapatan_proyeksi) != 0 ? round(($total_pendapatan_p / $pendapatan_proyeksi) * 100, 2) : 0 }}
                                    </td>
                                    <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ ($total_pendapatan && $total_omset) != 0 ? round(($total_pendapatan / $total_omset) * 100, 2) : 0 }}
                                    </td>
                                    <td class="text-right"> {{ number_format($total_pencapaian, 0, ',', '.') }}</td>
                                    @if ($total_pendapatan != 0)
                                        <td class="text-right">
                                            {{ ($total_pencapaian && $total_pendapatan) != 0 ? round(($total_pencapaian / $total_pendapatan) * 100, 2) : 0 }}
                                        </td>
                                    @else
                                        <td class="text-right">0 </td>
                                    @endif
                                    <td class="text-right"> {{ number_format($total_pertumbuhan, 0, ',', '.') }}</td>
                                    @if ($total_pendapatan != 0)
                                        <td class="text-right">
                                            {{ ($total_pertumbuhan && $total_pendapatan) != 0 ? round(($total_pertumbuhan / $total_pendapatan) * 100, 2) : 0 }}
                                        </td>
                                    @else
                                        <td class="text-right">0 </td>
                                    @endif
        
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
