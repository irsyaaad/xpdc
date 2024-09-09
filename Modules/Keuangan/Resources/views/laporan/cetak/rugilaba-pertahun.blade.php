<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <title>Cetak Rugilaba Pertahun | Lsj Express Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style type="text/css">
        html { margin: 15px}
        body{
            font-family: sans-serif !important;
        }
        .page-break {
            page-break-after: always;
        }
        .table1 {
            font-size: 8pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }
        .table1 tr th{
            background: grey;
            color: #fff;
            font-weight: bold;
        }
        .table1, th, td {
            text-align: left;
        }
        .text-center{
            text-align: center;
        }
        .text-title{
            margin-left: 10pt;
        }

        .heading{
            text-align: center;
            padding-top: 10px;
            line-height: 15px;
        }

        .table2{

            margin-top: -5px;
            font-size: 8pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }

        .text-body{
            font-size: 7pt !important;
        }

        .stt{
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-top: -40px;
            text-decoration: underline;
        }

        .isi{
            padding : 20px;
        }

        .table-isi{
            font-size: 12px;
        }

        .isi-content{
            border-bottom : 1px solid black;
        }

        .footer{
            padding : 20px;
        }

        .table-footer{
            font-size: 12px;
            text-align:center;
        }

        th {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size : 12px;
        }

        td {
            font-size : 10px;
        }
        .t {
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 10px;
            font-size : 10px;
        }

        .head{
            font-size: 18px;
            font-weight: bold;
            height: 50px;
        }
        .text-center{
            text-align : center;
        }
        .text-right{
            text-align : right;
        }
        .heading{
            text-align: center;
            font-size: 14px;
        }
        .kepada{
            margin-top: -40px;
            line-height: 10px;
        }
        .kepada td{
            font-size : 12px;
        }
        .hr{
            border-top: 1px solid red;
            margin-top : 10px;
        }
        .headnote{
            border-top: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
            text-align: left;
            font-size : 10px;
        }
        .headnote td {
            padding-left : 10px;
        }
        .note{
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
            text-align: left;
            font-size : 10px;
            height : 50px;
        }
        .hrhead{
            border: 1px solid black;
        }
        .penutup td{
            font-size: 12px;
        }
        .setelah-garis p {
            font-size: 12px;
        }
        .tr-bold{
            font-weight: bold !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr width="30%">
                <td rowspan="3" style="text-align: center;">
                    @php

                    if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                    }

                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="height: 50px; margin-top:-20px">
                </td>
                <td class="heading">
                    <center>
                        <b style="font-size:16px">{{ strtoupper($perusahaan->nm_perush) }}</b><br>
                        <label style="font-size:12px">
                            {!! $perusahaan->header !!}
                        </label>
                    </center>

                    <hr>
                </td>
            </tr>
        </table>
    </div>
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
    </body>

    </html>
