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
    <title>Cetak Managerial | Lsj Express Group</title>
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
            font-size : 10px;
            padding: 2px 2px 2px 2px !important;
        }

        td {
            font-size : 10px;
            padding: 2px 2px 2px 2px !important;
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
    
    <div class="container">
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode STT</th>
                    <th>Nama Pengirim</th>
                    <th>Kgv</th>
                    <th>Kg</th>
                    <th>M3</th>
                    <th>Koli</th>
                    <th>Cek</th>
                    <th>Omset</th>
                    <th>Bayar</th>
                    <th>Piutang</th>
                    <th>Nama Marketing</th>
                    <th>Kode DM</th>
                    <th>Tgl DM Berangkat</th>
                    <th>Tgl DM Tiba</th>
                    <th>Tgl STT Sampai</th>
                    <th>Tgl Klaim</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr style="background-color: rgb(221, 218, 218)">
                        <td colspan="26">{{ dateindo($key) }}</td>
                    </tr>
                    @foreach ($data[$key] as $index => $item)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $item->kode_stt }}</td>
                            <td>{{ $item->pengirim_nm }}</td>
                            <td>{{ $item->n_volume }}</td>
                            <td>{{ $item->n_berat }}</td>
                            <td>{{ $item->n_kubik }}</td>
                            <td>{{ $item->n_koli }}</td>
                            <td style="border: 1px solid black"></td>
                            <td class="text-right" >{{ toNumber($item->c_total) }}</td>
                            <td class="text-right">{{ toNumber($item->bayar) }}</td>
                            <td class="text-right">{{ toNumber($item->c_total-$item->bayar) }}</td>
                            <td>{{ $item->nm_marketing }}</td>
                            <td>{{ $item->kode_dm }}</td>
                            <td>{{ !empty($item->tgl_berangkat) ? dateindo($item->tgl_berangkat) : ''}}</td>
                            <td>{{ !empty($item->tgl_sampai) ? dateindo($item->tgl_sampai) : '' }}</td>
                            <td>{{ !empty($item->tgl_tiba) ? dateindo($item->tgl_tiba) : '' }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    </body>
    
    </html>
    