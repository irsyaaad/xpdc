@if(Request::segment(1) == "biayabydm")
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
                size: A4 portrait;
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
        <table class="table table-sm table-bordered">
        <thead class="text-center">
            <th>No</th>
            <th>No DM</th>
            <th>Tgl Berangkat</th>
            <th>Cabang Tujuan</th>
            <th>Nama Kapal</th>
            <th>Nama Sopir</th>
            <th>No PLAT</th>
            <th>Biaya</th>
            <th>Bayar</th>
            <th>Tgl DM Dibuat</th>
            <th>ID User</th>
        </thead>
        <tbody>
        @php
            $total_biaya = 0;
            $total_bayar = 0;
        @endphp
        @foreach($data as $key => $value)
        <tr>
            <td>{{$key+1}}</td>
            <td>@if(isset($value->kode_dm)){{$value->kode_dm}}@endif</td>
            <td>@if(isset($value->tgl_berangkat)){{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}@endif</td>
            <td>@if(isset($value->nm_perush)){{strtoupper($value->nm_perush)}}@else {{strtoupper($value->nm_ven)}} @endif</td>
            <td>@if(isset($value->nm_kapal_perush)){{$value->nm_kapal_perush}} @else - @endif</td>
            <td>@if(isset($value->nm_sopir)){{$value->nm_sopir}} @else - @endif</td>
            <td>@if(isset($value->no_plat)){{$value->no_plat}} @else - @endif</td>
            <td>@if(isset($value->biaya))Rp. {{ number_format($value->biaya, 0, ',', '.') }} @else Rp. 0 @endif</td>
            <td>@if(isset($value->bayar))Rp. {{ number_format($value->bayar, 0, ',', '.') }} @else Rp. 0 @endif</td>
            <td>@if(isset($value->created_at)){{$value->created_at}}@endif</td>
            <td>@if(isset($value->nm_user)){{$value->nm_user}}@endif</td>
        </tr>
        @php
            $total_biaya += $value->biaya;
            $total_bayar += $value->bayar;
        @endphp
        @endforeach
        <tr>
            <td colspan=7 class="text-center">TOTAL</td>
            <td>Rp. {{ number_format($total_biaya, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($total_bayar, 0, ',', '.') }}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
        </table>
    </div>
</body>
</html>
@endif
@if(Request::segment(1) == "omsetvsbiaya")
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
                size: A4 portrait;
                }
            }
        body {
            font-family: Tahoma !important;
            font-size : 10px;
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
    <table class="table table-sm table-bordered" id="html_table" width="100%">
        <thead class="text-center">
            <th>No</th>
            <th>Kode DM</th>
            <th>Tgl Berangkat</th>
            <th>Nama Vendor</th>
            <th>No SEAL / Container</th>
            <th>Omset</th>
            <th>Biaya</th>
            <th> % </th>
            <th>Laba</th>
            <th> % </th>
        </thead>
        <tbody>
            @php
                $total_omset = 0;
                $total_biaya = 0;
                $total_laba  = 0;
            @endphp
            @foreach($data as $key => $value)
            <tr>
                <td>{{$key+1}}</td>
                <td>@if(isset($value->kode_dm)){{$value->kode_dm}}@endif</td>
                <td>
                    @if(isset($value->tgl_berangkat))
                        {{ daydate($value->tgl_berangkat).", ".dateindo($value->tgl_berangkat) }}
                    @endif
                </td>
                <td>
                    @if(isset($value->p_perush))
                        {{strtoupper($value->p_perush)}}
                    @else 
                        {{strtoupper($value->nm_ven)}} 
                    @endif
                </td>
                <td>
                    @if(isset($value->no_seal))
                        {{$value->no_seal}}
                    @endif
                    /
                    @if(isset($value->no_container))
                        {{$value->no_container}}
                    @endif
                </td>
                <td>@if(isset($value->total_omset))
                        Rp. {{ number_format($value->total_omset, 0, ',', '.') }} 
                    @else 
                        Rp. 0 
                    @endif
                </td>
                <td>
                    @if(isset($value->total_biaya))
                        Rp. {{ number_format($value->total_biaya, 0, ',', '.') }} 
                    @else 
                        Rp. 0 
                    @endif
                </td>
                <td>
                    @if($value->total_biaya > 0)
                        {{round(($value->total_biaya/$value->total_omset) * 100,2)}} %
                    @endif
                </td>
                <td>
                    @if(isset($value->total_laba))
                        Rp. {{ number_format($value->total_laba, 0, ',', '.') }} 
                    @else 
                        Rp. 0 
                    @endif
                </td>
                <td>
                    @if($value->total_laba > 0)
                        {{round(($value->total_laba/$value->total_omset) * 100,2)}} %
                    @endif
                </td>
            </tr>
            @php
                $total_biaya += $value->total_biaya;
                $total_omset += $value->total_omset;
                $total_laba += $value->total_laba;
            @endphp
            @endforeach
            <tr style="font-weight: bold">
                <td colspan=5 class="text-center">Total</td>
                <td>Rp. {{ number_format($total_omset, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($total_biaya, 0, ',', '.') }}</td>
                <td>
                    @if($total_biaya > 0)
                        {{round(($total_biaya/$total_omset) * 100,2)}} %
                    @endif
                </td>
                <td>Rp. {{ number_format($total_laba, 0, ',', '.') }}</td>
                <td>
                    @if($total_laba > 0)
                        {{round(($total_laba/$total_omset) * 100,2)}} %
                    @endif
                </td>
            </tr>
        </tbody>
</table>
    </div>
</body>
</html>
@endif
<script>
$("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
</script>
