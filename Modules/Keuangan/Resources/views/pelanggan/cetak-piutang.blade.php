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
    <title>Cetak Detail Transaksi | Lsj Express Group</title>
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
            padding-left: 5px;
            padding-right: 5px;
        }

        a {
            color : black;
            text-decoration : none;
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
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>No Telpn</th>
                <th>Alamat</th>
                <th>Jml Kiriman (STT)</th>
                <th>Jml STT Lunas</th>
                <th>Nominal</th>
                <th>Terbayar</th>
                <th>Sisa</th>
            </thead>
            <tbody>
                @php
                $no = 0;
                @endphp
                @foreach ($group as $key => $value)
                    @isset($data[$value->id_plgn_group])
                    <tr>
                        <td  colspan="9">
                            <b>{{$value->nm_group}}</b>
                        </td>
                    </tr>
                        @php
                            $total_stt = 0;
                            $total_stt_lunas = 0;
                            $total_nominal = 0;
                            $total_terbayar = 0;
                            $total_sisa = 0;
                        @endphp
                        @foreach ($data[$value->id_plgn_group] as $key2 => $value2)
                            <tr>
                                <td class="text-center">{{++$no}}</td>
                                <td ><a href="{{ url(Request::segment(1)."/".$value2->id_pelanggan."/show") }}" target="_blank">{{ strtoupper($value2->nm_pelanggan) }}</a></td>
                                <td >{{$value2->telp}}</td>
                                <td >@if(isset($value2->alamat)){{ strtoupper($value2->alamat) }}@endif</td>
                                <td class="text-right">@if(isset($value2->total_stt)){{$value2->total_stt}}@endif</td>
                                <td class="text-right">@if(isset($value2->total_stt_byr)){{$value2->total_stt_byr}}@endif</td>
                                <td class="text-right">@if(isset($value2->total)) {{ number_format($value2->total, 0, ',', '.') }}@endif</td>
                                <td class="text-right">@if(isset($value2->bayar)) {{ number_format($value2->bayar, 0, ',', '.') }}@endif</td>
                                <td class="text-right">@if(isset($value2->kurang)) {{ number_format($value2->kurang, 0, ',', '.') }}@endif</td>
                            </tr>
                            @php
                                $total_stt += $value2->total_stt;
                                $total_stt_lunas += $value2->total_stt_byr;
                                $total_nominal += $value2->total;
                                $total_terbayar += $value2->bayar;
                                $total_sisa += $value2->kurang;
                            @endphp
                        @endforeach
                        <tr style="background-color: rgb(221, 218, 218); font-weight:bold">
                            <td colspan="4" class="text-center">TOTAL</td>
                            <td class="text-right">{{ $total_stt }}</td>
                            <td class="text-right">{{ $total_stt_lunas }}</td>
                            <td class="text-right">{{ toNumber($total_nominal) }}</td>
                            <td class="text-right">{{ toNumber($total_terbayar) }}</td>
                            <td class="text-right">{{ toNumber($total_sisa) }}</td>
                        </tr>
                    @endisset
                @endforeach
            </tbody>
        </table>
    </div>
</body>
    
</html>
    