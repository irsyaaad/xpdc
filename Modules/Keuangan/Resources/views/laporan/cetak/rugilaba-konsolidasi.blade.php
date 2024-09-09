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
    <title>Cetak RUGILABA KONSOLIDASI| Lsj Express Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style>
    @media print{
        @page
        {
            size: A4 portrait;
            /* size: landscape; */
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 15px;
        font-size: 12px;
        /* font-weight: bold; */
        color: #000;
    }
    th {
        border: 1px solid black;
        border-collapse: collapse;
        border-style: none;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
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
        line-height : 15px;
    }
    .kepada{
        line-height: 15px;
    }
    .hr{
        border-top: 1px solid red;
        margin-top : 10px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .penutup{
        font-size: 14px;
    }
    .tr-bold{
        font-weight: bold !important;
    }
    .atas{
        line-height : 5px;
    }
</style>
</head>
<body>
    <div class="atas">
        <p class="text-center" style="font-size:15px;"><b>LAPORAN RUGILABA KONSOLIDASI</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
@php
    $pembagi_nilai = 1;
    $presisi_desimal = 0;
    $total_rugilaba   = 0;
@endphp
<table class="table table-borderless table-sm ">
    <thead>
        <th>Nama Account</th>
        @foreach ($perush as $key => $value)
            <th style="border-collapse: collapse">{{ $value->nm_perush }}</th>
        @endforeach
        <th>Total</th>
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
                                @php $sub_total = 0; @endphp
                                @foreach ($perush as $key => $value)
                                    @if (isset($data[$value->id_perush]))
                                        @if(isset($data[$value->id_perush][$value3->id_ac]))
                                            <td class="text-right">{{ ($value3->tipe == "D" && $data[$value->id_perush][$value3->id_ac] != 0) ? "-":"" }}{{ number_format($data[$value->id_perush][$value3->id_ac] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                                            @php
                                                // if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                if ($value3->tipe == "D") {
                                                    $total_pendapatan[$value->id_perush]-=$data[$value->id_perush][$value3->id_ac];
                                                    $sub_total -= $data[$value->id_perush][$value3->id_ac];
                                                }else{
                                                    $total_pendapatan[$value->id_perush]+=$data[$value->id_perush][$value3->id_ac];
                                                    $sub_total += $data[$value->id_perush][$value3->id_ac];
                                                }
                                                
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                                <td class="text-right"> {{ number_format($sub_total / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    <td class="text-center">Sub Total PENDAPATAN</td>
                    @php $totalnya = 0; @endphp
                    @foreach ($perush as $key => $value)
                        <td class="text-right"> {{ number_format($total_pendapatan[$value->id_perush] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                        @php $totalnya += $total_pendapatan[$value->id_perush] @endphp
                    @endforeach
                    <td class="text-right"> {{ number_format($totalnya / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                </tr>
            @endif
        @endif
    @endforeach

    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
            <tr><td>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                    @if(isset($data3[$value2->id_ac]))
                    @php $temp = $total; @endphp
                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                            <tr>
                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                @php $sub_total = 0; @endphp
                                @foreach ($perush as $key => $value)
                                    @isset($data[$value->id_perush])
                                        @if(isset($data[$value->id_perush][$value3->id_ac]))
                                            <td class="text-right"> {{ number_format($data[$value->id_perush][$value3->id_ac] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                                            @php
                                                if ($value3->tipe == "K") {
                                                    $temp[$value->id_perush]+=$data[$value->id_perush][$value3->id_ac];
                                                    // $sub_total += $data[$value->id_perush][$value3->id_ac];
                                                } else {
                                                    $temp[$value->id_perush]-=$data[$value->id_perush][$value3->id_ac];
                                                    // $sub_total -= $data[$value->id_perush][$value3->id_ac];
                                                }
                                                $sub_total += $data[$value->id_perush][$value3->id_ac];
                                                
                                            @endphp
                                        @endif
                                    @endisset
                                @endforeach
                                <td class="text-right"> {{ number_format($sub_total / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                    <td class="text-center">Sub Total {{$value2->nama}}</td>
                    @php $totalnya = 0; @endphp
                    @foreach ($perush as $key => $value)
                        <td class="text-right"> {{ number_format($temp[$value->id_perush] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                        @php 
                            $total_pendapatan[$value->id_perush] += $temp[$value->id_perush];
                            // $totalnya += $total[$value->id_perush];
                            $totalnya += $temp[$value->id_perush];
                        @endphp
                    @endforeach
                    <td class="text-right"> {{ number_format($totalnya / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
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
                    @php $totalnya = 0; @endphp
                    @foreach ($perush as $key => $value)
                        <td class="text-right"> {{ number_format($total_pendapatan[$value->id_perush] / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                        @php 
                            $totalnya += $total_pendapatan[$value->id_perush];
                        @endphp
                    @endforeach
                    <td class="text-right"> {{ number_format($totalnya / $pembagi_nilai, $presisi_desimal, ',', '.') }}</td>
                    </tr>
                @endforeach

            @endif
        @endif
    @endforeach

</table>
    </body>
    
    </html>
    