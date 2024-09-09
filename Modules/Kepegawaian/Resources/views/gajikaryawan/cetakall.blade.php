<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Daftar Gaji</title>
    <style>
        @media print{
            @page {
                size: A4 landscape;
            }
        }
        body {
            font-family: Tahoma !important;
            font-size : 9px;
        }
    </style>
</head>
<body class="container">
    <div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        <button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i> Cetak</button>
    </div>
    @foreach($perusahaan as $key => $value5)
    <div class="container" style="margin-top:20px">
        <div>
            <h6>Perusahaan / Devisi : {{ $value5->nm_perush }}</h6>
            <h6> Periode Gaji {{ date("M Y", strtotime($tahun."-".$bulan)) }}</h6>
        </div>
        <table class="table table-sm table-bordered">
            <thead class="text-center">
                <tr>
                    <th rowspan=2>No</th>
                    <th rowspan=2>Nama Karyawan</th>
                    <th rowspan=2>Bagian</th>
                    <th rowspan=2>Jabatan</th>
                    <th rowspan=2>Gol / Pangkat</th>
                    <th rowspan=2>Gaji Pokok</th>
                    <th colspan=2 >Tunjangan</th>
                    <th colspan=3>Potongan</th>
                    <th rowspan=2>Sisa Gaji / THP</th>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <th>Kehadiran</th>

                    <th>Kas Bon</th>
                    <th>Denda Keahdiran</th>
                    <th>BPJS</th>
                </tr>

            </thead>
            <tbody>
                @php
                $total_gaji_pokok = 0;
                $total_tuj_jab    = 0;
                $total_tuj_ops    = 0;
                $total_tuj_keh    = 0;
                $total_kasbon     = 0;
                $total_denda_keh  = 0;
                $total_bpjs       = 0;
                $total_thp        = 0;
                $total_bpjs       = 0;

                @endphp
                @if(isset($gaji[$value5->id_perush]))
                @php
                $no = 0;
                @endphp
                @foreach($gaji[$value5->id_perush] as $key => $value)
                <tr>
                    <td>{{ (++$no) }}</td>
                    <td>{{ strtoupper($value->nm_karyawan) }}</td>
                    <td>{{ strtoupper($value->nm_jenis) }}</td>
                    <td>{{ strtoupper($value->nm_jabatan) }}</td>
                    <td>{{ strtoupper($value->golongan." / ".$value->pangkat) }}</td>
                    @php
                    $gj = $value->n_gaji;
                    $tj = $value->n_tunjangan;
                    $tk = $value->n_kehadiran * 0.1;
                    $thp = $value->n_gaji+$value->n_tunjangan-$value->n_denda;
                    $total_gaji_pokok += $gj;
                    $total_tuj_jab += $tj;
                    $total_tuj_keh += $tk;

                    @endphp
                    <td>@if(isset($gj)){{ tonumber($gj) }}@endif</td>
                    <td>@if(isset($tj)){{ tonumber($tj) }}@endif</td>
                    <td>@if(isset($tk)){{ tonumber($tk) }}@endif</td>
                    <td>
                        @if(isset($value->n_piutang))
                        {{ tonumber($value->n_piutang) }}
                        @php
                        $thp        -= $value->n_piutang;
                        $total_kasbon += $value->n_piutang;
                        @endphp
                        @endif
                    </td>
                    <td>@if(isset($value->n_denda))
                        {{ tonumber($value->n_denda) }}
                        @endif
                        @php
                        $total_denda_keh += $value->n_denda;
                        @endphp
                    </td>
                    <td>@if (isset($value->n_bpjs))
                        Rp. {{ tonumber($value->n_bpjs) }}
                        @php
                        $total_bpjs +=$value->n_bpjs;
                        $thp        -= $value->n_bpjs;
                        @endphp
                        @else
                        Rp. 0
                        @endif</td>
                        <td>
                            {{ tonumber($thp) }}
                            @php
                            $total_thp        += $thp;
                            @endphp
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    <tr>
                        <td colspan=5 class="text-center"><b>TOTAL</b></td>
                        <td>Rp. {{tonumber($total_gaji_pokok)}}</td>
                        <td>Rp. {{tonumber($total_tuj_jab)}}</td>
                        <td>Rp. {{tonumber($total_tuj_keh)}}</td>
                        <td>Rp. {{tonumber($total_kasbon)}}</td>
                        <td>Rp. {{tonumber($total_denda_keh)}}</td>
                        <td>Rp. {{tonumber($total_bpjs)}}</td>
                        <td>Rp. {{tonumber($total_thp)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endforeach
    </body>
    </html>
    <script>
        $("#cetak").click(function(){
            $("#tombol").hide();
            window.print();
        });
    </script>
