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
                <th id="n">No. Stt</th>
                <th id="n">Tipe Kiriman</th>
                <th id="n">Koli Kirim</th>
                <th id="n">Tally</th>
                <th id="n">Jumlah Tally</th>
                <th id="n">Keterangan</th>
            </thead>
            <tbody>
                @foreach ($stt as $key => $value)
                    <tr>
                        <td id="nx">{{$key+1}}</td>
                        <td id="nx" >{{$value->kode_stt}}</td>
                        <td id="nx" >{{$value->tipekirim->nm_tipe_kirim}}</td>
                        <td id="nx" >{{$value->n_koli}}</td>
                        <td id="nx" ></td>
                        <td id="nx" ></td>
                        <td id="nx" >{{$value->info_kirim}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="row text-right">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-center">
                <b>Tanda Tangan Petugas, </b>
                <br>
                <br>
                <br>
                <br>
                <hr style="height:2px;width:200px; border-width:0;color:black;background-color:black">
            </div>
        </div>
    </div>
    <br>
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
