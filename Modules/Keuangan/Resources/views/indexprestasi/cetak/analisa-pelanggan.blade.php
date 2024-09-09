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
    <title>Cetak Analisa Pelanggan | Lsj Express Group</title>
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
            font-size : 11px;
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
        .atas{
            line-height : 5px;
        }
        li {
            font-size: 10px;
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
    <div class="atas">
        <p class="text-center"><b>LAPORAN ANALISA PELANGGAN {{ strtoupper($perusahaan->nm_perush) }}</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Marketing</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">STT</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Koli</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Omset</th>
                    <th colspan="3" class="text-center">Pelanggan</th>
                    <th colspan="8" class="text-center">Jenis</th>
                </tr>
                <tr>
                    <th class="text-center">Aktif</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center"> % </th>
                    <th class="text-center">Baru</th>
                    <th class="text-center"> % </th>
                    <th class="text-center"> Omset Baru </th>
                    <th class="text-center"> % </th>
                    <th class="text-center">Reorder</th>
                    <th class="text-center"> % </th>
                    <th class="text-center"> Omset Reorder </th>
                    <th class="text-center"> % </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_stt = 0;
                    $total_koli = 0;
                    $total_omset = 0;
                    $total_jumlah = 0;
                    $total_aktif = 0;
                    $total_baru = 0;
                    $total_reorder = 0;
                    $total_omset_baru = 0;
                    $total_omset_reorder = 0;
                @endphp
                @foreach ($data as $key => $value)
                    <tr>
                        <td class="text-center">{{ $key += 1 }}</td>
                        <td class="text-center">{{ !empty($value->nm_marketing) ? strtoupper($value->nm_marketing) : 'DATANG SENDIRI' }}</td>
                        <td class="text-center">{{ $value->total_stt }}</td>
                        <td class="text-center">{{ $value->koli }}</td>
                        <td class="text-right">{{ number_format($value->omset, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $value->aktif }}</td>
                        <td class="text-center">{{ $value->jumlah }}</td>
                        <td class="text-center">
                            @if (($value->aktif and $value->jumlah) > 0)
                                {{ round(($value->jumlah / $value->aktif) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-center">{{ $value->baru }}</td>
                        <td class="text-center">
                            @if (($value->baru and $value->jumlah) > 0)
                                {{ round(($value->baru / $value->jumlah) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-right">{{ toNumber($value->omset_baru) }}</td>
                        <td class="text-center">
                            @if (($value->omset and $value->omset_baru) > 0)
                                {{ round(($value->omset_baru / $value->omset) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-center">{{ $value->reorder }} </td>
                        <td class="text-center">
                            @if (($value->reorder and $value->aktif) > 0)
                                {{ round(($value->reorder / $value->jumlah) * 100, 2) }} %
                            @endif
                        </td>
                        <td class="text-right">{{ toNumber($value->omset_reorder) }} </td>
                        <td class="text-center">
                            @if (($value->omset_reorder and $value->omset) > 0)
                                {{ round(($value->omset_reorder / $value->omset) * 100, 2) }} %
                            @endif
                        </td>
                    </tr>
                    @php
                        $total_stt += $value->total_stt;
                        $total_koli += $value->koli;
                        $total_omset += $value->omset;
                        $total_jumlah += $value->jumlah;
                        $total_aktif += $value->aktif;
                        $total_baru += $value->baru;
                        $total_reorder += $value->reorder;
                        $total_omset_baru += $value->omset_baru;
                        $total_omset_reorder += $value->omset_reorder;
                    @endphp
                @endforeach
                <tr>
                    <th colspan = "2" class="text-center">Total Pelanggan Unik : {{ $pelanggan_unik['unik'] }}, dari
                        {{ $pelanggan_unik['unik_aktif'] }} aktif</th>
                    <th class="text-center">{{ $total_stt }}</th>
                    <th class="text-center">{{ $total_koli }}</th>
                    <th class="text-right">{{ number_format($total_omset, 0, ',', '.') }}</th>
                    <th class="text-center">{{ $total_aktif }}</th>
                    <th class="text-center">{{ $total_jumlah }}</th>
                    <th></th>
                    <th class="text-center">{{ $total_baru }}</th>
                    <th class="text-center">
                        @if (($total_baru and $total_jumlah) > 0)
                            {{ round(($total_baru / $total_jumlah) * 100, 2) }} %
                        @endif
                    </th>
                    <th class="text-right">{{ toNumber($total_omset_baru) }}</th>
                    <th class="text-center">
                        @if (($total_omset_baru and $total_omset) > 0)
                            {{ round(($total_omset_baru / $total_omset) * 100, 2) }} %
                        @endif
                    </th>
                    <th class="text-center">{{ $total_reorder }}</th>
                    <th class="text-center">
                        @if (($total_reorder and $total_jumlah) > 0)
                            {{ round(($total_reorder / $total_jumlah) * 100, 2) }} %
                        @endif
                    </th>
                    <th class="text-right">{{ toNumber($total_omset_reorder) }}</th>
                    <th>
                        @if (($total_omset_reorder and $total_omset) > 0)
                            {{ round(($total_omset_reorder / $total_omset) * 100, 2) }} %
                        @endif
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
    <ul>
        <li>Baru, Jika -12 Bulan kebelakang tidak pernah melakukan pengiriman dari tanggal kirim pada range periode</li>
        <li>Reorder, Jika -12 Bulan kebelakang ada melakukan pengiriman dari tanggal kirim pada range periode</li>
        <li>Aktif, dihitung -36 Bulan kebelakang dari tanggal akhir periode</li>
        <li>Pelanggan Unik, adalah pelanggan unik selama range periode, mengabaikan grouping dari marketing</li>
        <li>Aktif Unik, adalah pelanggan unik dari periode akhir hingga -36 bulan kebelakang</li>
    </ul>
    </body>
    
    </html>
    