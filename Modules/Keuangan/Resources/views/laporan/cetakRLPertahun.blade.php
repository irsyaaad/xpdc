<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Neraca</title>
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
    <table class="table table-bordered table-sm ">
    <thead >
        <th class="text-center">Keterangan</th>
        <th class="text-center">Jan</th>
        <th class="text-center">Feb</th>
        <th class="text-center">Mar</th>
        <th class="text-center">Apr</th>
        <th class="text-center">Mei</th>
        <th class="text-center">Jun</th>
        <th class="text-center">Jul</th>
        <th class="text-center">Agu</th>
        <th class="text-center">Sep</th>
        <th class="text-center">Okt</th>
        <th class="text-center">Nov</th>
        <th class="text-center">Des</th>
    </thead>
    <tbody>
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 4)
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=13 style="padding-left:10px;background-color: #ededed;">{{$value2->nama}}</td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td>{{$value3->nama}}</td>
                                    @for ($i = 1; $i <= 12; $i++)
                                    @if(isset($temp[$i][$value3->id_ac]))
                                        <td>Rp. {{ number_format($temp[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                    @endif
                                    @endfor                                
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @if(isset($total[$i][$value2->id_ac]))
                                <td style="background-color: #ededed;">Rp. {{ number_format($total[$i][$value2->id_ac], 0, ',', '.') }}</td>
                            @endif
                        @endfor                    
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    @foreach($data1 as $key => $value)
        @if($value->id_ac == 5)
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=13 style="padding-left:10px;background-color: #ededed;">{{$value2->nama}}</td>
                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr>
                                <td>{{$value3->nama}}</td>
                                    @for ($i = 1; $i <= 12; $i++)
                                    @if(isset($temp[$i][$value3->id_ac]))
                                        <td>Rp. {{ number_format($temp[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                    @endif
                                    @endfor
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    <td style="background-color: #ededed;">Sub total</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @if(isset($total[$i][$value2->id_ac]))
                                <td style="background-color: #ededed;">Rp. {{ number_format($total[$i][$value2->id_ac], 0, ',', '.') }}</td>
                            @endif
                        @endfor                    
                    </tr>
                @endforeach
            @endif
        @endif
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