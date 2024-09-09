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
                
                }
            }
        body {
            font-family: Arial !important; 
            font-weight: bold;
            font-size : 20px;
        }
    </style>
</head>
<body class="container">
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
	<a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
	<button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i>  Cetak</button>
</div>
<br>
    <!-- @foreach($data as $key => $value)
    <table style="border: 1px solid black; text-align:center">
        <thead>
            <th>@php

                        if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                        }

                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="width: 120px"></th>
            <th>{{$value->id_stt}}</th>
        </thead>
        <tbody>
            <tr>
                <td rowspan=6><canvas id="qr-code{{$key}}"></canvas></td>
                <td>Pengirim</td>                
            </tr>
            <tr>
                <td><b>{{strtoupper($value->pengirim_nm)}}</b></td>                
            </tr>
            <tr>
                <td>Penerima</td>                
            </tr>
            <tr>
                <td><b>{{strtoupper($value->penerima_nm)}}</b></td>                
            </tr>
            <tr>
                <td>Kota Tujuan</td>                
            </tr>
            <tr>
                <td><b>{{strtoupper($value->kota_tujuan)}}</b></td>                
            </tr>
        </tbody>
    </table>
    <br>
    @endforeach
    
    <br>
    <hr> -->
    @foreach($data as $key => $value)
        <table class="table table-sm table-borderless" style="border: 1px solid black; text-align:center">
            <thead>
                <th>@php

                            if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                            $path = 'public/uploads/perusahaan/'.$perusahaan->logo;

                            $full_path = Storage::path($path);
                            $base64 = base64_encode(Storage::get($path));
                            $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                            $perusahaan->logo = $image;
                            }

                        @endphp
                        <img src="{{ $perusahaan->logo }}" style="width: 120px">
                </th>

                <th colspan=2>
                <h5 class="text-center">{{ $perusahaan->nm_perush }}</h5>
                <h6 class="text-center">{{ $perusahaan->alamat }},
                    {{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</h6>
                <h6 class="text-center">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</h6>
                </th>
                
            </thead>
            <tbody>
                <tr>
                    <td colspan=3 style="font-size: 50px;">{{$kode_stt->kode_stt}}</td>                    
                </tr>
                <tr>
                    <td colspan=3 style="font-size: 50px;">
                        {{strtoupper($kode_stt->asal->nama_wil)}} - {{strtoupper($kode_stt->tujuan->nama_wil)}}
                    </td>
                </tr>
                <tr>
                    <td colspan=3 >
                        <img alt='{{$value->id_koli}}' src='http://bwipjs-api.metafloor.com/?bcid=code128&text={{$value->id_koli}}&scaleX=4&scaleY=1'/>
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>
</html>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
    
    var qr;
    (function() {
            var id_stt = "http://lsj-express.id/cekresi/";
            console.log(id_stt);
            qr = new QRious({
            element: document.getElementById('qr-code0'),
            size: 100,
            value: id_stt
        });
    })();

    var id_stt = "{{$id_stt}}";
    console.log(id_stt);
    $.ajax({
            type: "GET",
            url: "{{ url('getKoli') }}/"+id_stt,
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){ 
                //console.log(response);
                $.each(response, function(index, value) {
                    var qr;
                    (function() {
                            var id_stt = value.id_koli;
                            //console.log(id_stt);
                            qr = new QRious({
                            element: document.getElementById('qr-code'+index),
                            size: 100,
                            value: id_stt
                        });
                    })();
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            }
        });
</script>
