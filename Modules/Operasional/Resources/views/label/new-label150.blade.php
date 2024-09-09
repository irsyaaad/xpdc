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
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style type="text/css">
        html {
            margin: 0px
        }

        body {
            margin: 5px;
            font-family: sans-serif !important;
        }

        .table1 {
            margin-left: 10px;
            margin-top: 10px;
            font-size: 12px;
            font-family: sans-serif !important;
            border-collapse: collapse;
            width: 100%;
            border-spacing: 0;
        }

        .table1 tr th {
            font-size: 4px;
            background: grey;
            color: #fff;
            font-weight: bold;
        }

        .table1,
        th,
        td {
            text-align: left;
            padding: 0px;
            /* border: 1px solid black; */
            padding: 0;
            margin: 0;
            border-spacing: 0;
            font-size: 14px;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            font-weight: 80;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-title {
            margin-left: 10pt;
        }

        .heading {
            font-size: 5px;
        }

        .table2 {
            margin-top: -5px;
            font-size: 9pt;
            font-family: sans-serif !important;
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
    @foreach ($data as $key => $value)
        <table class="table1">
            <tr>
                <td rowspan="3" width="72px">
                    @php
                        switch ($perusahaan->id_perush) {
                            case 32:
                                $path = 'public/uploads/perusahaan/po.png';
                                break;
                            case 39:
                                $path = 'public/uploads/perusahaan/papua.png';
                                break;
                            default:
                                $path = 'public/uploads/perusahaan/po.png';
                                break;
                        }
                        if (Storage::exists($path)) {
                            $path = $path;

                            $full_path = Storage::path($path);
                            $base64 = base64_encode(Storage::get($path));
                            $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                            $perusahaan->logo = $image;
                        }
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="height: 220px;">
                </td>
                <td colspan="3" style="border-bottom: 1px solid black">
                    <div style="line-height: 150%">
                        <span>Pengirim :</span><br>
                        <span
                            style="font-size: {{ isset($value->pengirim_nm) && strlen($value->pengirim_nm) < 20 ? '18px' : '' }}"><b>{{ isset($value->pengirim_nm) ? strtoupper($value->pengirim_nm) : '' }}</b></span><br>
                        <span
                            style="font-size: 18px"><b>{{ isset($kode_stt->asal->nama_wil) ? strtoupper($kode_stt->asal->nama_wil) : '' }}</b></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div>
                        <span>Penerima :</span><br>
                        <span
                            style="font-size: {{ isset($value->pengirim_nm) && strlen($value->pengirim_nm) < 20 ? '18px' : '' }}"><b>{{ isset($kode_stt->penerima_nm) ? strtoupper($kode_stt->penerima_nm) : '' }}</b></span><br>
                        <span
                            style="font-size: 18px">{{ isset($kode_stt->tujuan->nama_wil) ? strtoupper($kode_stt->tujuan->nama_wil) : '' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 40px"><b>{{ $key + 1 }}/{{ $total }}</b></span>
                </td>
                <td></td>
                <td>
                    <span style="font-size: 25px"><b>{{ $kode_stt->kode_stt }}</b></span><br>
                    <span>{!! DNS1D::getBarcodeHTML($value->kode_stt, 'C128', 1, 33) !!}</span><br>
                </td>
            </tr>
        </table>
        @if ($key + 1 < count($data))
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
