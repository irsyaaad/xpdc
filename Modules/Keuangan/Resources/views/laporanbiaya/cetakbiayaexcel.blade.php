@if(Request::segment(1) == "biayabydm")
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=BiayaByDM.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="10" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="10" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="10" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
    <table class="table table-bordered table-sm" style=" margin-top:10px; font-size:12px;">
        <thead class="text-center">
            <th>No</th>
            <th>No DM</th>
            <th>Tgl Berangkat</th>
            <th>Cabang Tujuan</th>
            <th>Nama Kapal</th>
            <th>Nama Sopir</th>
            <th>No PLAT</th>
            <th>Biaya</th>
            <th>Bayar</th>        
            <th>Tgl DM Dibuat</th>
            <th>ID User</th>
        </thead>
        <tbody>
        @php
            $total_biaya = 0;
            $total_bayar = 0;
        @endphp
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->id_dm)){{$value->id_dm}}@endif</td>
            <td>@if(isset($value->tgl_berangkat)){{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}@endif</td>
            <td>@if(isset($value->nm_perush)){{$value->nm_perush}}@endif</td>
            <td>@if(isset($value->nm_kapal_perush)){{$value->nm_kapal_perush}}@endif</td>
            <td>@if(isset($value->nm_sopir)){{$value->nm_sopir}}@endif</td>
            <td>@if(isset($value->no_plat)){{$value->no_plat}}@endif</td>
            <td>@if(isset($value->biaya))Rp. {{ number_format($value->biaya, 0, ',', '.') }}@endif</td>
            <td>@if(isset($value->bayar))Rp. {{ number_format($value->bayar, 0, ',', '.') }}@endif</td>
            <td>@if(isset($value->created_at)){{$value->created_at}}@endif</td>
            <td></td>
        </tr>
        @php
            $total_biaya += $value->biaya;
            $total_bayar += $value->bayar;
        @endphp
        @endforeach
        <tr>
            <td colspan=7 class="text-center">TOTAL</td>
            <td>Rp. {{ number_format($total_biaya, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($total_bayar, 0, ',', '.') }}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
    </table>
</body>
</html>
@endif
@if(Request::segment(1) == "omsetvsbiaya")
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=OmsetVsBiaya.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="10" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="10" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="10" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
    <table class="table table-bordered table-sm" style=" margin-top:10px; font-size:12px;">
        <thead class="text-center">
            <th>No</th>
            <th>No DM</th>
            <th>Tgl Berangkat</th>
            <th>Nama Sopir</th>
            <th>No PLAT</th>
            <th>Proy (Biaya)</th>
            <th>Omset</th>        
            <th>Proy (Laba)</th>
        </thead>
        <tbody>
            @php
                $total_biaya = 0;
                $total_omset = 0;
                $total_laba = 0;
            @endphp
            @foreach($data as $key => $value)
            <tr>
                <td>{{$key+1}}</td>
                <td>@if(isset($value->id_dm)){{$value->id_dm}}@endif</td>
                <td>@if(isset($value->tgl_berangkat)){{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}@endif</td>
                <td>@if(isset($value->nm_sopir)){{ strtoupper($value->nm_sopir) }}@endif</td>
                <td>@if(isset($value->no_plat)){{ strtoupper($value->no_plat) }}@endif</td>
                <td>@if(isset($value->c_pro))Rp. {{ number_format($value->c_pro, 0, ',', '.') }}@endif</td>
                <td>@if(isset($value->c_total))Rp. {{ number_format($value->c_total, 0, ',', '.') }}@endif</td>
                <td>@if(isset($value->laba))Rp. {{ number_format($value->laba, 0, ',', '.') }}@endif</td>
            </tr>
            @php
                $total_biaya += $value->c_pro;
                $total_omset += $value->c_total;
                $total_laba += $value->laba;
            @endphp
            @endforeach
            <tr >
                <td class="text-center" colspan=5>Total</td>
                <td>Rp. {{ number_format($total_biaya, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($total_omset, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($total_laba, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
@endif