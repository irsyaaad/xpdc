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
                size: A4 potrait;
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
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
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
    <div class="container" style="margin-top:20px">
        <table class="table table-sm table-borderless" width="50%">
            <tr style="padding: 0px">
                <th>No. DM</th>
                <th> : </td>
                <th>{{$dm->kode_dm}}</th>
            </tr>
            @if ($dm->is_vendor)
                <tr>
                    <th>Nama Vendor</th>
                    <th> : </th>
                    <th>@if (isset($dm->vendor->nm_ven))
                        {{$dm->vendor->nm_ven}}
                    @else
                        @isset($dm->perush_tujuan->nm_perush)
                            {{$dm->perush_tujuan->nm_perush}}
                        @endisset
                    @endif</th>
                </tr>
            @else
                <tr>
                    <th>Nama Kapal</th>
                    <th> : </th>
                    <th>@isset($dm->kapal->nm_kapal)
                        {{$dm->kapal->nm_kapal}}
                    @endisset</th>
                </tr>
                <tr>
                    <th>Sopir</th>
                    <th> : </th>
                    <th>@isset($dm->sopir->nm_sopir)
                        {{$dm->sopir->nm_sopir}}
                    @endisset</th>
                </tr>
                <tr>
                    <th>No Plat</th>
                    <th> : </th>
                    <th>@isset($dm->armada->no_plat)
                        {{$dm->armada->no_plat}}
                    @endisset</th>
                </tr>
                <tr>
                    <th>Berangkat / Tujuan</th>
                    <th> : </th>
                    <th>
                        {{$dm->nm_dari}} / {{$dm->nm_tuju}}                   
                    </th>
                </tr>
                
            @endif
            
            <tr>
                <th>Tgl Berangkat / Est Tiba</th>
                <th> : </th>
                <th>
                    ({{dateindo($dm->tgl_berangkat)}}) / ({{dateindo($dm->tgl_sampai)}})                    
                </th>
            </tr>
            @if ($dm->id_layanan == 2)
            <tr>
                <th>No Container / No Seal</th>
                <th> : </th>
                <th>
                    {{$dm->no_container}} / {{$dm->no_seal}}               
                </th>
            </tr>
            @endif
            
        </table>

        <table class="table table-sm table-borderless" width="100%">
            <thead style="font-size: 12px; border: 1px solid black;">
                <th id="n">No</th>
                <th id="n">Kode Stt</th>
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
                    <td colspan="7" id="nx" class="text-center">Total</td>
                    <td id="nx">{{$total_koli}}</td>
                    <td id="nx">{{$total_berat}} Kg | {{$total_volume}} M3</td>
                    <td id="nx"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <div class=container style="height:50px">
        <div class="row">
            <div class="col">
                <canvas id="qr-code"></canvas>
            </div>
            <div class="col">
                @if ($dm->is_vendor)
                    @if (isset($dm->vendor->nm_ven))
                        {{$dm->vendor->nm_ven}}
                    @else
                        @isset($dm->perush_tujuan->nm_perush)
                            {{$dm->perush_tujuan->nm_perush}}
                        @endisset
                    @endif              
                @else
                {{$dm->perush_tujuan->nm_perush}}
                @endif
                
            </div>
            <div class="col">
                Sopir
            </div>
            <div class="col">
                {{ $perusahaan->nm_perush }}
            </div>
        </div>
    </div>
    <br>
    <div class=container>
        <div class="row">
            <div class="col">
                
            </div>
            <div class="col">
                {{ $dm->nm_pj_tuju }}
            </div>
            <div class="col">
                @isset($dm->sopir->nm_sopir)
                    {{$dm->sopir->nm_sopir}}
                @endisset
            </div>
            <div class="col">
                @isset($dm->user->nm_user)
                    {{ $dm->user->nm_user }}
                @endisset
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
    var qr;
    (function() {
            var id_stt = "http://lsj-express.id/cekresi/<?php echo $id; ?>";
            console.log(id_stt);
            qr = new QRious({
            element: document.getElementById('qr-code'),
            size: 100,
            value: id_stt
        });
    })();
</script>
