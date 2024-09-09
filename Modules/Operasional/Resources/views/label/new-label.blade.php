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
    <title>Cetak STT | Lsj Express Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style type="text/css">
        html { margin: 0px}
        body{
            margin: 5px;
            font-family: Tahoma !important;
        }
        .table1 {
            font-size: 12px;
            font-family: Tahoma;
            border-collapse: collapse;
            width: 100%;
        }
        .table1 tr th{
            font-size: 4px;
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
            font-size: 5px;
        }

        .table2{
            margin-top: -5px;
            font-size: 9pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @php
    $total = count($data);
    @endphp
    @foreach($data as $key => $value)
    <table class="table1">
        <tr>
            <td height="30px">
                @php
                if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                    $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                    $full_path = Storage::path($path);
                    $base64 = base64_encode(Storage::get($path));
                    $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                    $perusahaan->logo = $image;
                }
                @endphp
                <img src="{{ $perusahaan->logo }}" style="height: 30px">
            </td>
            <td colspan="2">{!! DNS1D::getBarcodeHTML($value->kode_stt, 'C128', 1,33) !!}</td>
        </tr>
        <tr>
            <td>Pengirim :</td>
            <td colspan="2" rowspan="2" style="font-size:19px; text-align:center">
                @if (isset($perusahaan->kode_perush) and isset($perush_tj->kode_perush))
                <b>{{ $perusahaan->kode_perush." - ".$perush_tj->kode_perush }}</b>
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2">
                @if(isset($value->pengirim_nm))
                {{ $value->pengirim_nm }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:12px">
                <b>
                    @if(isset($kode_stt->asal->nama_wil))
                    {{ $kode_stt->asal->nama_wil }}
                    @endif
                </b>
            </td>
            <td rowspan="4" style="font-size:50px;"><b>{{$key+1}}/{{$total}}</b></td>
        </tr>
        <tr><td></td><td></td><td></td></tr>
        <tr>
            <td>Penerima : </td>
        </tr>
        <tr>
            <td>
                @if(isset($kode_stt->penerima_nm))
                {{ $kode_stt->penerima_nm }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:12px">
                <b>
                    @if(isset($kode_stt->tujuan->nama_wil))
                    {{ $kode_stt->tujuan->nama_wil }}
                    @endif
                </b>
            </td>
            <td style="font-size:12px; text-align:center">
                @if(isset($kode_stt->kode_stt))
                {{ $kode_stt->kode_stt }}
                @endif
            </td>
        </tr>
    </table>
    <div class="page-break"></div>
    @endforeach
</body>
</html>
