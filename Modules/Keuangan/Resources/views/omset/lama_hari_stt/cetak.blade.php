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
    <title>Cetak Lama Hari STT | Lsj Express Group</title>
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
            margin: 15px
        }

        body {
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

        .table1 tr th {
            background: grey;
            color: #fff;
            font-weight: bold;
        }

        .table1,
        th,
        td {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-title {
            margin-left: 10pt;
        }

        .heading {
            text-align: center;
            padding-top: 10px;
            line-height: 15px;
        }

        .table2 {

            margin-top: -5px;
            font-size: 8pt;
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
        }

        .text-body {
            font-size: 7pt !important;
        }

        .stt {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-top: -40px;
            text-decoration: underline;
        }

        .isi {
            padding: 20px;
        }

        .table-isi {
            font-size: 12px;
        }

        .isi-content {
            border-bottom: 1px solid black;
        }

        .footer {
            padding: 20px;
        }

        .table-footer {
            font-size: 12px;
            text-align: center;
        }

        th {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size: 12px;
        }

        td {
            font-size: 10px;
            border: 1px solid black;
            padding-left: 10px;
        }

        .t {
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 10px;
            font-size: 10px;
        }

        .head {
            font-size: 18px;
            font-weight: bold;
            height: 50px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .heading {
            text-align: center;
            font-size: 14px;
        }

        .kepada {
            margin-top: -40px;
            line-height: 10px;
        }

        .kepada td {
            font-size: 12px;
        }

        .hr {
            border-top: 1px solid red;
            margin-top: 10px;
        }

        .headnote {
            border-top: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
            text-align: left;
            font-size: 10px;
        }

        .headnote td {
            padding-left: 10px;
        }

        .note {
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-collapse: collapse;
            text-align: left;
            font-size: 10px;
            height: 50px;
        }

        .hrhead {
            border: 1px solid black;
        }

        .penutup td {
            font-size: 12px;
        }

        .setelah-garis p {
            font-size: 12px;
        }

        .atas {
            line-height: 1px;
        }

        .no-border {
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <table width="100%">
            <tr width="30%">
                <td class="no-border" rowspan="3" style="text-align: center;">
                    @php

                        if (Storage::exists('public/uploads/perusahaan/' . $perusahaan->logo)) {
                            $path = 'public/uploads/perusahaan/' . $perusahaan->logo;

                            $full_path = Storage::path($path);
                            $base64 = base64_encode(Storage::get($path));
                            $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                            $perusahaan->logo = $image;
                        }

                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="height: 50px; margin-top:-20px">
                </td>
                <td class="heading no-border">
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
        <p class="text-center"><b>LAPORAN LAMA HARI STT {{ strtoupper($perusahaan->nm_perush) }}</b></p>
        <p class="text-center"><b>Periode : {{ dateindo($filter['dr_tgl']) }} s/d {{ dateindo($filter['sp_tgl']) }}</b>
        </p>
    </div>
    <div class="container">
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <th>#</th>
                <th>No STT</th>
                <th>Smp</th>
                <th>STT Kirim</th>
                <th>Krm</th>
                <th>STT Kembali</th>
                <th>Kmb</th>
                <th>Tgl Invoice</th>
                <th>No Invoice</th>
                <th>Inv</th>
                <th>Tgl Bayar</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Piutang</th>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border" colspan=2>No DM</td>
                        <td class="no-border" colspan=3>{{ isset($value->kode_dm) ? $value->kode_dm : '-' }}</td>
                        <td class="no-border" colspan=2>Nama Sopir</td>
                        <td class="no-border" colspan=6>{{ isset($value->nm_sopir) ? $value->nm_sopir : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border" colspan=2>Cab Tujuan</td>
                        <td class="no-border" colspan=3>{{ isset($value->nm_perush) ? $value->nm_perush : $value->nm_ven }}</td>
                        <td class="no-border" colspan=2>Nama Kapal</td>
                        <td class="no-border" colspan=3>{{ isset($value->nm_kapal) ? $value->nm_kapal : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border" colspan=2>Tgl Berangkat</td>
                        <td class="no-border" colspan=3>
                            @if (isset($value->tgl_berangkat))
                                {{ daydate($value->tgl_berangkat) . ', ' . dateindo($value->tgl_berangkat) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="no-border"></td>
                        <td class="no-border" colspan=2>Tgl Tiba</td>
                        <td class="no-border" colspan=3>
                            @if (isset($value->tgl_sampai))
                                {{ daydate($value->tgl_sampai) . ', ' . dateindo($value->tgl_sampai) }}
                            @endif
                        </td>
                    </tr>
                    @php
                        $no = 0;
                    @endphp

                    @foreach ($stt[$value->id_dm] as $key2 => $value2)
                        <tr>
                            <td>{{ $no += 1 }}</td>
                            <td>{{ isset($value2->kode_stt) ? $value2->kode_stt : '-' }}</td>
                            <td>{{ isset($value2->sampai) ? $value2->sampai : '-' }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ isset($value2->tgl_kembali) ? dateindo($value2->tgl_kembali) : '-' }}</td>
                            <td>{{ isset($value2->stt_kembali) ? $value2->stt_kembali : '-' }}</td>
                            <td>{{ isset($value2->tgl) ? dateindo($value2->tgl) : '-' }}</td>
                            <td>{{ isset($value2->kode_invoice) ? $value2->kode_invoice : '-' }}</td>
                            <td>{{ isset($value2->inv) ? $value2->inv : '-' }}</td>
                            @php
                                $tgl = explode(',', $value2->tgl_bayar);
                            @endphp
                            <td>
                                @foreach ($tgl as $index => $item)
                                    {{ $index + 1 . ') ' . $item }} <br>
                                @endforeach
                            </td>
                            <td>{{ isset($value2->c_total) ? toNumber($value2->c_total) : '0' }}</td>
                            <td>
                                @php
                                    $bayar = explode(',', $value2->n_bayar);
                                    foreach ($bayar as $index => $item) {
                                        echo $index + 1 . ') ' . toNumber((int) $item) . '<br>';
                                    }
                                @endphp
                            </td>
                            <td>{{ isset($value2->piutang) ? toNumber($value2->piutang) : '-' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</body>
{{-- {{ dd() }} --}}

</html>
