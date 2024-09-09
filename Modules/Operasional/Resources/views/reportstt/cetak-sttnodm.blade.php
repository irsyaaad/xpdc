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
            @page
            {
                size: A4 portrait;
                /* size: landscape; */
            }
        }
        body {
            font-family: Arial !important; 
        }
        th {
            font-weight: bold;
        }
        #n {
            border: 1px solid black;
            text-align: center;
        }
        #nx {
            border: 1px solid black;
            font-size: 12px;
        }
    </style>
</head>
<body class="container">
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
	
	<button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i>  Cetak</button>
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
    <h6 class="text-center"><b>STT Belum Masuk DM</b></h6>
    <div class="container" style="margin-top:20px">
        <table class="table table-sm table-borderless" width="100%">
            <thead style="font-size: 12px; border: 1px solid black;">
                <th id="n">No</th>
                <th id="n">Kode Stt</th>
                <th id="n">No AWB</th>
                <th id="n">Tgl Masuk</th>
                <th id="n">Pengirim / Kontak</th>
                <th id="n">Penerima / Kontak</th>
                <th id="n">Alamat Tujuan</th>
                <th id="n">Tipe Kiriman</th>
                <th id="n">Koli</th>
                <th id="n">Berat / Volume</th>
                <th id="n">Keterangan</th>
            </thead>
            <tbody>
                @php
                    $total_koli = 0;
                    $total_berat = 0;
                    $total_volume = 0;
                @endphp
                @foreach ($stt as $key => $value)
                    <tr>
                        <td id="nx">{{$key+1}}</td>
                        <td id="nx" >{{$value->kode_stt}}</td>
                        <td id="nx" >{{$value->no_awb}}</td>
                        <td id="nx" >{{dateindo($value->tgl_masuk)}}</td>
                        <td id="nx" >{{$value->pengirim_nm}} | {{$value->pengirim_telp}}</td>
                        <td id="nx" >{{$value->penerima_nm}} | {{$value->penerima_telp}}</td>
                        <td id="nx" >{{$value->penerima_alm}}</td>
                        <td id="nx" >{{$value->tipekirim->nm_tipe_kirim}}</td>
                        <td id="nx" >{{$value->n_koli}}</td>
                        <td id="nx" >{{$value->n_berat}} Kg | {{$value->n_volume}} M3</td>
                        <td id="nx" >{{$value->info_kirim}}</td>
                    </tr>
                    @php
                        $total_koli+=$value->n_koli;
                        $total_berat+=$value->n_berat;
                        $total_volume+=$value->n_volume;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="8" id="nx" class="text-center">Total</td>
                    <td id="nx">{{$total_koli}}</td>
                    <td id="nx">{{$total_berat}} Kg | {{$total_volume}} M3</td>
                    <td id="nx"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
  
</body>
</html>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
    
</script>
