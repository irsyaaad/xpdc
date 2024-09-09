<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANALISA PELANGGAN</title>
    <style>
        th {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size: 12px;
        }

        td {
            font-size: 11px;
        }
    </style>
</head>
<?php
header('Content-type: application/vnd-ms-excel');
header('Content-Disposition: attachment; filename=analisa-pelanggan.xls');
?>

<body class="container">
    <div class="container" style=" margin-top:10px;">
        <table>
            <tr>
                <th colspan="16" class="text-center">{{ strtoupper($perusahaan->nm_perush) }}</th>
            </tr>
            <tr>
                <th colspan="16" class="text-center">LAPORAN ANALISA PELANGGAN</th>
            </tr>
            <tr>
                <th colspan="16" class="text-center">Periode : {{ dateindo($filter['dr_tgl']) }} s/d
                    {{ dateindo($filter['sp_tgl']) }}</th>
            </tr>
        </table>
        <br><br>
    </div>
    <div class="container">

        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Marketing</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">STT</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Koli</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Omset</th>
                    <th colspan="3" class="text-center">Pelanggan</th>
                    <th colspan="8" class="text-center">Jenis</th>
                </tr>
                <tr>
                    <th class="text-center">Aktif</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center"> % </th>
                    <th class="text-center">Baru</th>
                    <th class="text-center"> % </th>
                    <th class="text-center"> Omset Baru </th>
                    <th class="text-center"> % </th>
                    <th class="text-center">Reorder</th>
                    <th class="text-center"> % </th>
                    <th class="text-center"> Omset Reorder </th>
                    <th class="text-center"> % </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_stt = 0;
                    $total_koli = 0;
                    $total_omset = 0;
                    $total_jumlah = 0;
                    $total_aktif = 0;
                    $total_baru = 0;
                    $total_reorder = 0;
                    $total_omset_baru = 0;
                    $total_omset_reorder = 0;
                @endphp
                @foreach ($data as $key => $value)
                    <tr>
                        <td class="text-center">{{ $key += 1 }}</td>
                        <td class="text-center">
                            {{ !empty($value->nm_marketing) ? strtoupper($value->nm_marketing) : 'DATANG SENDIRI' }}
                        </td>
                        <td class="text-center">{{ $value->total_stt }}</td>
                        <td class="text-center">{{ $value->koli }}</td>
                        <td class="text-right">{{ $value->omset }}</td>
                        <td class="text-center">{{ $value->aktif }}</td>
                        <td class="text-center">{{ $value->jumlah }}</td>
                        <td class="text-center">
                            @if (($value->aktif and $value->jumlah) > 0)
                                {{ round(($value->jumlah / $value->aktif) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-center">{{ $value->baru }}</td>
                        <td class="text-center">
                            @if (($value->baru and $value->jumlah) > 0)
                                {{ round(($value->baru / $value->jumlah) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-right">{{ $value->omset_baru }}</td>
                        <td class="text-center">
                            @if (($value->omset and $value->omset_baru) > 0)
                                {{ round(($value->omset_baru / $value->omset) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-center">{{ $value->reorder }} </td>
                        <td class="text-center">
                            @if (($value->reorder and $value->aktif) > 0)
                                {{ round(($value->reorder / $value->jumlah) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-right">{{ $value->omset_reorder }} </td>
                        <td class="text-center">
                            @if (($value->omset_reorder and $value->omset) > 0)
                                {{ round(($value->omset_reorder / $value->omset) * 100, 2) }} %
                            @endif
                        </td>
                    </tr>
                    @php
                        $total_stt += $value->total_stt;
                        $total_koli += $value->koli;
                        $total_omset += $value->omset;
                        $total_jumlah += $value->jumlah;
                        $total_aktif += $value->aktif;
                        $total_baru += $value->baru;
                        $total_reorder += $value->reorder;
                        $total_omset_baru += $value->omset_baru;
                        $total_omset_reorder += $value->omset_reorder;
                    @endphp
                @endforeach
                <tr>
                    <th colspan = "2" class="text-center">Total Pelanggan Unik : {{ $pelanggan_unik['unik'] }}, dari
                        {{ $pelanggan_unik['unik_aktif'] }} aktif</th>
                    <th class="text-center">{{ $total_stt }}</th>
                    <th class="text-center">{{ $total_koli }}</th>
                    <th class="text-right">{{ $total_omset }}</th>
                    <th class="text-center">{{ $total_aktif }}</th>
                    <th class="text-center">{{ $total_jumlah }}</th>
                    <th></th>
                    <th class="text-center">{{ $total_baru }}</th>
                    <th class="text-center">
                        @if (($total_baru and $total_jumlah) > 0)
                            {{ round(($total_baru / $total_jumlah) * 100, 2) }} %
                        @endif
                    </th>
                    <th class="text-right">{{ $total_omset_baru }}</th>
                    <th class="text-center">
                        @if (($total_omset_baru and $total_omset) > 0)
                            {{ round(($total_omset_baru / $total_omset) * 100, 2) }} %
                        @endif
                    </th>
                    <th class="text-center">{{ $total_reorder }}</th>
                    <th class="text-center">
                        @if (($total_reorder and $total_jumlah) > 0)
                            {{ round(($total_reorder / $total_jumlah) * 100, 2) }} %
                        @endif
                    </th>
                    <th class="text-right">{{ $total_omset_reorder }}</th>
                    <th>
                        @if (($total_omset_reorder and $total_omset) > 0)
                            {{ round(($total_omset_reorder / $total_omset) * 100, 2) }} %
                        @endif
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>
