<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Laporan Denda</title>
    <style>
        @media print{
            @page {
                size: F4 portrait;
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
    </style>
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=LaporanDendaKehadiran".$perusahaan->nm_perush.date("mY").".xls");
    ?>
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
                
                <h5 class="text-center">{{ $perusahaan->nm_perush }}</h5>
                <h6 class="text-center">{{ $perusahaan->alamat }},
                    {{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</h6>
                    <h6 class="text-center">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</h6>
                    
                </div>
            </div>
        </div>
        <div class="container" style="margin-top:20px">
            <div class="row">
                <div class="col-md-3">
                    <label>Tanggal Awal : <b>@if(isset($dr_tgl)){{ dateindo($dr_tgl) }}@endif</b></label><br>
                    <label>Tanggal Akhir : <b>@if(isset($sp_tgl)){{ dateindo($sp_tgl) }}@endif</b></label><br>
                </div>
                <div class="col-md-3">
                    <label>Jumlah Hari : <b>@if(isset($jmla)){{ $jmla }}@endif</b></label><br>
                    <label>Jumlah Hari Kerja : <b>@if(isset($jmla)){{ $jmla-$jml }}@endif</b></label><br>
                    
                </div>
                <div class="col-md-3">
                    <label>Jumlah Hari Libur : <b>@if(isset($jml)){{ $jml }}@endif</b></label>
                </div>
                <div class="col-md-12"> 
                    <table class="table table-responsive table-striped" width="100%">
                        <thead style="background-color: grey; color : #ffff">
                            <tr>
                                <th rowspan="2" style="border: 1px solid white;">No</th>
                                <th rowspan="2" style="border: 1px solid white;">Karyawan</th>
                                <th rowspan="2" style="border: 1px solid white;">Kehadiran</th>
                                <th colspan="{{ count($jenis)+5 }}" style="border: 1px solid white; text-align:center">Denda</th>
                                <th rowspan="2" style="border: 1px solid white;">Total Denda</th>
                            </tr>
                            <tr style="border: 1px solid white;">
                                @foreach($jenis as $key => $value)
                                <th style="border: 1px solid white;">{{  $value->nm_jenis }}</th>
                                @endforeach
                                <th style="border: 1px solid white;">Terlambat</th>
                                <th style="border: 1px solid white;">Tdk Absen Masuk</th>
                                
                                <th style="border: 1px solid white;">Tdk Absen Pulang</th>
                                <th style="border: 1px solid white;">Pulang Duluan</th>
                                
                                <th style="border: 1px solid white;">Alpha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawan as $key => $value)
                            
                            @php
                            $total = 0;
                            $kurang = 0;
                            $tlmb = 0;
                            $kehadiran = 0;   
                            @endphp
                            <tr>
                                <td class="td-nama">
                                    {{ $key+1 }}
                                    {{-- {{ $value->id_karyawan }} --}}
                                </td>
                                <td class="td-nama">
                                    {{ strtoupper($value->nm_karyawan) }}
                                </td>
                                @if(isset($dk[$value->id_karyawan]))
                                    @php
                                        $kehadiran += $dk[$value->id_karyawan];
                                    @endphp
                                @endif
                                @if(isset($id[$value->id_karyawan]))
                                @php
                                    $kehadiran += $id[$value->id_karyawan];
                                @endphp
                                @endif
                                
                                @if(isset($absen[$value->id_karyawan]))
                                <td class="td-garis">
                                    {{ $absen[$value->id_karyawan]["absen"] }}
                                </td>
                                @php
                                $kurang += $absen[$value->id_karyawan]["absen"];
                                @endphp
                                @else
                                <td class="td-garis"></td>
                                @endif
            
                                @foreach($jenis as $key1 => $value1)
                                @if(isset($ijin[$value->id_karyawan][$value1->id_jenis]))
                                @php
                                $jumlah = $ijin[$value->id_karyawan][$value1->id_jenis]["jumlah"];
                                $nominal = $ijin[$value->id_karyawan][$value1->id_jenis]["nominal"];
                                $frekuensi = $ijin[$value->id_karyawan][$value1->id_jenis]["frekuensi"];
                                $dd = 0;
                                if($jumlah > $frekuensi){
                                    $dd = $jumlah - $frekuensi;
                                }
                                
                                $denda = $dd * $nominal;
            
                                @endphp
                                <td>{{ str_replace(",", ".", number_format($denda)) }}</td>
                                @php
                                $total += $denda;
                                $kurang += $jumlah;
                                @endphp
                                @else
                                <td> 0 </td>
                                @endif
                                @endforeach
                                
                                @php
                                $n_datang = 0;
                                $n_pulang = 0;
                                @endphp
                                
                                <td>
                                    @if(isset($datang[$value->id_karyawan][2]) and $datang[$value->id_karyawan][2]->status_datang=="2")
                                    @php
                                    $setting = $s_datang[2];
                                    $jumlah = $datang[$value->id_karyawan][2]->jumlah;
                                    $n_hari = 0;
                                    if($jumlah > $setting->frekuensi){
                                        $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                                        $n_datang += $n_hari;
                                    }
                                    
                                    echo $n_hari;
                                    @endphp
                                    @endif
                                </td>
            
                                <td>
                                    @if(isset($datang[$value->id_karyawan][3]) and $datang[$value->id_karyawan][3]->status_datang=="3")
                                    @php
                                    $setting = $s_datang[3];
                                    $jumlah = $datang[$value->id_karyawan][3]->jumlah;
                                    $n_hari = 0;
                                    if($jumlah > $setting->frekuensi){
                                        $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                                        $n_datang += $n_hari;
                                    }
                                    
                                    echo $n_hari;
                                    @endphp
                                    @endif
                                </td>
                                <td>
                                    @if(isset($pulang[$value->id_karyawan][4]->status_pulang) and $pulang[$value->id_karyawan][4]->status_pulang=="4")
                                    @php
                                    $setting = $s_pulang[4];
                                    $jumlah = $pulang[$value->id_karyawan][4]->jumlah;
                                    $n_hari = 0;
                                    if($jumlah > $setting->frekuensi){
                                        $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                                        $n_pulang += $n_hari;
                                    }
                                    echo $n_hari;
                                    @endphp
                                    @endif
                                </td>
                                <td>
                                    @if(isset($pulang[$value->id_karyawan][5]->status_pulang) and $pulang[$value->id_karyawan][5]->status_pulang=="5")
                                    @php
                                    $setting = $s_pulang[4];
                                    $jumlah = $pulang[$value->id_karyawan][5]->jumlah;
                                    $n_hari = 0;
                                    if($jumlah > $setting->frekuensi){
                                        $n_hari = ($jumlah - $setting->frekuensi) * $setting->nominal;
                                        $n_pulang += $n_hari;
                                    }
                                    echo $n_hari;
                                    @endphp
                                    @endif
                                </td>
                                <td>
                                    
                                    @if(isset($absen[$value->id_karyawan]))
                                    @php
                                    $hadir =  $jmla - $jml - $kurang - $kehadiran;
                                   // dd($jmla, $jml, $kurang);
                                    $denda = ($hadir - $alpha->frekuensi) * $alpha->nominal;
            
                                    if($denda < 0 ){
                                        $denda = 0;
                                    }
                                    
                                    @endphp
                                    {{ str_replace(",", ".", number_format($denda)) }}
                                    @else
                                    0
                                    @endif
                                </td>
                                
                                <td>
                                    @if(isset($absen[$value->id_karyawan]))
                                    @php
                                    
                                    $denda = $total+$denda+$n_datang+$n_pulang;
                                    @endphp
                                    {{ str_replace(",", ".", number_format($denda)) }}
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
    </html>
    <script>
        $("#cetak").click(function(){
            $("#tombol").hide();
            window.print();
        });
        
    </script>