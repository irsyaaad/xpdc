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
    <title>Cetak RUGILABA | Lsj Express Group</title>
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
        text-align: center;
        font-size : 12px;
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
<div class="container">
        <table width="100%">
            <tr width="30%">
                <td style="text-align: center;">
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
                        <label style="font-size:12px;">
                            {!! $perusahaan->header !!}
                        </label>
                    </center>   
                </td>                
            </tr>            
        </table>
        <hr>
    </div>
    <div class="atas">
        <p class="text-center" style="font-size:15px;"><b>LAPORAN RUGILABA</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
        @php
        $total_pendapatan = 0;
        $total_rugilaba   = 0;
        @endphp
        <table width="100%">
            @foreach ($data1 as $key => $value)
                @if ($value->id_ac == 4)
                    <tr class="tr-bold">
                        <td>{{ $value->nama }}</td>
                    </tr>
                    @if (isset($data2[$value->id_ac]))
                        @foreach ($data2[$value->id_ac] as $key2 => $value2)
                            @if (isset($data3[$value2->id_ac]))
                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                    {{-- @if (isset($nilai[$value3->id_ac]) && $nilai[$value3->id_ac] != 0) --}}
                                        <tr>
                                            <td style="padding-left:50px">{{ $value3->nama }}</td>
                                            @if (isset($nilai[$value3->id_ac]))
                                                <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pendapatan -= $nilai[$value3->id_ac];
                                                    } else {
                                                        $total_pendapatan += $nilai[$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
                                            <td class="text-center">{{ ($nilai[$value3->id_ac] && ($total_omset)) != 0 ? round(($nilai[$value3->id_ac]/($total_omset)) * 100,2) : 0 }} %</td>
                                        </tr>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                        @endforeach
                        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                            <td class="text-center"> TOTAL PENDAPATAN</td>
                            <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                            <td class="text-center">{{ ($total_pendapatan && ($total_omset)) != 0 ? round(($total_pendapatan/($total_omset)) * 100,2) : 0 }} %</td>
                        </tr>
                    @endif
                @endif
            @endforeach

            @foreach ($data1 as $key => $value)
                @if ($value->id_ac == 5)
                    <tr>
                        <td>{{ $value->nama }}</td>
                    </tr>
                    @if (isset($data2[$value->id_ac]))
                        @foreach ($data2[$value->id_ac] as $key2 => $value2)
                            @php $total = 0; @endphp
                            <td style="padding-left:10px">
                                <p>{{ $value2->nama }}</p>
                            </td>
                            @if (isset($data3[$value2->id_ac]))
                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                    {{-- @if (isset($nilai[$value3->id_ac]) && $nilai[$value3->id_ac] != 0) --}}
                                    <tr>
                                        <td style="padding-left:50px">{{ $value3->nama }}</td>
                                        @if (isset($nilai[$value3->id_ac]))
                                            <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                            @if ($value3->tipe == "K")
                                                @php
                                                    $total += $nilai[$value3->id_ac];
                                                @endphp
                                            @else
                                                @php
                                                    $total -= $nilai[$value3->id_ac];
                                                @endphp
                                            @endif
                                        @endif
                                        <td class="text-center">{{ ($nilai[$value3->id_ac] && ($total_omset)) != 0 ? (round(($nilai[$value3->id_ac]/($total_omset)) * 100,2)) : 0}} %</td>
                                    </tr>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                            <tr>
                                <td class="text-center"> TOTAL {{ $value2->nama }}</td>
                                <td class="text-right"> {{ number_format($total, 0, ',', '.') }}</td>
                                <td class="text-center">{{ ($total && ($total_omset)) != 0 ? (round(($total/($total_omset)) * 100,2)) : 0 }} %</td>
                                @php $total_pendapatan+=$total @endphp
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
                                <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ ($total_pendapatan && ($total_omset)) != 0 ? (round(($total_pendapatan/($total_omset)) * 100,2)) : 0}} %</td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            @endforeach

        </table>
        </div>
    </div>
    </body>
    
    </html>
    