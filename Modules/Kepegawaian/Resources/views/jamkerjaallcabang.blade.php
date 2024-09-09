<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Laporan Jam Kerja Karyawan</title>
    <style>
         @media print{
            @page {
                size: A4 portrait;
            }
        }
        body {
            font-family: Tahoma !important;
            font-size : 9px;
        }
        
        table tbody tr td{
            text-align: center;
        }
        
        table thead tr th{
            text-align: center;
        }
        
        .td-nama{
            text-align: left;
        }
        
        .td-garis{
            border: 1px solid rgb(0, 0, 0);
        }
        .page-break {
            page-break-after: always;
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
                    
                    if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                        
                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                    }
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="width: 120px">
                </center>
            </div>
            <div class="col-8">
                <h5 class="text-center">PT. LSJ Express</h5>
                <h6 class="text-center">Jl. Raya Brebek No. 46, KOTA SURABAYA-JAWA TIMUR</h6>
                <h6 class="text-center">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</h6>
            </div>
        </div>
    </div>
        
        @php
        $total_jam_kerja = ($jmla * 8);
        $jam_kerja =  ($jmla-$jml) * 8;
        if($jam_kerja <= 0){
            $jam_kerja =8;
        }
        $jam_libur = ($jml * 8);
        @endphp
        <hr>
        <table class="table table-borderless table-sm" width="100%" style="margin-top:10px">
            <tr>
                <td>Tanggal Awal</td>
                <td> : </td>
                <td><b>@if(isset($dr_tgl)){{ dateindo($dr_tgl) }}@endif</b></td>
                <td>Jumlah Hari </td>
                <td> : </td>
                <td><b>{{ $jmla }}</b></td>
                <td>Jumlah Hari Kerja</td>
                <td> : </td>
                <td><b>{{ ($jmla-$jml) }}</b></td>
                <td>Jumlah Hari Libur</td>
                <td> : </td>
                <td><b>{{ $jml }}</b></td>
            </tr>
            <tr>
                <td>Tanggal Akhir</td>
                <td> : </td>
                <td><b>@if(isset($sp_tgl)){{ dateindo($sp_tgl) }}@endif</b></td>
                <td>Jumlah Jam </td>
                <td> : </td>
                <td><b>{{ $total_jam_kerja }}</b></td>
                <td>Jumlah Jam Kerja</td>
                <td> : </td>
                <td><b>{{ $jam_kerja }}</b></td>
                <td>Jumlah Jam Libur</td>
                <td> : </td>
                <td><b>{{ $jam_libur }}</b></td>
            </tr>
        </table>
        <div class="col-md-12">
            @foreach ($perush as $key => $value2)
            <h6 class="text-center" style="margin:20px"><b>{{ ($key+1).". ".$value2->nm_perush }}</b></h6>
            <table class="table" width="100%">
                <thead>
                    <tr>
                        <th rowspan="2" style="border: 1px solid black;">Karyawan</th>
                        <th colspan="3" style="border: 1px solid black; text-align:center">Jam Bekerja</th>
                        <th colspan="4" style="border: 1px solid black; text-align:center">Jam Tidak Bekerja (+)</th>
                        <th colspan="7" style="border: 1px solid black; text-align:center">Jam Tidak Bekerja (-)</th>
                        <td colspan="2" style="border: 1px solid black; text-align:center">Perhitungan</td>
                        <th rowspan="2" style="border: 1px solid black;">Persentase (%)</th>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">Hadir</td>
                        <td style="border: 1px solid black;">Dinas Dalam Kota</td>
                        <td style="border: 1px solid black;">Dinas Luar Kota</td>
                        <td style="border: 1px solid black;">Cuti</td>
                        <td style="border: 1px solid black;">Sakit</td>
                        <td style="border: 1px solid black;">Berduka</td>
                        <td style="border: 1px solid black;">Pulang Cepat (Sakit)</td>
                        
                        <td style="border: 1px solid black;">Tidak Masuk</td>
                        <td style="border: 1px solid black;">Izin Terlambat</td>
                        <td style="border: 1px solid black;">Pulang Cepat</td>
                        <td style="border: 1px solid black;">Keluar</td>
                        <td style="border: 1px solid black;">Terlambat</td>
                        <td style="border: 1px solid black;">Terlambat Istirahat</td>
                        <td style="border: 1px solid black;">Pulang Awal</td>
                        
                        <td style="border: 1px solid black;">Jam Bekerja</td>
                        <td style="border: 1px solid black;">Jam Tidak Bekerja</td>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($karyawan[$value2->id_perush]))
                    @php
                    $total_jam = 0;
                    $total_jam_kosong = 0;
                    $total_prosentase = 0;
                    $total_karyawan = 0;
                    @endphp
                    @foreach($karyawan[$value2->id_perush] as $key => $value)
                    @php
                    $s_jam_kerja = 0;
                    $s_jam_kosong = 0;
                    $s_ijin = 0;
                    $total_karyawan++;
                    $td = 0;
                    $tt = 0;
                    @endphp
                    <tr>
                        <td class="td-garis">{{ $value->nm_karyawan }}</td>
                        <td class="td-garis">
                            @if(isset($kehadiran[$value->id_karyawan]))
                            {{ $kehadiran[$value->id_karyawan]["total"] }}
                            @php
                            $s_jam_kerja += $kehadiran[$value->id_karyawan]["total"];
                            @endphp
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($hijin[$value->id_karyawan]["dd"]))
                            @php
                            $td = $hijin[$value->id_karyawan]["dd"]["total"]*8;
                            $s_jam_kerja += $td;
                            @endphp
                            @endif
                            
                            @if(isset($jizin[$value->id_karyawan]["id"]))
                            @php
                            $tt = round(toMinutes($jizin[$value->id_karyawan]["id"]["total"])/60,2);
                            $s_jam_kerja += $tt;
                            @endphp
                            {{ ($tt+$td) }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($hijin[$value->id_karyawan]["dk"]))
                            {{ $hijin[$value->id_karyawan]["dk"]["total"]*8 }}
                            @php
                            $s_jam_kerja += $hijin[$value->id_karyawan]["dk"]["total"]*8;
                            @endphp
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($hijin[$value->id_karyawan]["c"]))
                            {{ $hijin[$value->id_karyawan]["c"]["total"]*8 }}
                            @php
                            $s_jam_kerja += $hijin[$value->id_karyawan]["c"]["total"]*8;
                            @endphp
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($hijin[$value->id_karyawan]["s"]))
                            {{ $hijin[$value->id_karyawan]["s"]["total"]*8 }}
                            @php
                            $s_jam_kerja += $hijin[$value->id_karyawan]["s"]["total"]*8;
                            @endphp
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($hijin[$value->id_karyawan]["bd"]))
                            {{ $hijin[$value->id_karyawan]["bd"]["total"]*8 }}
                            @php
                            $s_jam_kerja += $hijin[$value->id_karyawan]["bd"]["total"]*8;
                            @endphp
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($jizin[$value->id_karyawan]["ps"]))
                            @php
                            $tt = round(toMinutes($jizin[$value->id_karyawan]["ps"]["total"])/60,2);
                            $s_jam_kosong += $tt;
                            $s_jam_kerja +=8;
                            @endphp
                            {{ $tt }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($hijin[$value->id_karyawan]["tm"]))
                            @php
                            $tt = $hijin[$value->id_karyawan]["tm"]["total"]*8;
                            $s_jam_kosong += $tt;
                            @endphp
                            {{ $tt }}
                            @endif 
                        </td>
                        <td class="td-garis">
                            @if(isset($jizin[$value->id_karyawan]["it"]))
                            @php
                            $tt = round(toMinutes($jizin[$value->id_karyawan]["it"]["total"])/60,2);
                            $s_jam_kosong += $tt;
                            $s_jam_kerja +=8;
                            @endphp
                            {{ $tt }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($jizin[$value->id_karyawan]["ip"]))
                            @php
                            $tt = round(toMinutes($jizin[$value->id_karyawan]["ip"]["total"])/60,2);
                            $s_jam_kosong += $tt;
                            $s_jam_kerja +=8;
                            @endphp
                            {{ $tt }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($jizin[$value->id_karyawan]["k"]))
                            @php
                            $tt = round(toMinutes($jizin[$value->id_karyawan]["k"]["total"])/60,2);
                            $s_jam_kosong += $tt;
                            @endphp
                            {{ $tt }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($terlambat[$value->id_karyawan]))
                            @php
                            $tt = round(toMinutes($terlambat[$value->id_karyawan])/60, 2);
                            $s_jam_kosong += $tt;
                            @endphp
                            {{ $tt }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($istirahat[$value->id_karyawan]) and $value->id_perush != "14")
                            @php
                            $tt = round(toMinutes($istirahat[$value->id_karyawan])/60, 2);
                            $s_jam_kosong += $tt;
                            @endphp
                            {{ $tt }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if(isset($pulang[$value->id_karyawan]))
                            @php
                            $tt = round(toMinutes($pulang[$value->id_karyawan])/60, 2);
                            $s_jam_kosong += $tt;
                            @endphp
                            @endif
                        </td>
                        @php
                        $alpha = $jam_kerja-$s_jam_kerja-$s_jam_kosong;
                        if($alpha < 0){
                            $alpha = 0;
                        }
                        $s_jam_kosong += $alpha;
                        @endphp
                        <td class="td-garis">
                            @php
                            $s_jam_kerja = $jam_kerja - $s_jam_kosong;
                            if($s_jam_kerja>$jam_kerja){
                                $s_jam_kerja = $jam_kerja;
                            }
                            if($s_jam_kerja<0){
                                $s_jam_kerja = 0;
                            }
                            $total_jam += $s_jam_kerja;
                            @endphp
                            {{ $s_jam_kerja }}
                        </td>
                        <td class="td-garis">
                            @php
                            $jk = $s_jam_kosong;
                            $jk = round($jk, 2);
                            if($jk>$jam_kerja){
                                $jk = 0;
                            }
                            
                            $total_jam_kosong += $jk;
                            @endphp
                            {{ $jk }}
                        </td>
                        <td class="td-garis">
                            @php
                            $tt = ($s_jam_kerja / $jam_kerja) * 100;
                            $tt = round($tt, 2);
                            $total_prosentase += $tt;
                            @endphp
                            {{ $tt." %" }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="td-garis text-right" colspan="15">
                            RATA - RATA :
                        </td>
                        <td class="td-garis">
                            @if($total_jam > 0)
                            {{ round(($total_jam/$total_karyawan), 3) }}
                            @else
                            {{ 0 }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if($total_jam_kosong > 0)
                            {{ round(($total_jam_kosong/$total_karyawan), 3) }}
                            @else
                            {{ 0 }}
                            @endif
                        </td>
                        <td class="td-garis">
                            @if($total_prosentase > 0)
                            {{ round(($total_prosentase/$total_karyawan), 3) }} %
                            @elseif($total_prosentase > 100)
                            {{ 100 }} %
                            @else
                            {{ 0 }}
                            @endif
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="page-break"></div>
            @endforeach
        </div>
    </div>
</body>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
</script>
</html>
