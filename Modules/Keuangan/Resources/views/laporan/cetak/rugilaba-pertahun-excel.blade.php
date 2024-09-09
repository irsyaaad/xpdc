<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rugilaba Pertahun</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=rugilaba-pertahun.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="5" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="5" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="5" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
<br><br>
</div>
<div>
    @php
        $total_pendapatan = [0,0,0,0,0,0,0,0,0,0,0,0,0];
        $total_rugilaba   = 0;
        $total            = 0;
    @endphp
    <table width="100%" style="border-collapse: collapse;">
        <thead>
            <th>Nama Account</th>
            @php
                $bulan = array (
                1 =>   'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            );
            @endphp
            @foreach ($bulan as $item)
                <th>{{$item}}</th>
            @endforeach
        </thead>
        @foreach($data1 as $key => $value)
            @if($value->id_ac == 4)
                <tr><td>{{$value->nama}}</td></tr>
                @if(isset($data2[$value->id_ac]))
                    @foreach($data2[$value->id_ac] as $key2 => $value2)
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                    <td style="padding-left:50px">{{$value3->nama}}</td>
                                    @for ($i =1; $i<=12; $i++)
                                        @if (isset($data[$i]))
                                            @if(isset($data[$i][$value3->id_ac]))
                                                <td class="text-right"> {{ number_format($data[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                        $total_pendapatan[$i]-=$data[$i][$value3->id_ac];
                                                    }else{
                                                        $total_pendapatan[$i]+=$data[$i][$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
                                        @endif
                                    @endfor
                                </tr>
                                @php

                                @endphp
                            @endforeach
                        @endif
                    @endforeach
                    <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                        <td class="text-center">Sub Total PENDAPATAN</td>
                        @for ($i=1; $i<=12; $i++)
                            <td class="text-right"> {{ number_format($total_pendapatan[$i], 0, ',', '.') }}</td>
                        @endfor
                    </tr>
                @endif
            @endif
        @endforeach

        @foreach($data1 as $key => $value)
            @if($value->id_ac == 5)
                <tr><td>{{$value->nama}}</td></tr>
                @if(isset($data2[$value->id_ac]))
                    @foreach($data2[$value->id_ac] as $key2 => $value2)
                        @php $total = [0,0,0,0,0,0,0,0,0,0,0,0,0]; @endphp
                        <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                    <td style="padding-left:50px">{{$value3->nama}}</td>
                                    @for ($i=1; $i<=12; $i++)
                                        @isset($data[$i])
                                            @if(isset($data[$i][$value3->id_ac]))
                                                <td class="text-right"> {{ number_format($data[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    if ($value3->tipe == "K") {
                                                        $total[$i] += $data[$i][$value3->id_ac];
                                                    } else {
                                                        $total[$i] -= $data[$i][$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
                                        @endisset
                                    @endfor
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                        <td class="text-center">Sub Total {{$value2->nama}}</td>
                        @for ($i=1; $i<=12; $i++)
                            <td class="text-right"> {{ number_format($total[$i], 0, ',', '.') }}</td>
                            @php $total_pendapatan[$i] += $total[$i] @endphp
                        @endfor


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
                        @for ($i=1; $i<=12; $i++)
                            <td class="text-right"> {{ number_format($total_pendapatan[$i], 0, ',', '.') }}</td>
                        @endfor
                        </tr>
                    @endforeach

                @endif
            @endif
        @endforeach
    </table>
</div>
</body>
</html>