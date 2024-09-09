<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Cetak Statistik Kehadiran</title>
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
        <hr>
        <div class="container-fluid" style="margin-top:20px">
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
                    <table class="table table-responsive table-bordered" id="mytable" width="100%">
                        <thead style="background-color: #fff; color : black">
                            <tr style="border: 1px solid black;">
                                <th rowspan="2" style="border: 1px solid black;">No</th>
                                <th rowspan="2" style="border: 1px solid black;">Nama Karyawan</th>
                                <th rowspan="2" style="border: 1px solid black; text-align:center">Kehadiran</th>
                                <th colspan="{{ count($jenis) }}" style="border: 1px solid black; text-align:center">Perizinan</th>
                                <th colspan="5" style="border: 1px solid black; text-align:center">Ket Kehadiran</th>
                            </tr>
                            <tr style="border: 1px solid black;">
                                @foreach($jenis as $key => $value)
                                <th style="border: 1px solid black;">{{  $value->nm_jenis }}</th>
                                @endforeach
                                <th style="border: 1px solid black;">Terlambat</th>
                                <th style="border: 1px solid black;">Tdk Absen Masuk</th>
                                <th style="border: 1px solid black;">Tdk Absen Pulang</th>
                                <th style="border: 1px solid black;">Pulang awal</th>
                                <th style="border: 1px solid black;">Alpha</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($data as $key => $value)
                            <tr>
                                @php
                                $alpha = 0;
                                @endphp
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ strtoupper($value->nm_karyawan) }}
                                </td>
                                <td>
                                    {{ $value->absen }}
                                </td>
                                @foreach($jenis as $key1 => $value1)
                                @if(isset($izin[$value->id_karyawan][$value1->id_jenis]))
                                <td>{{ $izin[$value->id_karyawan][$value1->id_jenis]->ijin }}</td>
                                @php
                                $ij = $izin[$value->id_karyawan][$value1->id_jenis];
                                if($ij->format == "2"){
                                    $alpha += $ij->ijin;
                                }
                                @endphp
                                @else
                                <td></td>
                                @endif
                                @endforeach
                                
                                
                                @for($i = 2; $i<=3; $i++)
                                @if(isset($status_datang[$value->id_karyawan][$i]))
                                <td>
                                    {{ $status_datang[$value->id_karyawan][$i]->jumlah }}
                                </td>
                                @else
                                <td></td>
                                @endif
                                @endfor
                                
                                @for($i = 4; $i<=5; $i++)
                                @if(isset($status_pulang[$value->id_karyawan][$i]))
                                <td>
                                    {{ $status_pulang[$value->id_karyawan][$i]->jumlah }}
                                </td>
                                @else
                                <td></td>
                                @endif
                                @endfor
                                <td>
                                    @php
                                    $alpha2 = $jmla - ($jml + $value->absen + $alpha);
                                    
                                    if($alpha2 < 0){
                                        $alpha2 = 0;
                                    }
                                    @endphp
                                    {{ $alpha2 }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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