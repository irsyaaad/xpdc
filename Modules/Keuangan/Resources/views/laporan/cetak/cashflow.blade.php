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
    <title>Cetak CASHFLOW | Lsj Express Group</title>
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
        <p class="text-center" style="font-size:15px;"><b>LAPORAN CASHFLOW</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
        <table width="100%">
            <tr>
                <td>CASH IN</td>
            </tr>
            @php
                $total_all_cash_in = 0;
            @endphp
            @foreach ($head as $value)
                @if ($value->tipe == '1')
                    <tr>
                        <td>{{ $value->nama_cashflow }}</td>
                    </tr>
                    @php
                        $sub_total = 0;
                    @endphp
                    @if (isset($child[$value->id_cf]))
                        @foreach ($child[$value->id_cf] as $value2)
                            @php
                                $total_in = 0;
                                $total_out = 0;
                                if (isset($cashflow[$value2->id_cf])) {
                                    foreach ($cashflow[$value2->id_cf] as $index) {
                                        if (isset($cashin[$index])) {
                                            foreach ($cashin[$index] as $item) {
                                                $total_in += $item->nominal;
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <tr>
                                <td style="padding-left: 100px">{{ $value2->nama_cashflow }}</td>
                                <td class="text-right">{{ toNumber($total_in) }}</td>
                                <td class="text-right">{{ ($total_in && $total_cash_in) > 0 ? round(($total_in/($total_cash_in)) * 100,2) : 0 }} %</td>
                            </tr>
                            @php
                                $sub_total += $total_in;
                            @endphp
                        @endforeach
                    @endif
                    <tr>
                        <td class="text-center">Sub Total {{ $value->nama_cashflow }}</td>
                        <td class="text-right">{{ toNumber($sub_total) }}</td>
                        <td class="text-right">{{ ($sub_total && $total_cash_in) > 0 ? round(($sub_total/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
                    </tr>
                    @php
                        $total_all_cash_in += $sub_total;
                    @endphp
                @endif
            @endforeach
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td class="text-center">TOTAL CASH IN</td>
                <td class="text-right">{{ toNumber($total_all_cash_in) }}</td>
                <td class="text-right">{{ ($total_all_cash_in && $total_cash_in) > 0 ? round(($total_all_cash_in/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
            </tr>
            <tr>
                <td>CASH OUT</td>
            </tr>
            @php
                $total_all_cash_out = 0;
            @endphp
            @foreach ($head as $value)
                @if ($value->tipe == '2')
                    <tr>
                        <td>{{ $value->nama_cashflow }}</td>
                    </tr>
                    @php
                        $sub_total = 0;
                    @endphp
                    @if (isset($child[$value->id_cf]))
                        @foreach ($child[$value->id_cf] as $value2)
                            @php
                                $total_in = 0;
                                $total_out = 0;
                                if (isset($cashflow[$value2->id_cf])) {
                                    foreach ($cashflow[$value2->id_cf] as $index) {
                                        if (isset($cashout[$index])) {
                                            foreach ($cashout[$index] as $item) {
                                                $total_in += $item->nominal;
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <tr>
                                <td style="padding-left: 100px">{{ $value2->nama_cashflow }}</td>
                                <td class="text-right">{{ toNumber($total_in) }}</td>
                                <td class="text-right">{{ ($total_in && $total_cash_in) > 0 ? round(($total_in/($total_cash_in)) * 100,2) : 0 }} %</td>
                            </tr>
                            @php
                                $sub_total += $total_in;
                            @endphp
                        @endforeach
                    @endif
                    <tr>
                        <td class="text-center">Sub Total {{ $value->nama_cashflow }}</td>
                        <td class="text-right">{{ toNumber($sub_total) }}</td>
                        <td class="text-right">{{ ($sub_total && $total_cash_in) > 0 ? round(($sub_total/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
                    </tr>
                    @php
                        $total_all_cash_out += $sub_total;
                    @endphp
                @endif
            @endforeach
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td class="text-center">TOTAL CASH OUT</td>
                <td class="text-right">{{ toNumber($total_all_cash_out) }}</td>
                <td class="text-right">{{ ($total_all_cash_out && $total_cash_in) > 0 ? round(($total_all_cash_out/ceil($total_cash_in)) * 100,2) : 0 }} %</td>
            </tr>
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td class="text-center">Surplus / Defisit Setelah Investasi</td>
                <td class="text-right">{{ toNumber($total_all_cash_in - $total_all_cash_out) }}</td>
                <td></td>
            </tr>
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td class="text-center">Saldo Awal</td>
                <td class="text-right">{{ toNumber($saldo_awal) }}</td>
                <td></td>
            </tr>
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td class="text-center">Saldo Akhir</td>
                <td class="text-right">{{ toNumber($saldo_awal + ($total_all_cash_in - $total_all_cash_out)) }}</td>
                <td></td>
            </tr>
        </table>
    </div>
    </body>
    
    </html>
    