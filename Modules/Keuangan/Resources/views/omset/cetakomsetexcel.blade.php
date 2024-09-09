@if(Request::segment(1) == "sttbycarabayar")
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
	header("Content-Disposition: attachment; filename=sttbycarabayar.xls");
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
<table class="table table-sm table-bordered">
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>No Stt</th>
                <th>Tanggal Masuk</th>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Penerima</th>
                <th>No Bukti Pembayaran</th>
                <th>Total</th>
                <th>Piutang</th>
            </tr>
        </thead>     
        <tbody>
        @foreach($carabayar as $key => $value)
            @if(isset($data[$value->id_cr_byr_o]))
                <tr>
                    <td colspan="9" style="background-color: #e3e5e8;">{{$value->nm_cr_byr_o}}</td>
                </tr>
                @php
                    $count = 0;
                    $omset = 0;
                    $piutang = 0;
                @endphp        
                @foreach($data[$value->id_cr_byr_o] as $key2 => $value2)
                <tr>
                    <td>{{$count+=1}}</td>
                    <td>@if(isset($value2->id_stt)){{$value2->id_stt}}@endif</td>
                    <td>@if(isset($value2->tgl_masuk)){{ daydate($value2->tgl_masuk).", ".dateindo($value2->tgl_masuk) }}@endif</td>
                    <td>@if(isset($value2->id_plgn)){{$value2->id_plgn}}@endif</td>
                    <td>@if(isset($value2->nm_pelanggan)){{$value2->nm_pelanggan}}@endif</td>
                    <td>@if(isset($value2->penerima_nm)){{$value2->penerima_nm}}@endif</td>
                    <td>@if(isset($value2->id_order_pay)){{$value2->id_order_pay}}@endif</td>
                    <td>@if(isset($value2->c_total))Rp. {{ number_format($value2->c_total, 0, ',', '.') }}@endif</td>
                    <td>@if(isset($value2->piutang))Rp. {{ number_format($value2->piutang, 0, ',', '.') }}@endif</td>
                    @php
                        $omset+=$value2->c_total;
                        $piutang+=$value2->piutang;
                    @endphp
                </tr>
                @endforeach
                <tr>
                    <td colspan="7" class="text-center">Sub Total Omset by {{$value->nm_cr_byr_o}}</td>
                    <td>Rp. {{ number_format($omset, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($piutang, 0, ',', '.') }}</td>
                </tr>
            @else
            
            @endif            
        @endforeach
        </tbody>
        </table>
</body>
</html>
@endif  
@if(Request::segment(1) == "sttbyusers")
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
	header("Content-Disposition: attachment; filename=sttbyusers.xls");
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
<table class="table table-sm table-bordered" id="html_table" width="100%">
	<thead class="text-center">
		<tr>
            <th>No</th>
            <th>No Stt</th>
            <th>Tanggal Masuk</th>
            <th>ID Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Penerima</th>
            <th>No Bukti Pembayaran</th>
            <th>Total</th>
            <th>Piutang</th>
		</tr>
    </thead>
    <tbody>
        @foreach($user as $key => $value)
            @if(isset($data[$value->id_user]))
                <tr>
                    <td colspan="9" style="background-color: #e3e5e8;">{{$value->nm_user}}</td>
                </tr>
                @php
                    $count = 0;
                    $omset = 0;
                    $piutang = 0;
                @endphp        
                @foreach($data[$value->id_user] as $key2 => $value2)
                <tr>
                    <td>{{$count+=1}}</td>
                    <td>@if(isset($value2->id_stt)){{$value2->id_stt}}@endif</td>
                    <td>@if(isset($value2->tgl_masuk)){{ daydate($value2->tgl_masuk).", ".dateindo($value2->tgl_masuk) }}@endif</td>
                    <td>@if(isset($value2->id_plgn)){{$value2->id_plgn}}@endif</td>
                    <td>@if(isset($value2->nm_pelanggan)){{$value2->nm_pelanggan}}@endif</td>
                    <td>@if(isset($value2->penerima_nm)){{$value2->penerima_nm}}@endif</td>
                    <td>@if(isset($value2->id_order_pay)){{$value2->id_order_pay}}@endif</td>
                    <td>@if(isset($value2->c_total))Rp. {{ number_format($value2->c_total, 0, ',', '.') }}@endif</td>
                    <td>@if(isset($value2->piutang))Rp. {{ number_format($value2->piutang, 0, ',', '.') }}@endif</td>
                    @php
                        $omset+=$value2->c_total;
                        $piutang+=$value2->piutang;
                    @endphp
                </tr>
                @endforeach
                <tr>
                    <td colspan="7" class="text-center">Sub Total Omset by {{$value->nm_user}}</td>
                    <td>Rp. {{ number_format($omset, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($piutang, 0, ',', '.') }}</td>
                </tr>
            @else
            
            @endif            
        @endforeach
    </tbody>
</table>
</body>
</html>
@endif 
@if(Request::segment(1) == "sttbydm")
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
	header("Content-Disposition: attachment; filename=sttbydm.xls");
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
<table class="table table-sm table-bordered" id="html_table" width="100%">
	<thead class="text-center">
		<tr>
            <th>No</th>
            <th>No Stt</th>
            <th>Tanggal Masuk</th>
            <th>Nama Pelanggan</th>
            <th>Berat</th>
            <th>Volume</th>
            <th>Koli</th>
            <th>Total</th>
            <th>Berangkat</th>
		</tr>
    </thead>
    <tbody>
        @foreach($dm as $key => $value)
            @if(isset($data[$value->id_dm]))
                <tr>
                    <td colspan="9" style="background-color: #e3e5e8;">{{$value->id_dm}}</td>
                </tr>
                @php
                    $count = 0;
                    $omset = 0;
                    $piutang = 0;
                @endphp        
                @foreach($data[$value->id_dm] as $key2 => $value2)
                <tr>
                    <td>{{$count+=1}}</td>
                    <td>@if(isset($value2->id_stt)){{$value2->id_stt}}@endif</td>
                    <td>@if(isset($value2->tgl_masuk)){{ daydate($value2->tgl_masuk).", ".dateindo($value2->tgl_masuk) }}@endif</td>
                    <td>@if(isset($value2->nm_pelanggan)){{$value2->nm_pelanggan}}@endif</td>
                    <td>@if(isset($value2->penerima_nm)){{$value2->penerima_nm}}@endif</td>
                    <td>@if(isset($value2->n_berat)){{$value2->n_berat}}@endif</td>
                    <td>@if(isset($value2->n_volume)){{$value2->n_berat}}@endif</td>
                    <td>@if(isset($value2->c_total))Rp. {{ number_format($value2->c_total, 0, ',', '.') }}@endif</td>
                    <td>@if(isset($value2->tgl_berangkat)){{ daydate($value2->tgl_berangkat).", ".dateindo($value2->tgl_berangkat) }}@endif</td>
                    @php
                        $omset+=$value2->c_total;
                    @endphp
                </tr>
                @endforeach
                <tr >
                    <td colspan="8" class="text-center">Sub Total Omset by {{$value->nm_user}}</td>
                    <td>Rp. {{ number_format($omset, 0, ',', '.') }}</td>
                </tr>
            @else
            
            @endif            
        @endforeach
    </tbody>
</table>
</body>
</html>
@endif 
@if(Request::segment(1) == "omsetbypelanggan")
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
	header("Content-Disposition: attachment; filename=omsetbypelanggan.xls");
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
<table class="table table-sm table-bordered" id="html_table" width="100%">
	<thead class="text-center">
		<tr>
            <th>No</th>
            <th>ID Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>Mei</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Aug</th>
            <th>Sep</th>
            <th>Okt</th>
            <th>Nov</th>
            <th>Des</th>
		</tr>
    </thead>
    <tbody>
        @foreach($grouppelanggan as $key => $value)
           @if(isset($pelanggan[$value->id_plgn_group]))
                <tr>
                    <td colspan="15">{{$value->nm_group}}</td>
                </tr>
                @foreach($pelanggan[$value->id_plgn_group] as $key2 => $value2)
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{$value2->nm_pelanggan}}</td>
                        <td>@if(isset($data[$value2->id_pelanggan][1]))Rp. {{ number_format($data[$value2->id_pelanggan][1]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][2]))Rp. {{ number_format($data[$value2->id_pelanggan][2]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][3]))Rp. {{ number_format($data[$value2->id_pelanggan][3]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][4]))Rp. {{ number_format($data[$value2->id_pelanggan][4]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][5]))Rp. {{ number_format($data[$value2->id_pelanggan][5]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][6]))Rp. {{ number_format($data[$value2->id_pelanggan][6]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][7]))Rp. {{ number_format($data[$value2->id_pelanggan][7]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][8]))Rp. {{ number_format($data[$value2->id_pelanggan][8]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][9]))Rp. {{ number_format($data[$value2->id_pelanggan][9]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][10]))Rp. {{ number_format($data[$value2->id_pelanggan][10]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][11]))Rp. {{ number_format($data[$value2->id_pelanggan][11]->totalamount, 0, ',', '.') }}@else @endif</td>
                        <td>@if(isset($data[$value2->id_pelanggan][12]))Rp. {{ number_format($data[$value2->id_pelanggan][12]->totalamount, 0, ',', '.') }}@else @endif</td>    
                    </tr>
                @endforeach
           @endif
        @endforeach
    </tbody>
</table>
</body>
</html>
@endif 