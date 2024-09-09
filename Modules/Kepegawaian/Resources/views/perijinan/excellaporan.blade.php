<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perijinan {{ $perusahaan->nm_perush." - ".date("Y-m-d") }}</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=LaporanPerijinan".$perusahaan->nm_perush." - ".date("Y-m-d").".xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="8" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="8" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="8" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
<table class="table" width="100%">
    <thead style="background-color: grey; color : #ffff">
        <tr>
            <th>No</th>
            <th>Nama Karyawan </th>
            <th>Jenis Perijinan</th>
            <th>Ijin Tanggal</th>
            <th>Sampai Tanggal</th>
            <th>Lama Hari Ijin</th>
            <th>Keterangan</th>
            <th>Status konfirmasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>@if(isset($value->nm_karyawan)){{ strtoupper($value->nm_karyawan) }}@endif</td>
            <td>@if(isset($value->nm_jenis)){{ strtoupper($value->nm_jenis) }}@endif</td>
            <td>@if(isset($value->dr_tgl)){{ daydate($value->dr_tgl).", ".dateindo($value->dr_tgl) }}@endif</td>
            <td>@if(isset($value->sp_tgl)){{ daydate($value->sp_tgl).", ".dateindo($value->sp_tgl) }}@endif</td>
            <td>@if(isset($value->dr_tgl) and isset($value->sp_tgl))
                @php
                $tgl1 = new DateTime(date($value->dr_tgl));
                $tgl2 = new DateTime(date($value->sp_tgl));
                $perbedaan = $tgl2->diff($tgl1)->format("%a");
                @endphp
                {{$perbedaan+1}}@endif</td>            
                <td>@if(isset($value->keterangan)){{ strtoupper($value->keterangan) }}@endif</td>
                <td>
                    @if($value->is_konfirmasi==1)
                    <i class="fa fa-check" style="color: green"></i>
                    @else
                    <i class="fa fa-times" style="color: red"></i>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>