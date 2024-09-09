<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Cetak</title>
    <style>
        @media print{
            @page {
                size: A4 landscape;
            }
        }
        body {
            font-family: Tahoma !important;
            font-size : 12px;
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
        <div class="container" style="margin-top:20px">
            <table class="table" width="100%">
                <thead style="background-color: grey; color : #ffff">
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan </th>
                        <th>Jenis Perijinan</th>
                        <th>Ijin Tanggal</th>
                        <th>Sampai Tanggal</th>
                        <th>Lama Hari Ijin</th>
                        <th>Keterangan</th>
                        <th>Status konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>@if(isset($value->nm_karyawan)){{ strtoupper($value->nm_karyawan) }}@endif</td>
                        <td>@if(isset($value->nm_jenis)){{ strtoupper($value->nm_jenis) }}@endif</td>
                        <td>@if(isset($value->dr_tgl)){{ daydate($value->dr_tgl).", ".dateindo($value->dr_tgl) }}@endif</td>
                        <td>@if(isset($value->sp_tgl)){{ daydate($value->sp_tgl).", ".dateindo($value->sp_tgl) }}@endif</td>
                        <td>@if(isset($value->dr_tgl) and isset($value->sp_tgl))
                            @php
                            $tgl1 = new DateTime(date($value->dr_tgl));
                            $tgl2 = new DateTime(date($value->sp_tgl));
                            $perbedaan = $tgl2->diff($tgl1)->format("%a");
                            @endphp
                            {{$perbedaan+1}}@endif</td>            
                            <td>@if(isset($value->keterangan)){{ strtoupper($value->keterangan) }}@endif</td>
                            <td>
                                @if($value->is_konfirmasi==1)
                                <i class="fa fa-check" style="color: green"></i>
                                @else
                                <i class="fa fa-times" style="color: red"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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