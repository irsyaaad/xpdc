<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca</title>
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
<br><br>
</div>
<div class="container">
        @php $total_aktiva = 0; @endphp
        <table width="100%">
            <tr>
                <td>
                    <table width="100%">
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

                                                                {{-- <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td> --}}
                                                                @if(isset($nilai[$value3->id_ac]))
                                                                    <td>Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                                    @php
                                                                        $total_aktiva+=$nilai[$value3->id_ac];
                                                                    @endphp
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endif
                            @endif
                        @endforeach
                    </table>
                </td>
                <td>
                    @php $total_pasiva = 0;  @endphp
                    <table width="100%">
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
                                                                @if(isset($nilai[$value3->id_ac]))
                                                                    <td>Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                                    @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                            @endif
                        @endforeach
                        @foreach($data1 as $key => $value)
                            @if($value->id_ac == 3)
                                <tr><td>{{$value->nama}}</td></tr>
                                    @if(isset($data2[$value->id_ac]))
                                        @foreach($data2[$value->id_ac] as $key2 => $value2)
                                            <tr>
                                                    @if(isset($data3[$value2->id_ac]))
                                                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                            <tr>
                                                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                                                @if(isset($lababerjalan) and $value3->id_ac == 321)
                                                                    <td>Rp. {{ number_format($lababerjalan, 0, ',', '.') }}</td>
                                                                    @php $total_pasiva+=$lababerjalan @endphp
                                                                @elseif(isset($nilai[$value3->id_ac]))
                                                                    <td>Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                                    @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                            @endif
                        @endforeach
                    </table>
                </td>
            </tr>
            {{-- total --}}
            <tr>
                <td colspan="2">
                    <hr class="hr">
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td>Total Aktiva</td>
                            <td style="text-align:center">Rp. {{ number_format($total_aktiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table width="100%">
                        <tr>
                            <td>Total Pasiva</td>
                            <td style="text-align:center">Rp. {{ number_format($total_pasiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>