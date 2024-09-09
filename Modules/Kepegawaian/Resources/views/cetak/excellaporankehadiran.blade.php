<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Excel Laporan Kehadiran</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=LaporanKehadiran".date("md").".xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="33" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="33" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="33" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
@php
$days = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
@endphp
<br><br>
<table class="table table-bordered table-sm" width="100%" id="mytable" style="margin-top: 20px">
    <thead >
        <tr rowspan="2" style="border: 1px solid black;">
            <th style="border: 1px solid black;">Nama Karyawan</th>
            <th colspan="{{ $days }}" class="text-center" style="border: 1px solid black;">{{$bulan}} - {{$tahun}}</th>
            <th rowspan="2" class="text-center" style="border: 1px solid black;">Total</th>
        </tr>
        <tr>
            <th></th>
            @for($i = 1; $i<=$days; $i++)
            <th class="text-center" style="border: 1px solid black;">{{$i}}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($karyawan as $key => $value)
        <tr>
            @php
            $total = 0;
            @endphp
            <td style="border: 1px solid black;">{{$value->nm_karyawan}}</td>
            @for($i = 1; $i<=$days; $i++)
            @if(isset($day[$value->id_karyawan][$i]))
            @php
            $total += 1;
            @endphp
            <td style="color:green;border: 1px solid black;">✓</td>
            @else
            <td style="border: 1px solid black;">
                
            </td>
            @endif
            @endfor
            <td class="text-center" style="border: 1px solid black;">
                {{ $total }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>