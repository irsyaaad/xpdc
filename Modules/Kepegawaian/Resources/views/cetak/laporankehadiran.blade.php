<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
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
    @php
    $days = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
@endphp
<table class="table table-bordered table-sm" width="100%" id="mytable" style="margin-top: 20px">
    <thead >
        <tr rowspan="2">
            <th>Nama Karyawan</th>
            @php 
            $date = $bulan;
            $tahun = $tahun;
            @endphp
            <th colspan="{{ $days }}" class="text-center">{{$bulan}} - {{$tahun}}</th>
            <th rowspan="2" class="text-center">Total</th>
        </tr>
        <tr>
            <th></th>
            @for($i = 1; $i<=$days; $i++)
            <th class="text-center">{{$i}}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($karyawan as $key => $value)
        <tr>
            @php
            $total = 0;
            @endphp
            <td>{{$value->nm_karyawan}}</td>
            @for($i = 1; $i<=$days; $i++)
            @if(isset($day[$value->id_karyawan][$i]))
            @php
            $total += 1;
            @endphp
            <td>
                <i class="fa fa-check" style="color: green"></i>
            </td>
            @else
            <td>
                
            </td>
            @endif
            @endfor
            <td class="text-center">
                {{ $total }}
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