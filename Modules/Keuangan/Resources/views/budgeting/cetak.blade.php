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
    <title>Cetak Budgeting | Lsj Express Group</title>
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
    
    <table width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th rowspan="2" >No</th>
                <th rowspan="2" >Nama Acount</th>
                <th colspan="4">{{ $filter['bulan_kemarin'] }}</th>
                <th colspan="4">{{ $filter['bulan_ini'] }}</th>
                <th colspan="4">{{ $filter['bulan_depan'] }}</th>
            </tr>
            <tr>
                <th>Budget</th>
                <th>Realisasi</th>
                <th>Selisih</th>
                <th>%</th>

                <th>Budget</th>
                <th>Realisasi</th>
                <th>Selisih</th>
                <th>%</th>

                <th>Budget</th>
                <th>Realisasi</th>
                <th>Selisih</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_budget_bulan_kemarin = 0;
                $total_realisasi_bulan_kemarin = 0;
                $total_selisih_bulan_kemarin = 0;
                
                $total_budget_bulan_ini = 0;
                $total_realisasi_bulan_ini = 0;
                $total_selisih_bulan_ini = 0;
                
                $total_budget_bulan_depan = 0;
                $total_realisasi_bulan_depan = 0;
                $total_selisih_bulan_depan = 0;
            @endphp
            @foreach ($ac as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{$item->nama}}</td>
                    {{-- Bulan Kemarin --}}
                    <td class="text-right">
                        {{ isset($budgeting_bulan_kemarin[$item->id_ac]) ? number_format($budgeting_bulan_kemarin[$item->id_ac], 0, ',', '.') : '0' }}
                        @php
                            $total_budget_bulan_kemarin += isset($budgeting_bulan_kemarin[$item->id_ac]) ? $budgeting_bulan_kemarin[$item->id_ac] : 0;
                        @endphp
                    </td>
                    <td class="text-right">
                        @php
                            $total = 0;
                        @endphp
                        @if (isset($bulanKemarin['debit'][$item->id_ac]) && isset($bulanKemarin['kredit'][$item->id_ac]))  
                            @if ($item->def_pos == 'D')                                
                                {{ number_format($bulanKemarin['debit'][$item->id_ac]-$bulanKemarin['kredit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanKemarin['debit'][$item->id_ac]-$bulanKemarin['kredit'][$item->id_ac];
                                @endphp
                            @endif
                            @if ($item->def_pos == 'K')
                                {{ number_format($bulanKemarin['kredit'][$item->id_ac]-$bulanKemarin['debit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanKemarin['kredit'][$item->id_ac]-$bulanKemarin['debit'][$item->id_ac];
                                @endphp
                            @endif
                            @php
                                $total_realisasi_bulan_kemarin += $total;
                            @endphp
                        @endif                        
                    </td>
                    <td class="text-right">{{ isset($budgeting_bulan_kemarin[$item->id_ac]) ? toNumber($budgeting_bulan_kemarin[$item->id_ac]-$total) : '0' }}</td>
                        @php
                            $total_selisih_bulan_kemarin += isset($budgeting_bulan_kemarin[$item->id_ac]) ? $budgeting_bulan_kemarin[$item->id_ac]-$total : 0;
                        @endphp
                    <td class="text-right">{{ (isset($budgeting_bulan_kemarin[$item->id_ac]) && $budgeting_bulan_kemarin[$item->id_ac] > 0 && $total > 0 ) ? round(($total / $budgeting_bulan_kemarin[$item->id_ac]) * 100, 2) : 0}}</td>

                    {{-- Sekarang --}}
                    <td class="text-right">{{ isset($budgeting_bulan_ini[$item->id_ac]) ? number_format($budgeting_bulan_ini[$item->id_ac], 0, ',', '.') : '0' }}</td>
                        @php
                            $total_budget_bulan_ini += isset($budgeting_bulan_ini[$item->id_ac]) ? $budgeting_bulan_ini[$item->id_ac] : 0;
                        @endphp
                    <td class="text-right">
                        @php
                            $total = 0;
                        @endphp
                        @if (isset($bulanIni['debit'][$item->id_ac]) && isset($bulanIni['kredit'][$item->id_ac]))  
                            @if ($item->def_pos == 'D')                                
                                {{ number_format($bulanIni['debit'][$item->id_ac]-$bulanIni['kredit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanIni['debit'][$item->id_ac]-$bulanIni['kredit'][$item->id_ac];
                                @endphp
                            @endif
                            @if ($item->def_pos == 'K')
                                {{ number_format($bulanIni['kredit'][$item->id_ac]-$bulanIni['debit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanIni['kredit'][$item->id_ac]-$bulanIni['debit'][$item->id_ac];
                                @endphp
                            @endif
                            @php
                                $total_realisasi_bulan_ini += $total;
                            @endphp
                        @endif                        
                    </td>
                    <td class="text-right">{{ isset($budgeting_bulan_ini[$item->id_ac]) ? toNumber($budgeting_bulan_ini[$item->id_ac]-$total) : '0' }}</td>
                        @php
                            $total_selisih_bulan_ini += isset($budgeting_bulan_ini[$item->id_ac]) ? $budgeting_bulan_ini[$item->id_ac]-$total : 0;
                        @endphp
                    <td class="text-right">{{ (isset($budgeting_bulan_ini[$item->id_ac]) && $budgeting_bulan_ini[$item->id_ac] > 0 && $total > 0 ) ? round(($total / $budgeting_bulan_ini[$item->id_ac]) * 100, 2) : 0}}</td>

                    {{-- Bulan Depan --}}
                    <td class="text-right">{{ isset($budgeting_bulan_depan[$item->id_ac]) ? number_format($budgeting_bulan_depan[$item->id_ac], 0, ',', '.') : '0' }}</td>
                        @php
                            $total_budget_bulan_depan += isset($budgeting_bulan_depan[$item->id_ac]) ? $budgeting_bulan_depan[$item->id_ac] : 0;
                        @endphp
                    <td class="text-right">
                        @php
                            $total = 0;
                        @endphp
                        @if (isset($bulanDepan['debit'][$item->id_ac]) && isset($bulanDepan['kredit'][$item->id_ac]))  
                            @if ($item->def_pos == 'D')                                
                                {{ number_format($bulanDepan['debit'][$item->id_ac]-$bulanDepan['kredit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanDepan['debit'][$item->id_ac]-$bulanDepan['kredit'][$item->id_ac];
                                @endphp
                            @endif
                            @if ($item->def_pos == 'K')
                                {{ number_format($bulanDepan['kredit'][$item->id_ac]-$bulanDepan['debit'][$item->id_ac], 0, ',', '.') }}
                                @php
                                    $total = $bulanDepan['kredit'][$item->id_ac]-$bulanDepan['debit'][$item->id_ac];
                                @endphp
                            @endif
                            @php
                                $total_realisasi_bulan_depan += $total;
                            @endphp
                        @endif                        
                    </td>
                    <td class="text-right">{{ isset($budgeting_bulan_depan[$item->id_ac]) ? toNumber($budgeting_bulan_depan[$item->id_ac]-$total) : '0' }}</td>
                        @php
                            $total_selisih_bulan_depan += isset($budgeting_bulan_depan[$item->id_ac]) ? $budgeting_bulan_depan[$item->id_ac]-$total : 0;
                        @endphp
                    <td class="text-right">{{ (isset($budgeting_bulan_depan[$item->id_ac]) && $budgeting_bulan_depan[$item->id_ac] > 0 && $total > 0 ) ? round(($total / $budgeting_bulan_depan[$item->id_ac]) * 100, 2) : 0}}</td>

                </tr>
            @endforeach
            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                <td colspan="2" class="text-center">Grand Total</td>
                <td class="text-right">{{ toNumber($total_budget_bulan_kemarin) }}</td>
                <td class="text-right">{{ toNumber($total_realisasi_bulan_kemarin) }}</td>
                <td class="text-right">{{ toNumber($total_selisih_bulan_kemarin) }}</td>
                <td class="text-right">{{ ($total_budget_bulan_kemarin && $total_realisasi_bulan_kemarin) > 0 ? round(($total_realisasi_bulan_kemarin / $total_budget_bulan_kemarin) * 100, 2) : 0 }}</td>
                <td class="text-right">{{ toNumber($total_budget_bulan_ini) }}</td>
                <td class="text-right">{{ toNumber($total_realisasi_bulan_ini) }}</td>
                <td class="text-right">{{ toNumber($total_selisih_bulan_ini) }}</td>
                <td class="text-right">{{ ($total_budget_bulan_ini && $total_realisasi_bulan_ini) > 0 ? round(($total_realisasi_bulan_ini / $total_budget_bulan_ini) * 100, 2) : 0 }}</td>
                <td class="text-right">{{ toNumber($total_budget_bulan_depan) }}</td>
                <td class="text-right">{{ toNumber($total_realisasi_bulan_depan) }}</td>
                <td class="text-right">{{ toNumber($total_selisih_bulan_depan) }}</td>
                <td class="text-right">{{ ($total_budget_bulan_depan && $total_realisasi_bulan_depan) > 0 ? round(($total_realisasi_bulan_depan / $total_budget_bulan_depan) * 100, 2) : 0 }}</td>
            </tr>
        </tbody>
    </table>
    </body>

    </html>
