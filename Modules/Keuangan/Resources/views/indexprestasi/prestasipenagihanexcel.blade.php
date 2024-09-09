@if(Request::segment(1) == "lamaharistt")
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
	header("Content-Disposition: attachment; filename=lamaharistt.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="14" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="14" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="14" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
<table class="table table-bordered table-sm" id="html_table" width="100%">
    <thead class="text-center">
       <th>#</th>
       <th>No STT</th>
       <th>Smp</th>
       <th>STT Kirim</th>
       <th>Krm</th>
       <th>STT Kembali</th>
       <th>Kmb</th>
       <th>Tgl Invoice</th>
       <th>No Invoice</th>
       <th>Inv</th>
       <th>Tgl Bayar</th> 
       <th>Total</th> 
       <th>Bayar</th> 
       <th>Piutang</th>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        <tr>
            <td colspan=2></td>
            <td colspan=2>No DM</td>
            <td colspan=3>@if(isset($value->id_dm)){{$value->id_dm}}@endif</td>
            <td colspan=2>Nama Sopir</td>
            <td colspan=3>@if(isset($value->nm_sopir)){{$value->nm_sopir}}@endif</td>
        </tr>
        <tr>
            <td colspan=2></td>
            <td colspan=2>Cab Tujuan</td>
            <td colspan=3>@if(isset($value->nm_perush)){{$value->nm_perush}}@endif</td>
            <td colspan=2>Nama Kapal</td>
            <td colspan=3>@if(isset($value->nm_kapal_perush)){{$value->nm_kapal_perush}}@endif</td>
        </tr>
        <tr>
            <td colspan=2></td>
            <td colspan=2>Tgl Berangkat</td>
            <td colspan=3>@if(isset($value->tgl_berangkat)){{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}@endif</td>
        </tr>
        <tr>
            <td colspan=2></td>
            <td colspan=2>Tgl Tiba</td>
            <td colspan=3>@if(isset($value->nm_perush)){{$value->nm_perush}}@endif</td>
        </tr>
            @php
            $no = 0;
            @endphp
            @foreach($stt[$value->id_dm] as $key2 => $value2)
                <tr>
                    <td>{{$no+=1}}</td>
                    <td>@if(isset($value2->id_stt)){{$value2->id_stt}}@endif</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>@if(isset($value2->id_order_pay)){{$value2->id_order_pay}}@endif</td>
                    <td></td>
                    <td>@if(isset($value2->tgl)){{ dateindo($value2->tgl) }}@endif</td>
                    <td>@if(isset($value2->c_total))Rp. {{ number_format($value2->c_total, 0, ',', '.') }}@endif</td>                    
                    <td>@if(isset($value2->n_bayar))Rp. {{ number_format($value2->n_bayar, 0, ',', '.') }}@endif</td>                    
                    <td>@if(isset($value2->n_bayar))Rp. {{ number_format(($value2->c_total)-($value2->n_bayar), 0, ',', '.') }}@endif</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
</body>
</html>
@endif
@if(Request::segment(1) == "lamaharisttbygroup")
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
	header("Content-Disposition: attachment; filename=lamaharisttbygroup.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="14" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="14" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="14" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
<table class="table table-sm table-bordered" id="html_table" width="100%">
    <thead class="text-center" >
       <tr>
            <th class="text-center" rowspan=2>BL</th>
            <th class="text-center" rowspan=2>TH</th>
            <th class="text-center" colspan=3 class="text-center">Jumlah</th>
            <th class="text-center" colspan=3 class="text-center">Rata - Rata Hari</th>
            <th class="text-center" rowspan=2>Omset</th>
            <th class="text-center" rowspan=2>Bayar</th>
            <th class="text-center" rowspan=2>Piutang</th>
            <th class="text-center" rowspan=2>Bayar %</th>
       </tr>
       <tr>
            <th class="text-center">DM</th>
            <th class="text-center">STT</th>
            <th class="text-center">Blm Bayar</th>
            <th class="text-center">Kemb</th>
            <th class="text-center">Inv</th>
            <th class="text-center">Bayar</th>
       </tr>
    </thead>
    <tbody>
        @php
            $total_dm = 0;
            $total_stt = 0;
            $total_omset = 0;
            $total_bayar = 0;
            $blm_bayar = 0;
            $total_piutang = 0;
        @endphp
        @foreach($data as $key => $value)
            <tr class="text-center">
                <td>@if(isset($value->month)){{$value->month}}@endif</td>
                <td></td>
                <td>@if(isset($value->total_dm)){{$value->total_dm}}@endif</td>
                <td>@if(isset($value->total_stt)){{$value->total_stt}}@endif</td>
                <td>@if(isset($value->stt_bayar)){{$value->total_stt-$value->stt_bayar}}@endif</td>
                <td></td>
                <td></td>
                <td></td>
                <td>@if(isset($value->total_pend))Rp. {{ number_format($value->total_pend, 0, ',', '.') }}@endif</td>                
                <td>@if(isset($value->total_bayar))Rp. {{ number_format($value->total_bayar, 0, ',', '.') }}@endif</td>                
                <td>@if(isset($value->total_bayar))Rp. {{ number_format($value->total_pend-$value->total_bayar, 0, ',', '.') }}@endif</td>
                <td>@if(isset($value->total_bayar)){{round($value->total_bayar/$value->total_pend*100,2)}}@endif</td>
            </tr>
            @php
            $total_dm += $value->total_dm;
            $total_stt += $value->total_stt;
            $total_omset += $value->total_pend;
            $total_bayar += $value->total_bayar;
            $blm_bayar += ($value->total_stt-$value->stt_bayar);
            $total_piutang += ($value->total_pend-$value->total_bayar);
        @endphp
        @endforeach
            <tr class="text-center" >
                <td colspan=2 >Total</td>
                <td>{{$total_dm}}</td>
                <td>{{$total_stt}}</td>
                <td>{{$blm_bayar}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>Rp. {{ number_format($total_omset, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($total_bayar, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($total_piutang, 0, ',', '.') }}</td>
                <td></td>
            </tr>
    </tbody>
</table>
</body>
</html>
@endif