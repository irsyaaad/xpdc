@if(Request::segment(1) == "neraca")
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
	header("Content-Disposition: attachment; filename=neraca.xls");
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
</div>
<br><br>
    <table class="table table-bordered table-sm" style=" margin-top:10px; font-size:12px;">
        <thead>
            <th>Aktiva</th>
            <th></th>
            <th>Pasiva</th>
        </thead>
        <tbody>
            <tr>
                <td>
                <table class="table table-borderless table-sm ">
                @php $total_aktiva=0; @endphp
                    @foreach($data1 as $key => $value)
                        @if($value->id_ac == 1)
                        <tr><td>{{$value->nama}}</td></tr>
                            @if(isset($data2[$value->id_ac]))
                                @foreach($data2[$value->id_ac] as $key2 => $value2)
                                    <tr>
                                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                                    
                                        @if(isset($data3[$value2->id_ac]))
                                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                <tr>
                                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                                    @if(isset($data4[$value3->id_ac]))
                                                        @php
                                                        $total = 0;
                                                        @endphp
                                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                                            @php
                                                                $total+=$value4->total;
                                                            @endphp
                                                        @endforeach
                                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tr>
                                <tr><td>Sub Total {{$value2->nama}}</td>
                                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                                @php $total_aktiva+=$nilai[$value2->id_ac] @endphp
                                </tr>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </table>
                </td>
                <td></td>
                <td>
                <table class="table table-borderless table-sm ">
                @php $total_pasiva=0; @endphp
                    @foreach($data1 as $key => $value)
                        @if($value->id_ac == 2)
                        <tr><td>{{$value->nama}}</td></tr>
                            @if(isset($data2[$value->id_ac]))
                                @foreach($data2[$value->id_ac] as $key2 => $value2)
                                    <tr>
                                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                                    
                                        @if(isset($data3[$value2->id_ac]))
                                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                <tr>
                                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                                    @if(isset($data4[$value3->id_ac]))
                                                        @php
                                                        $total = 0;
                                                        @endphp
                                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                                            @php
                                                                $total+=$value4->total;
                                                            @endphp
                                                        @endforeach
                                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                                    @endif                                
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tr>
                                <tr><td>Sub Total {{$value2->nama}}</td>
                                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                                @php $total_pasiva+=$nilai[$value2->id_ac] @endphp
                                </tr>
                                <tr><td><p></p></td></tr>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                    <!-- Modal -->
                    @foreach($data1 as $key => $value)
                        @if($value->id_ac == 3)
                        <tr><td>{{$value->nama}}</td></tr>
                            @if(isset($data2[$value->id_ac]))
                                @foreach($data2[$value->id_ac] as $key2 => $value2)
                                    <tr>
                                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                                    
                                        @if(isset($data3[$value2->id_ac]))
                                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                <tr>
                                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                                    @if(isset($data4[$value3->id_ac]))
                                                        @php
                                                        $total = 0;
                                                        @endphp
                                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                                            @php
                                                                $total+=$value4->total;
                                                            @endphp
                                                        @endforeach
                                                        <td>
                                                            @if($value3->id_ac == '302-1')
                                                                Rp. {{ number_format($lababerjalan, 0, ',', '.') }}
                                                            @else
                                                                Rp. {{ number_format($total, 0, ',', '.') }}
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tr>
                                <tr><td>Sub Total {{$value2->nama}}</td>
                                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                                @php $total_pasiva+=$nilai[$value2->id_ac] @endphp
                                </tr>
                                <tr><td><p></p></td></tr>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
            </table>
                </td>
            </tr>
        </tbody>        
    </table>
    <table>
        <tr>
            <td>total Aktiva</td>
            <td style="text-align:center">Rp. {{ number_format($total_aktiva, 0, ',', '.') }}</td>
            <td></td>
            <td>total Pasiva</td>
            @php $total_pasiva+=$lababerjalan; @endphp
            <td style="text-align:center">Rp. {{ number_format($total_pasiva, 0, ',', '.') }}</td>
        </tr>
    </table>
    <br><br><br>
    <table>
    <tr>
		<th colspan="5" class="text-center">NERACA SALDO</th>
	</tr>
    </table>
    <table class="table table-sm table-bordered">
    <thead >
        <th>Keterangan</th>
        <th>Saldo Awal</th>
        <th>Debet</th>
        <th>Kredit</th>
        <th>Jumlah</th>
        <th>Total</th>
    </thead>
    <tbody class="text-left">
    @php 
    $aktiva_jumlah = 0; 
    $aktiva_debet = 0;
    $aktiva_kredit = 0;
    $sa_jumlah = 0;
    @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 1)
        <tr ><td colspan=6><p style="padding:10px">{{$value->nama}}</p></td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=6><p>{{$value2->nama}}</p></td>
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td >{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        $deb = 0;
                                        $kre = 0;
                                        $temp  = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                                $deb+=$value4->debit;
                                                $kre+=$value4->kredit;
                                                $temp+=$sa[$value4->ac_perush];
                                            @endphp
                                        @endforeach
                                    @endif
                                <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($deb-$sa_debit[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($kre-$sa_kredit[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($temp+$total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr >
                    <td class="text-center">Sub Total {{$value2->nama}}</td>
                    <td>Rp. {{ number_format($saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php 
                $aktiva_jumlah+=$nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac];
                $aktiva_debet+=$debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac];
                $aktiva_kredit+=$kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac];
                $sa_jumlah+=$saldo_awal[$value2->id_ac];
                @endphp
                @endforeach
            @endif
        @endif
    @endforeach
    <tr >
    <td><p style="padding:10px"><b>AKTIVA</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_debet, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_kredit, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah+$aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    </tr>
    </tbody>
</table>
<br><br>
<table class="table table-sm table-bordered">
    <thead >
        <th>Keterangan</th>
        <th>Saldo Awal</th>
        <th>Debet</th>
        <th>Kredit</th>
        <th>Jumlah</th>
        <th>Total</th>
    </thead>
    <tbody class="text-left">
    @php 
    $aktiva_jumlah = 0; 
    $aktiva_debet = 0;
    $aktiva_kredit = 0;
    $sa_jumlah = 0;
    @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 2)
        <tr ><td colspan=6><p style="padding:10px">{{$value->nama}}</p></td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=6><p>{{$value2->nama}}</p></td>
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td >{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        $deb = 0;
                                        $kre = 0;
                                        $temp  = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                                $deb+=$value4->debit;
                                                $kre+=$value4->kredit;
                                                $temp+=$sa[$value4->ac_perush];
                                            @endphp
                                        @endforeach
                                    @endif
                                <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($deb-$sa_debit[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($kre-$sa_kredit[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($temp+$total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr >
                    <td class="text-center">Sub Total {{$value2->nama}}</td>
                    <td>Rp. {{ number_format($saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php 
                $aktiva_jumlah+=$nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac];
                $aktiva_debet+=$debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac];
                $aktiva_kredit+=$kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac];
                $sa_jumlah+=$saldo_awal[$value2->id_ac];
                @endphp
                @endforeach
            @endif
        @endif
    @endforeach
    <!-- Modal -->
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 3)
        <tr ><td colspan=6><p style="padding:10px">{{$value->nama}}</p></td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=6><p>{{$value2->nama}}</p></td>
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td >{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        $deb = 0;
                                        $kre = 0;
                                        $temp  = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                                $deb+=$value4->debit;
                                                $kre+=$value4->kredit;
                                                $temp+=$sa[$value4->ac_perush];
                                            @endphp
                                        @endforeach
                                    @endif
                                <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($deb-$sa_debit[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($kre-$sa_kredit[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($temp+$total-$sa_total[$value3->id_ac], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr >
                    <td class="text-center">Sub Total {{$value2->nama}}</td>
                    <td>Rp. {{ number_format($saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php 
                $aktiva_jumlah+=$nilai[$value2->id_ac]-$saldo_awal[$value2->id_ac];
                $aktiva_debet+=$debit[$value2->id_ac]-$sa_total_deb[$value2->id_ac];
                $aktiva_kredit+=$kredit[$value2->id_ac]-$sa_total_kre[$value2->id_ac];
                $sa_jumlah+=$saldo_awal[$value2->id_ac];
                @endphp
                @endforeach
            @endif
        @endif
    @endforeach
    <tr >
    <td><p style="padding:10px"><b>PASIVA</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_debet, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_kredit, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    <td><p style="padding:10px"><b>Rp. {{ number_format($sa_jumlah+$aktiva_jumlah, 0, ',', '.') }}</b></p></td>
    </tr>
    </tbody>
</table>
</body>
</html>
@endif
@if(Request::segment(1) == "neracadetail")
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
	header("Content-Disposition: attachment; filename=neracadetail.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="7" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="7" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="7" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
<table class="table table-sm table-bordered">
    <thead >
        <th class="text-center">No</th>
        <th class="text-center">Tanggal</th>
        <th class="text-center">No Detail</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Debet</th>
        <th class="text-center">Kredet</th>
        <th class="text-center">Total</th>
    </thead>
    @foreach($data1 as $key => $value)
        @if($value->id_ac < 3)
           @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    @if(isset($data3[$value2->id_ac]))
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            @if(isset($data4[$value3->id_ac]))
                                @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                    @php $temp=$saldo_awal["$value4->ac_perush"]; @endphp
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr >
                                    <td colspan=7><b>{{$value4->ac_perush}} - {{$value4->nama_ac_perush}}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">SALDO AWAL</td>
                                        <td colspan="2">Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]))
                                        @foreach($data5[$value4->ac_perush] as $key5 => $value5)
                                        @php
                                        if(isset($value5->n_materai)){
                                            $temp+=$value5->n_materai;
                                        }elseif(isset($value5->total)){
                                            $temp-=$value5->total;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{$key5+1}}</td>
                                            <td>@if(isset($value5->created_at)){{date('d-M-Y', strtotime($value5->created_at))}} @endif</td>
                                            <td>@if(isset($value5->id_stt)){{$value5->id_stt}}@else{{$value5->id_detail}}@endif</td>
                                            <td>@if(isset($value5->info_kirim)){{$value5->info_kirim}}@else{{$value5->info}}@endif</td>
                                            <td>@if(isset($value5->n_materai))Rp. {{ number_format($value5->n_materai, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>@if(isset($value5->total))Rp. {{ number_format($value5->total, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr>
                                        <td class="text-center" colspan="6">Sub total {{$value4->nama_ac_perush}}</td>
                                        <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif                                 
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
           @endif
        @endif
    @endforeach
</table>
</body>
</html>
@endif
@if(Request::segment(1) == "rugilaba")
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rugilaba</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=rugilaba.xls");
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
</div>
<br><br>
<table class="table table-borderless table-sm ">
@php $total_aktiva=0; @endphp
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                            @endphp
                                        @endforeach
                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                    @endif                                
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                <tr><td class="text-center">Sub Total {{$value2->nama}}</td>
                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                @php $total_aktiva=$nilai[$value2->id_ac] @endphp
                </tr>
                <tr><td><p></p></td></tr>
                @endforeach
            @endif
        @endif
    @endforeach
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
        <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            @php
                                                $total+=$value4->total;
                                            @endphp
                                        @endforeach
                                        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                <tr><td class="text-center">Sub Total {{$value2->nama}}</td>
                <td>Rp. {{ number_format($nilai[$value2->id_ac], 0, ',', '.') }}</td>
                </tr>
                <tr>
                <td class="text-center">
                @if($value2->id_ac == 500)
                LABA KOTOR
                @elseif($value2->id_ac == 501)
                LABA OPERASIONAL
                @elseif($value2->id_ac == 502)
                LABA SETELAH POKOK DAN BUNGA
                @elseif($value2->id_ac == 503)
                LABA SEBELUM PAJAK
                @else
                LABA SETELAH PAJAK
                @endif
                </td>
                @php $total_aktiva-=$nilai[$value2->id_ac] @endphp
                <td>Rp. {{ number_format($total_aktiva, 0, ',', '.') }}</td>
                </tr>
                <tr><td><p></p></td></tr>
                @endforeach
            @endif
        @endif
    @endforeach
</table>
</body>
</html>
@endif
@if(Request::segment(1) == "rugilabadetail")
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rugilaba</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=rugilabadetail.xls");
?>
<body class="contianer">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="7" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="7" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="7" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
<table class="table table-sm table-bordered">
    <thead >
        <th class="text-center">No</th>
        <th class="text-center">Tanggal</th>
        <th class="text-center">No Detail</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Debet</th>
        <th class="text-center">Kredet</th>
        <th class="text-center">Total</th>
    </thead>
    @foreach($data1 as $key => $value)
        @if($value->id_ac > 3)
           @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    @if(isset($data3[$value2->id_ac]))
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            @if(isset($data4[$value3->id_ac]))
                                @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                @php $temp=0; @endphp
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr >
                                    <td colspan=7><b>{{$value4->ac_perush}} - {{$value4->nama_ac_perush}}</b></td>
                                    </tr>
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]))                                        
                                        @foreach($data5[$value4->ac_perush] as $key5 => $value5)
                                        @php
                                        if(isset($value5->n_materai)){
                                            $temp+=$value5->n_materai;
                                        }elseif(isset($value5->total)){
                                            $temp-=$value5->total;
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{$key5+1}}</td>
                                            <td>@if(isset($value5->created_at)){{date('d-M-Y', strtotime($value5->created_at))}} @endif</td>
                                            <td>@if(isset($value5->id_stt)){{$value5->id_stt}}@else{{$value5->id_detail}}@endif</td>
                                            <td>@if(isset($value5->info_kirim)){{$value5->info_kirim}}@else{{$value5->info}}@endif</td>
                                            <td>@if(isset($value5->n_materai))Rp. {{ number_format($value5->n_materai, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>@if(isset($value5->total))Rp. {{ number_format($value5->total, 0, ',', '.') }}@else 0 @endif</td>
                                            <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    @if(isset($data5[$value4->ac_perush]) and count($data5[$value4->ac_perush]) != 0)
                                    <tr>
                                        <td class="text-center" colspan="6">Sub total {{$value4->nama_ac_perush}}</td>
                                        <td>Rp. {{ number_format($temp, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
           @endif
        @endif
    @endforeach
</table>
</body>
</html>
@endif
@if(Request::segment(1) == "cashflow")
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cashflow</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=cashflow.xls");
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
</div>
<br><br>
<table class="table table-borderless table-sm ">
@php
$total_pendapatan = 0;
$total_hpp = 0;
@endphp
    <tr><h4>CASH IN</h4></tr>
    <tr>PENDAPATAN</tr>
    @if($data3[400])
    @foreach($data3[400] as $key => $value)
        @if(isset($data4[$value->id_ac]))
            @foreach($data4[$value->id_ac] as $key2 => $value2)
            <tr>
            <td style="padding-left:50px">{{$value2->nama}}</td>
            <td>Rp. {{ number_format($value2->total_pendapatan, 0, ',', '.') }}</td>
            @php
            $total_pendapatan+=$value2->total_pendapatan;
            @endphp
            </tr>
            @endforeach
        @endif
        
    @endforeach
    @endif
    <tr>
        <td>Sub Total Pendapatan</td>
        <td>Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
    </tr>
</table>
<br>
<h4>CASH OUT</h4>
<br>
<table class="table table-borderless table-sm ">
    @php
    $total_hpp = 0;
    $total_handling = 0;
    $total_operasional = 0;
    $total_gaji = 0;
    $total_inventaris = 0;
    @endphp
    <tr>HARGA POKOK</tr>
    @if(isset($data3[500]))
    @foreach($data3[500] as $key => $value)
        @if($value->id_ac != '500-8')
            @if(isset($data4[$value->id_ac]))
                @foreach($data4[$value->id_ac] as $key2 => $value2)
                <tr>
                <td style="padding-left:50px">{{$value2->nama_ac_perush}}</td>
                <td>Rp. {{ number_format($value2->total_pendapatan, 0, ',', '.') }}</td>
                @php
                $total_hpp+=$value2->total_pendapatan;
                @endphp
                </tr>
                @endforeach
            @endif
        @endif
        
    @endforeach
    @endif
    <tr>
        <td class="text-center">Sub Total HARGA POKOK</td>
        <td>Rp. {{ number_format($total_hpp, 0, ',', '.') }}</td>
    </tr>

    <tr><td>HANDLING</td></tr>
    @if(isset($data3[500]))
    @foreach($data3[500] as $key => $value)
        @if($value->id_ac == '500-8' and isset($data4[$value->id_ac]))
            @foreach($data4[$value->id_ac] as $key2 => $value2)
            <tr>
            <td style="padding-left:50px">{{$value2->nama_ac_perush}}</td>
            <td>Rp. {{ number_format($value2->total_pendapatan, 0, ',', '.') }}</td>
            @php
            $total_handling+=$value2->total_pendapatan;
            @endphp
            </tr>
            @endforeach
        @endif
        
    @endforeach
    @endif
    <tr>
        <td class="text-center">Sub Total HANDLING</td>
        <td>Rp. {{ number_format($total_handling, 0, ',', '.') }}</td>
    </tr>

    <tr><td>OPERASIONAL</td></tr>
    @if(isset($data3[501]))
    @foreach($data3[501] as $key => $value)
        @if($value->id_ac == '501-1' or $value->id_ac == '501-2')
            <tr>
            <td style="padding-left:50px">{{$value->nama}}</td>
            <td>Rp. {{ number_format($nilai3[$value->id_ac], 0, ',', '.') }}</td>
            @php
            $total_operasional+=$nilai3[$value->id_ac];
            @endphp
            </tr>
        @endif
        
    @endforeach
    @endif
    <tr>
        <td class="text-center">Sub Total Operasional</td>
        <td>Rp. {{ number_format($total_operasional, 0, ',', '.') }}</td>
    </tr>
    <tr><td>Gaji</td></tr>
    @if(isset($data3[501]))
    @foreach($data3[501] as $key => $value)
        @if($value->id_ac == '501-3')
            @foreach($data4[$value->id_ac] as $key2 => $value2)
            <tr>
            <td style="padding-left:50px">{{$value2->nama_ac_perush}}</td>
            <td>Rp. {{ number_format($value2->total_pendapatan, 0, ',', '.') }}</td>
            @php
            $total_gaji+=$value2->total_pendapatan;
            @endphp
            </tr>
            @endforeach
        @endif    
    @endforeach
    @endif
    @if(isset($data3[503]))
    @foreach($data3[503] as $key => $value)
        @if($value->id_ac == '503-2')
            @foreach($data4[$value->id_ac] as $key2 => $value2)
                @if($value2->ac_perush == '503-200' or $value2->ac_perush == '503-201')
                <tr>
                <td style="padding-left:50px">{{$value2->nama_ac_perush}}</td>
                <td>Rp. {{ number_format($value2->total_pendapatan, 0, ',', '.') }}</td>
                @php
                $total_gaji+=$value2->total_pendapatan;
                @endphp
                </tr>
                @endif
            @endforeach
        @endif    
    @endforeach
    @endif
    <tr>
        <td class="text-center">Sub Total Gaji</td>
        <td>Rp. {{ number_format($total_gaji, 0, ',', '.') }}</td>
    </tr>


    <tr><td>INVENTARIS</td></tr>
    @if(isset($data3[103]))
    @foreach($data3[103] as $key => $value)
        <tr>
        <td style="padding-left:50px">{{$value->nama}}</td>
        <td>{{$value->total_pendapatan}}</td>
        @php
        $total_inventaris+=$value->total_pendapatan;
        @endphp
        </tr>
    @endforeach
    @endif
    <tr>
        <td class="text-center">Sub Total Inventaris</td>
        <td>Rp. {{ number_format($total_inventaris, 0, ',', '.') }}</td>
    </tr>
</table>
</body>
</html>
@endif
@if(Request::segment(1) == "cashflowdetail")
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cashflow</title>
</head>
<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=cashflowdetail.xls");
?>
<body>
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
</div>
<br><br>
<h4>CASH IN</h4>
<br><br>
<table class="table table-sm table-bordered" id="html_table" width="100%">
    <thead >
        <th>No</th>
        <th>No Bukti</th>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Cash IN</th>
        <th>Cash OUT</th>
    </thead>
    <tbody>
        @php $no = 0; @endphp
        @foreach($batas as $key => $value)
            <tr><td colspan="6" class="text-center" >{{$value->nama}}</td></tr>
            @foreach($acperush as $key2 => $value2)
            @if(isset($data[$value->id_ac][$value2->id_ac]) and count($data[$value->id_ac][$value2->id_ac]) != 0)
            <tr><td colspan="6">{{$value2->nama}}</td></tr>
            @endif
                @if(isset($data[$value->id_ac][$value2->id_ac]))
                    @foreach($data[$value->id_ac][$value2->id_ac] as $key3 => $value3)
                        @if(isset($value3->tgl_masuk))
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{$value3->id_detail}}</td>
                                <td>{{$value3->tgl_masuk}}</td>
                                <td>{{$value3->info_kredit}}</td>
                                <td>Rp. {{ number_format($value3->total_kredit, 0, ',', '.') }}</td>
                                <td>0</td>
                            </tr>
                        @php $no++ @endphp
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>
<br>
<h4>CASH OUT</h4>
<table class="table table-sm table-bordered" id="html_table" width="100%">
    <thead >
        <th>No</th>
        <th>No Bukti</th>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Cash IN</th>
        <th>Cash OUT</th>
    </thead>
    <tbody>
        @php $no = 0; @endphp
        @foreach($batas as $key => $value)
            <tr><td colspan="6" class="text-center" >{{$value->nama}}</td></tr>
            @foreach($acperush as $key2 => $value2)
            @if(isset($data[$value->id_ac][$value2->id_ac]) and count($data[$value->id_ac][$value2->id_ac]) != 0)
            <tr><td colspan="6">{{$value2->nama}}</td></tr>
            @endif
                @if(isset($data[$value->id_ac][$value2->id_ac]))
                    @foreach($data[$value->id_ac][$value2->id_ac] as $key3 => $value3)
                        @if(isset($value3->tgl_keluar))
                            <tr>
                                <td>{{$no+1}}</td>
                                <td>{{$value3->id_detail}}</td>
                                <td>{{$value3->tgl_keluar}}</td>
                                <td>{{$value3->info_kredit}}</td>
                                <td>0</td>
                                <td>Rp. {{ number_format($value3->total_kredit, 0, ',', '.') }}</td>
                            </tr>
                        @php $no++ @endphp
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>
</body>
</html>
@endif