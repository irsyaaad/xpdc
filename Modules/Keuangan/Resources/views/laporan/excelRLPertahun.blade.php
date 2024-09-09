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
	header("Content-Disposition: attachment; filename=RugiLabaPertahun.xls");
?>
<body class="container">
<div class="container"  style=" margin-top:10px;">
	<table>
		<tr>
		<th colspan="13" class="text-center">{{$perusahaan->nm_perush}}</th>
		</tr>
		<tr>
		<th colspan="13" class="text-center">{{$perusahaan->alamat}}, {{$perusahaan->kotakab}}-{{$perusahaan->provinsi}}</th>
		</tr>
		<tr>
		<th colspan="13" class="text-center">Telp. {{$perusahaan->telp}} (Hunting), Fax. {{$perusahaan->fax}}</th>
		</tr>
	</table>
</div>
<br><br>
<table class="table table-bordered table-sm ">
    <thead >
        <th class="text-center">Keterangan</th>
        <th class="text-center">Jan</th>
        <th class="text-center">Feb</th>
        <th class="text-center">Mar</th>
        <th class="text-center">Apr</th>
        <th class="text-center">Mei</th>
        <th class="text-center">Jun</th>
        <th class="text-center">Jul</th>
        <th class="text-center">Agu</th>
        <th class="text-center">Sep</th>
        <th class="text-center">Okt</th>
        <th class="text-center">Nov</th>
        <th class="text-center">Des</th>
    </thead>
    <tbody>
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=13 style="padding-left:10px;background-color: #ededed;">{{$value2->nama}}</td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td>{{$value3->nama}}</td>
                                    @for ($i = 1; $i <= 12; $i++)
                                    @if(isset($temp[$i][$value3->id_ac]))
                                        <td>Rp. {{ number_format($temp[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                    @endif
                                    @endfor                                
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @if(isset($total[$i][$value2->id_ac]))
                                <td style="background-color: #ededed;">Rp. {{ number_format($total[$i][$value2->id_ac], 0, ',', '.') }}</td>
                            @endif
                        @endfor                    
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=13 style="padding-left:10px;background-color: #ededed;">{{$value2->nama}}</td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td>{{$value3->nama}}</td>
                                    @for ($i = 1; $i <= 12; $i++)
                                    @if(isset($temp[$i][$value3->id_ac]))
                                        <td>Rp. {{ number_format($temp[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                    @endif
                                    @endfor
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @if(isset($total[$i][$value2->id_ac]))
                                <td style="background-color: #ededed;">Rp. {{ number_format($total[$i][$value2->id_ac], 0, ',', '.') }}</td>
                            @endif
                        @endfor                    
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    </tbody>
</table>
</body>
</html>