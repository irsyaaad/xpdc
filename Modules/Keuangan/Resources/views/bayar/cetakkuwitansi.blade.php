<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Cetak</title>
    <style>
        @media print{
            @page {size: A5 landscape;margin : -2cm;}
        }
    </style>
</head>

<body class="container">
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
	<button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i>  Cetak</button>
</div>
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
            <div>
                <hr style=" height: 30px; color:black">
            </div>
        </div>

    </div>

    <div class="container row">
        <p>No Kwitansi.</p>
        <p style="padding-left: 50px">{{ $data->no_kwitansi }}</p>
    </div>
    <div class="text-center border-left border-top border-right border-bottom">
        <p style="padding-top : 10px; font-size:18px"><b>KWITANSI</b></p>
    </div>
    
    <br>
    <div style="margin-left:-5px">
        <table class="table table-borderless table-sm">
            <tr>
                <td width="17%">Telah Terima Dari</td>
                <td width="2%">:</td>
                <td>{{strtoupper($data->nm_bayar)}}</td>
            </tr>
            <tr>
                <td>Uang Sejumlah</td>
                <td>:</td>
                <td>{{strtoupper(terbilang($data->n_bayar))}} RUPIAH</td>
            </tr>
            <tr>
                <td>Untuk Pemabayaran</td>
                <td>:</td>
                <td>{{$data->info}}</td>
            </tr>
            <tr>
                <td>No STT</td>
                <td>:</td>
                <td>{{$data->stt->kode_stt}}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>{{$data->info}}, @if(isset($data->id_cr_byr)){{strtoupper($data->cara->nm_cr_byr_o)}}@endif @if(isset($data->id_bank))VIA {{strtoupper($data->bank->id_bank)}}@endif</td>
            </tr>
        </table>
    </div>
    <br><br>
    <?php
    $ldate = date('Y-m-d');
    ?>
    <div class="container" style="padding-left:60%">
        <p>{{$perusahaan->kotakab}}, {{dateindo($ldate)}}</p>
    </div>
    <br><br><br>
    <div class="container row">
        <div class="col"><p>Rp. {{number_format($data->n_bayar)}}</p></div>
        <div class="col"><p class="text-center"><u><b>{{$data->user->nm_user}}</b></u></p></div>
    </div>
</body>

</html>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
</script>
