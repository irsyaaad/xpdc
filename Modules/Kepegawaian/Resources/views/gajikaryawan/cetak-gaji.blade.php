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
    <title>Cetak Gaji | Lsj Express Group</title>
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
            font-size: 9px;
            padding: 3px !important;
        }

        td {
            font-size: 9px;
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 5px;
            padding-right: 5px;
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

        .tr-bold {
            font-weight: bold !important;
        }

        .noBorder {
            border: none !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <table width="100%" class="noBorder">
            <tr width="30%">
                <td rowspan="3" style="text-align: center;" class="noBorder">
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
                <td class="heading noBorder">
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
        <table width="100%" class="noBorder">
            <tr>
                <td class="text-center noBorder">
                    <h3><span style="font-size: 14px">Detail Gaji Karyawan</span><br>
                        Periode : {{ $bulan . ' - ' . $tahun }}</h3>
                </td>
            </tr>
        </table>
        @php
            $tunjangan = array_merge($tunjangan, $tunj_nonthp);
            $potongan = $potongan;
        @endphp
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Karyawan </th>
                    <th rowspan="2">Golongan / Pangkat </th>
                    <th rowspan="2">Jabatan </th>
                    <th rowspan="2">Gaji Pokok</th>
                    <th colspan="{{ count($tunjangan) }}">
                        <center>Tunjangan</center>
                    </th>
                    <th colspan="{{ count($potongan) }}">
                        <center>Potongan</center>
                    </th>
                    <th rowspan="2">Gaji Diterima (THP)</th>
                </tr>
                <tr>
                    @foreach ($tunjangan as $key => $item)
                        <th>{{ $item }}</th>
                    @endforeach
                    @foreach ($potongan as $key => $item)
                        <th>{{ $item }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $total_gaji = 0;
                    $total_thp = 0;
                    foreach ($tunjangan as $key => $item) {
                        $total[$key] = 0;
                    }
                    foreach ($potongan as $key => $item) {
                        $total[$key] = 0;
                    }
                @endphp
                @foreach ($data as $key => $value)
                    @php
                        $total_gaji += $value->n_gaji;
                        $total_tunjangan = 0;
                        $total_potongan = 0;
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            @if (isset($value->id_karyawan))
                                {{ ucwords(strtolower($value->nm_karyawan)) }}
                            @endif
                        </td>
                        <td>{{ isset($value->golongan) ? $value->golongan . ' / ' : '' }}
                            {{ isset($value->pangkat) ? $value->pangkat : '' }}
                            ({{ isset($value->nm_jenis) ? $value->nm_jenis : '' }})</td>
                        <td>{{ isset($value->nm_jabatan) ? $value->nm_jabatan : '' }}</td>
                        @php
                        @endphp
                        <td class="text-right">
                            @if (isset($value->n_gaji))
                                {{ toNumber($value->n_gaji) }}
                            @endif
                        </td>
                        @foreach ($tunjangan as $key => $item)
                            <td class="text-right">{{ isset($value->$key) ? toNumber($value->$key) : 0 }}</td>
                            @php
                                if (
                                    isset($value->$key) &&
                                    in_array($key, ['n_tunjangan_jabatan', 'n_tunjangan_kinerja', 'n_tunjangan_kpi'])
                                ) {
                                    $total_tunjangan += $value->$key;
                                } else {
                                    $total_tunjangan += 0;
                                }
                                $total[$key] += isset($value->$key) ? $value->$key : 0;
                            @endphp
                        @endforeach
                        @foreach ($potongan as $key => $item)
                            <td class="text-right">{{ isset($value->$key) ? toNumber($value->$key) : 0 }}</td>
                            @php
                                $total_potongan += isset($value->$key) ? $value->$key : 0;
                                $total[$key] += isset($value->$key) ? $value->$key : 0;
                            @endphp
                        @endforeach
                        <td class="text-right">
                            {{ toNumber($value->n_gaji + $total_tunjangan - $total_potongan) }}
                            @php
                                $total_thp += $value->n_gaji + $total_tunjangan - $total_potongan;
                            @endphp
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    <td colspan="4" class="text-center">TOTAL</td>
                    <td class="text-right">{{ toNumber($total_gaji) }}</td>
                    @foreach ($tunjangan as $key => $item)
                        <td class="text-right">{{ isset($total[$key]) ? toNumber($total[$key]) : 0 }}</td>
                    @endforeach
                    @foreach ($potongan as $key => $item)
                        <td class="text-right">{{ isset($total[$key]) ? toNumber($total[$key]) : 0 }}</td>
                    @endforeach
                    <td class="text-right">{{ toNumber($total_thp) }}</td>
                </tr>
                @if (count($data) < 1)
                    <tr>
                        <td colspan="14">
                            <center><b>Gaji Belum Di Generate</b></center>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
