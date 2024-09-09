<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Daftar Gaji {{ Session('perusahaan')['nm_perush'] }} - {{ date('ym', strtotime($tahun . '-' . $bulan)) }}
    </title>
    <style>
        @media print {
            @page {
                size: A4 landscape;
            }
        }

        body {
            font-family: Tahoma !important;
            font-size: 9px;
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
    <?php
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=Daftar Gaji' . date('ym', strtotime($tahun . '-' . $bulan)) . '.xls');
    ?>
</head>

<body class="container">
    <div class="container" style=" margin-top:10px;">
        <div class="row">
            <div class="col-3">
                <center>
                    @php

                        if (Storage::exists('public/uploads/perusahaan/' . $perusahaan->logo)) {
                            $path = 'public/uploads/perusahaan/' . $perusahaan->logo;

                            $full_path = Storage::path($path);
                            $base64 = base64_encode(Storage::get($path));
                            $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                            $perusahaan->logo = $image;
                        }
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="width: 120px">
                </center>
            </div>
            <div class="col-8">

                <h5 class="text-center">{{ $perusahaan->nm_perush }}</h5>
                <h6 class="text-center">{{ $perusahaan->alamat }},
                    {{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</h6>
                <h6 class="text-center">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</h6>

            </div>
        </div>
    </div>
    <div class="container" style="margin-top:20px">
        <hr>
        <div>
            <h6>Perusahaan / Devisi : {{ $perusahaan->nm_perush }}</h6>
            <h6>Periode Gaji : {{ date('M Y', strtotime($tahun . '-' . $bulan)) }}</h6>
        </div>
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
                                {{ $value->n_gaji }}
                            @endif
                        </td>
                        @foreach ($tunjangan as $key => $item)
                            <td class="text-right">{{ isset($value->$key) ? $value->$key : 0 }}</td>
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
                            <td class="text-right">{{ isset($value->$key) ? $value->$key : 0 }}</td>
                            @php
                                $total_potongan += isset($value->$key) ? $value->$key : 0;
                                $total[$key] += isset($value->$key) ? $value->$key : 0;
                            @endphp
                        @endforeach
                        <td class="text-right">
                            {{ $value->n_gaji + $total_tunjangan - $total_potongan }}
                            @php
                                $total_thp += $value->n_gaji + $total_tunjangan - $total_potongan;
                            @endphp
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                    <td colspan="4" class="text-center">TOTAL</td>
                    <td class="text-right">{{ $total_gaji }}</td>
                    @foreach ($tunjangan as $key => $item)
                        <td class="text-right">{{ isset($total[$key]) ? $total[$key] : 0 }}</td>
                    @endforeach
                    @foreach ($potongan as $key => $item)
                        <td class="text-right">{{ isset($total[$key]) ? $total[$key] : 0 }}</td>
                    @endforeach
                    <td class="text-right">{{ $total_thp }}</td>
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

<script>
    $("#cetak").click(function() {
        $("#tombol").hide();
        window.print();
    });
</script>
