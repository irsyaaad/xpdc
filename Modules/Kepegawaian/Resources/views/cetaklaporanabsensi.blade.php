<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Laporan Absensi</title>
    <style>
        @media print{
            @page {
                size: A4 portrait;
            }
        }
        body {
            font-family: Tahoma !important;
            font-size : 12px;
        }
    </style>
    @php
    $excel = preg_match("/excel/", url()->full());
    @endphp
    @if($excel == 1)
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=LaporanAbsensi".$perusahaan->nm_perush.date("mY").".xls");
    ?>
    @endif
</head>
<body class="container">
    @if($excel == 0)
    <div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        <button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i> Cetak</button>
    </div>
    @endif
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
            <table class="table table-sm table-bordered">
                <thead >
                    <tr>
                        <th>No</th>
                        <th>Karyawan</th>
                        <th>Perusahaan / Devisi</th>
                        <th>Tgl Absen</th>
                        <th> Jam Masuk</th>
                        <th> Jam Pulang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>     
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            @if(isset($value->nm_karyawan))
                            {{ strtoupper($value->nm_karyawan) }}
                            @endif
                        </td>
                        <td>
                            @if(isset($value->nm_perush))
                            {{ strtoupper($value->nm_perush) }}
                            @endif
                        </td>
                        <td>
                            {{ daydate($value->tgl_absen).", ".dateindo($value->tgl_absen) }}
                        </td>
                        <td>
                            {{ $value->jam_datang }}
                        </td>
                        <td>
                            {{ $value->jam_pulang }}
                        </td>
                        <td>
                            @if($value->status_datang==1)
                            <label style="color: green">Absen Sebelum Jam Masuk</label>
                            @elseif($value->status_datang==2)
                            <label style="color: red">Absen Terlambat</label>
                            @elseif($value->status_datang==3)
                            <label style="color: red">Tidak Absen Masuk</label>
                            @endif
                            <br>
                            
                            @if($value->status_pulang==4)
                            <label style="color: red">Tidak Absen Pulang</label>
                            @elseif($value->status_pulang==5)
                            <label style="color: red">Absen Pulang Dahulu</label>
                            @endif
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
    </html>
    <script>
        $("#cetak").click(function(){
            $("#tombol").hide();
            window.print();
        });
        
    </script>