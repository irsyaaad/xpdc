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
    <title>Slip Gaji - @if (isset($karyawan->nm_karyawan))
            {{ $karyawan->nm_karyawan }}-{{ date('ym', strtotime($gaji->dr_tgl)) }}
        @endif
    </title>
    <style>
        @media print {
            @page {
                size: 5.5in 9.4in;
                size: portrait;
                /* size: landscape; */
            }
        }

        body {
            font-family: Arial !important;
            font-weight: bold;
            font-size: 13px;
        }
    </style>
</head>

<body class="container">
    <div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        <button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i> Cetak</button>
    </div>
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
    <div>
        <hr style="border: 1px solid black">
        <hr style="overflow: visible; margin-top: -15px;border: 1px solid black">
    </div>
    <div class="container" style="margin-top:-10px">
        @php
            $total_tunjangan = 0;
            $total_potongan = 0;
            $tunjangan = [
                'n_tunjangan_jabatan' => 'Jabatan',
                'n_tunjangan_kinerja' => 'Kinerja',
                'n_tunjangan_kpi' => 'KPI',
                // 'n_tunjangan_kesehatan' => 'Tunj kesehatan',
                // 'n_tunjangan_jht' => 'JHT',
                // 'n_tunjangan_jkk' => 'JKK',
                // 'n_tunjangan_jkm' => 'JKM',
                // 'n_tunjangan_jp' => 'JP',
            ];
            $potongan = [
                'n_potongan_pph' => 'PPH 21',
                'n_potongan_kesehatan' => 'BPJS Kesehatan',
                // 'n_potongan_jht' => 'BPJS Kesehatan',
                'n_potongan_jp' => 'BPJS Ketenagakerjaan',
                'n_denda' => 'Absensi Kehadiran',
                'n_piutang' => 'Piutang Karyawan',
            ];

            foreach ($tunjangan as $key => $value) {
                $total_tunjangan += isset($gaji->$key) ? $gaji->$key : 0;
            }

            foreach ($potongan as $key => $value) {
                $total_potongan += isset($gaji->$key) ? $gaji->$key : 0;
            }
        @endphp
        <table>
            <tr style="font-weight:bold;">
                <td width="10%">Nama</td>
                <td width="5%">:</td>
                <td width="40%">
                    @if (isset($karyawan->nm_karyawan))
                        {{ $karyawan->nm_karyawan }}
                    @endif
                </td>

                <td width="10%">Golongan/Pangkat</td>
                <td width="5%">:</td>
                <td width="35%">
                    @if (isset($karyawan->golongan))
                        {{ strtoupper($karyawan->golongan) }}
                        @endif / @if (isset($karyawan->pangkat))
                            {{ strtoupper($karyawan->pangkat) }}
                        @endif
                </td>
            </tr>
            <tr style="font-weight:bold; padding-top:0px">
                <td>Jabatan</td>
                <td>:</td>
                <td>
                    @if (isset($karyawan->jabatan->nm_jabatan))
                        {{ strtoupper($karyawan->jabatan->nm_jabatan) }}
                    @endif
                </td>

                <td>Bagian</td>
                <td>:</td>
                <td>
                    @if (isset($karyawan->id_jenis))
                        {{ strtoupper($karyawan->jenis->nm_jenis) }}
                    @endif
                </td>
            </tr>
            <tr style="font-weight:bold; padding-top:0px">
                <td>Bulan / Tahun</td>
                <td>:</td>
                <td>
                    {{ $bulan }}
                </td>

                <td>Status</td>
                <td>:</td>
                <td>
                    @if (isset($karyawan->status_karyawan->nm_status_karyawan))
                        {{ $karyawan->status_karyawan->nm_status_karyawan }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Periode Bekerja</td>
                <td>:</td>
                <td>
                    {{ dateindo($dr_tgl) . ' s/d ' . dateindo($sp_tgl) }}
                </td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <thead
                style="border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black; border-left:1px solid black">
                <th class="text-center" width="10%">#</th>
                <th width="70%">KETERANGAN</th>
                <th width="20%">NOMINAL</th>
            </thead>
            <tbody>
                <tr style="border-right:1px solid black; border-left:1px solid black">
                    <td class="text-center">#</td>
                    <td>Gaji Pokok</td>
                    <td>{{ isset($gaji->n_gaji) ? toRupiah($gaji->n_gaji) : 0 }}</td>
                </tr>
                <tr style="border-right:1px solid black; border-left:1px solid black">
                    <td colspan=3>TUNJANGAN : </td>
                </tr>
                @foreach ($tunjangan as $key => $value)
                    <tr style="border-right:1px solid black; border-left:1px solid black">
                        <td class="text-center">#</td>
                        <td>{{ strtoupper($value) }}</td>
                        <td>{{ isset($gaji->$key) ? toRupiah($gaji->$key) : 0 }}</td>
                    </tr>
                @endforeach
                <tr style="border-right:1px solid black; border-left:1px solid black">
                    <td colspan=2 class="text-right" style="font-weight:bold;">TOTAL : </td>
                    <td style="border-bottom:1px solid black">{{ toRupiah($total_tunjangan) }}</td>
                </tr>
                <tr style="border-right:1px solid black; border-left:1px solid black">
                    <td colspan=3>POTONGAN : </td>
                </tr>
                @foreach ($potongan as $key => $value)
                    <tr style="border-right:1px solid black; border-left:1px solid black">
                        <td class="text-center">#</td>
                        <td>{{ strtoupper($value) }}</td>
                        <td>{{ isset($gaji->$key) ? toRupiah($gaji->$key) : 0 }}</td>
                    </tr>
                @endforeach
                <tr
                    style="font-weight:bold; border-top:1px solid black; border-bottom:1px solid black; border-right:1px solid black; border-left:1px solid black">
                    <td class="text-center">TOTAL : </td>
                    <td>{{ terbilang($gaji->n_gaji + $total_tunjangan - $total_potongan) }} Rupiah</td>
                    <td style="border-bottom:1px solid black">
                        {{ toRupiah($gaji->n_gaji + $total_tunjangan - $total_potongan) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class=container style="height:50px">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-5">
                PENERIMA
            </div>
            <div class="col-md-5 text-right">
                DIREKTUR KEUANGAN
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
    <br>
    <div class=container>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-5">
                {{ $karyawan->nm_karyawan }}
            </div>
            <div class="col-md-5 text-right">
                AYU AFRILLIA
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</body>

</html>
<script>
    $("#cetak").click(function() {
        $("#tombol").hide();
        window.print();
    });
</script>
