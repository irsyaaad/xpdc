<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rugilaba</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=rugilaba.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="5" class="text-center">{{ strtoupper($perusahaan->nm_perush) }}</th>
		</tr>
		<tr>
		<th colspan="5" class="text-center">LAPORAN RUGILABA</th>
		</tr>
		<tr>
		<th colspan="5" class="text-center">Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</th>
		</tr>
	</table>
<br><br>
</div>
<div class="container">
    @php
    $total_pendapatan = 0;
    $total_rugilaba   = 0;
    @endphp
    <table width="100%">
            @foreach ($data1 as $key => $value)
                @if ($value->id_ac == 4)
                    <tr class="tr-bold">
                        <td>{{ $value->nama }}</td>
                    </tr>
                    @if (isset($data2[$value->id_ac]))
                        @foreach ($data2[$value->id_ac] as $key2 => $value2)
                            @if (isset($data3[$value2->id_ac]))
                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                    {{-- @if (isset($nilai[$value3->id_ac]) && $nilai[$value3->id_ac] != 0) --}}
                                        <tr>
                                            <td style="padding-left:50px">{{ $value3->nama }}</a>
                                            </td>
                                            @if (isset($nilai[$value3->id_ac]))
                                                <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pendapatan -= $nilai[$value3->id_ac];
                                                    } else {
                                                        $total_pendapatan += $nilai[$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
                                            <td class="text-center">{{ ($nilai[$value3->id_ac] && ($total_omset)) != 0 ? round(($nilai[$value3->id_ac]/($total_omset)) * 100,2) : 0 }}</td>
                                        </tr>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                        @endforeach
                        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                            <td class="text-center"> TOTAL PENDAPATAN</td>
                            <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                            <td class="text-center">{{ ($total_pendapatan && ($total_omset)) != 0 ? round(($total_pendapatan/($total_omset)) * 100,2) : 0 }} </td>
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
                            @php $total = 0; @endphp
                            <td style="padding-left:10px">
                                <p>{{ $value2->nama }}</p>
                            </td>
                            @if (isset($data3[$value2->id_ac]))
                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                    {{-- @if (isset($nilai[$value3->id_ac]) && $nilai[$value3->id_ac] != 0) --}}
                                    <tr>
                                        <td style="padding-left:50px">{{ $value3->nama }}</td>
                                        @if (isset($nilai[$value3->id_ac]))
                                            <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                            @if ($value3->tipe == "K")
                                                @php
                                                    $total += $nilai[$value3->id_ac];
                                                @endphp
                                            @else
                                                @php
                                                    $total -= $nilai[$value3->id_ac];
                                                @endphp
                                            @endif
                                        @endif
                                        <td class="text-center">{{ ($nilai[$value3->id_ac] && ($total_omset)) != 0 ? (round(($nilai[$value3->id_ac]/($total_omset)) * 100,2)) : 0 }} </td>
                                    </tr>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                            <tr>
                                <td class="text-center"> TOTAL {{ $value2->nama }}</td>
                                <td class="text-right"> {{ number_format($total, 0, ',', '.') }}</td>
                                <td class="text-center">{{ ($total && ($total_omset)) != 0 ? (round(($total/($total_omset)) * 100,2)) : 0}}</td>
                                @php $total_pendapatan+=$total @endphp
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
                                <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ ($total_pendapatan && ($total_omset)) != 0 ? (round(($total_pendapatan/($total_omset)) * 100,2)) : 0 }} </td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            @endforeach

        </table>
    </div>
</div>
</body>
</html>