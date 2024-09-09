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
    <title>Cetak Rekap Gaji | Lsj Express Group</title>
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
                    <h3><span style="font-size: 14px">Rekap Gaji Karyawan</span><br>
                        Periode : {{ $filter['from_year'] . ' - ' . $filter['to_year'] }}</h3>
                </td>
            </tr>
        </table>
        @php
            $kolom = [
                'gaji' => 'Gaji Pokok',
                'n_tunjangan_jabatan' => 'Tunjangan Jabatan',
                'n_tunjangan_kinerja' => 'Tunjangan Kinerja',
                'n_tunjangan_kpi' => 'KPI',
                'n_tunjangan_kesehatan' => 'Tunj. BPJS Kesehatan',
                'n_tunjangan_jht' => 'JHT',
                'n_tunjangan_jkk' => 'JKK',
                'n_tunjangan_jkm' => 'JKM',
                'n_tunjangan_jp' => 'JP',
                'n_potongan_pph' => 'PPH 21',
                'n_potongan_kesehatan' => 'Potongan Kesehatan',
                'n_potongan_jht' => 'Potngan JHT',
                'n_potongan_jp' => 'Potngan JP',
                'n_denda' => 'Absensi Kehadiran',
            ];
        @endphp
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Komponen</th>
                    @foreach ($tahun as $item)
                        <th colspan="{{ count($bulan[$item])*2 }}" class="text-center">{{ $item }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    @foreach ($tahun as $item)
                        @isset($bulan[$item])
                            @foreach ($bulan[$item] as $value)
                                <th class="text-center">{{ $value }}</th>
                                <th>%</th>
                            @endforeach
                        @endisset
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($kolom as $key => $item)
                    <tr>
                        <td>{{ $item }}</td>
                        @php
                            $lastGaji = 0;
                        @endphp
                        @foreach ($tahun as $item)
                            @isset($bulan[$item])
                                @foreach ($bulan[$item] as $value)
                                    @php
                                        $currentGaji = isset($data[$item][$value]) ? $data[$item][$value]->$key : 0;
                                    @endphp
                                    <td class="text-right">
                                        {{ toNumber($currentGaji) }}
                                    </td>
                                    @php
                                        $diffGaji = $currentGaji - $lastGaji;
                                        $prosentase =
                                            $diffGaji != 0 && $currentGaji != 0
                                                ? round(($diffGaji / $currentGaji) * 100, 2)
                                                : 0;
                                        $lastGaji = $currentGaji;
                                    @endphp
                                    <td>{{ $prosentase }}</td>
                                @endforeach
                            @endisset
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
