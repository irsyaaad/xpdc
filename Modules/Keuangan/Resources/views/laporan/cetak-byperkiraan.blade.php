<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>{{Request::segment(1)}}</title>
    <style>
        @media print{
            @page {
                size: A4;
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
@if(Request::segment(1)=="neracabyperkiraan")
<table class="table table-sm table-bordered">
    <thead>
    <tr>
        <th rowspan=2 class="text-center">No</th>
        <th rowspan=2 class="text-center">AC 4</th>
        <th rowspan=2 class="text-center">Perkiraan 4</th>
        <th colspan=2 class="text-center">Saldo Awal</th>
        <th colspan=3 class="text-center">Transaksi Bulanan</th>
        <th colspan=3 class="text-center">Total Berjalan</th>
    </tr>
    <tr>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Total</th>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Total</th>   
    </tr>
    </thead>
    <tbody>
    @foreach($data1 as $key => $value)
        @if($value->id_ac < 4)
        <tr><td colspan=11>{{$value->nama}}</td></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=11>{{$value2->nama}}</td>                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr style="background-color: #e3e3e3;">
                                <td colspan=11>{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        $total_deb = 0;
                                        $total_kre = 0;
                                        $total_deb_sa = 0;
                                        $total_kre_sa = 0;
                                        $total_deb_bi = 0;
                                        $total_kre_bi = 0;
                                        $total_tot_bi = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            <tr>
                                                <td></td>
                                                <td>{{$value4->ac_perush}}</td>
                                                <td>{{$value4->nama_ac_perush}}</td>
                                                <td>Rp. {{ number_format($sa_debit[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($sa_kredit[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_deb[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_kre[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_deb[$value4->ac_perush]-$bulanini_kre[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->debit, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->kredit, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->debit-$value4->kredit, 0, ',', '.') }}</td>
                                                @php
                                                $total += $value4->debit-$value4->kredit;
                                                $total_deb += $value4->debit;
                                                $total_kre += $value4->kredit;
                                                $total_deb_sa += $sa_debit[$value4->ac_perush];
                                                $total_kre_sa += $sa_kredit[$value4->ac_perush];
                                                $total_deb_bi += $bulanini_deb[$value4->ac_perush];
                                                $total_kre_bi += $bulanini_kre[$value4->ac_perush];
                                                $total_tot_bi += $bulanini_deb[$value4->ac_perush]-$bulanini_kre[$value4->ac_perush];
                                                @endphp
                                            </tr>
                                        @endforeach
                                        <tr style="background-color: #d5e8e8;">
                                              <td colspan=3 rowspan=2 class="text-center">Sub Total {{$value3->nama}}</td>   
                                              <td>Rp. {{ number_format($total_deb_sa, 0, ',', '.') }}</td> 
                                              <td>Rp. {{ number_format($total_kre_sa, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_deb_bi, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_kre_bi, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_tot_bi, 0, ',', '.') }}</td>   
                                              <td>Rp. {{ number_format($total_deb, 0, ',', '.') }}</td>    
                                              <td>Rp. {{ number_format($total_kre, 0, ',', '.') }}</td>   
                                              <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>                                  
                                        </tr>
                                        <tr style="background-color: #d5e8e8;">
                                            <td colspan=2 class="text-center">Rp. {{ number_format($total_deb_sa-$total_kre_sa, 0, ',', '.') }}</td> 
                                            <td colspan=3 class="text-center">Rp. {{ number_format($total_tot_bi, 0, ',', '.') }}</td>
                                            <td colspan=3 class="text-center">Rp. {{ number_format($total, 0, ',', '.') }}</td> 
                                        </tr>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    </tbody>
</table>
@endif

@if(Request::segment(1)=="rugilababyperkiraan")
<table class="table table-sm table-bordered">
    <thead >
    <tr>
        <th rowspan=2 class="text-center">No</th>
        <th rowspan=2 class="text-center">AC 4</th>
        <th rowspan=2 class="text-center">Perkiraan 4</th>
        <th colspan=2 class="text-center">Saldo Awal</th>
        <th colspan=3 class="text-center">Transaksi Bulanan</th>
        <th colspan=3 class="text-center">Total Berjalan</th>
    </tr>
    <tr>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Total</th>
        <th class="text-center">Debet</th>    
        <th class="text-center">Kredit</th>
        <th class="text-center">Total</th>   
    </tr>
    </thead>
    <tbody>
    @foreach($data1 as $key => $value)
        @if($value->id_ac > 3)
        <tr></tr>
            @if(isset($data2[$value->id_ac]))
                @foreach($data2[$value->id_ac] as $key2 => $value2)
                    <tr>
                    <td colspan=11>{{$value2->nama}}</td>                    
                        @if(isset($data3[$value2->id_ac]))
                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                <tr style="background-color: #e3e3e3;">
                                <td colspan=11>{{$value3->nama}}</td>
                                    @if(isset($data4[$value3->id_ac]))
                                        @php
                                        $total = 0;
                                        $total_deb = 0;
                                        $total_kre = 0;
                                        $total_deb_sa = 0;
                                        $total_kre_sa = 0;
                                        $total_deb_bi = 0;
                                        $total_kre_bi = 0;
                                        $total_tot_bi = 0;
                                        @endphp
                                        @foreach($data4[$value3->id_ac] as $key4 => $value4)
                                            <tr>
                                                <td></td>
                                                <td>{{$value4->ac_perush}}</td>
                                                <td>{{$value4->nama_ac_perush}}</td>
                                                <td>Rp. {{ number_format($sa_debit[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($sa_kredit[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_deb[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_kre[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($bulanini_deb[$value4->ac_perush]-$bulanini_kre[$value4->ac_perush], 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->debit, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->kredit, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($value4->debit-$value4->kredit, 0, ',', '.') }}</td>
                                                @php
                                                $total += $value4->debit-$value4->kredit;
                                                $total_deb += $value4->debit;
                                                $total_kre += $value4->kredit;
                                                $total_deb_sa += $sa_debit[$value4->ac_perush];
                                                $total_kre_sa += $sa_kredit[$value4->ac_perush];
                                                $total_deb_bi += $bulanini_deb[$value4->ac_perush];
                                                $total_kre_bi += $bulanini_kre[$value4->ac_perush];
                                                $total_tot_bi += $bulanini_deb[$value4->ac_perush]-$bulanini_kre[$value4->ac_perush];
                                                @endphp
                                            </tr>
                                        @endforeach
                                        <tr >
                                              <td colspan=3 rowspan=2 class="text-center">Sub Total {{$value3->nama}}</td>   
                                              <td>Rp. {{ number_format($total_deb_sa, 0, ',', '.') }}</td> 
                                              <td>Rp. {{ number_format($total_kre_sa, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_deb_bi, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_kre_bi, 0, ',', '.') }}</td>  
                                              <td>Rp. {{ number_format($total_tot_bi, 0, ',', '.') }}</td>   
                                              <td>Rp. {{ number_format($total_deb, 0, ',', '.') }}</td>    
                                              <td>Rp. {{ number_format($total_kre, 0, ',', '.') }}</td>   
                                              <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>                                  
                                        </tr>
                                        <tr >
                                            <td colspan=2 class="text-center">Rp. {{ number_format($total_deb_sa-$total_kre_sa, 0, ',', '.') }}</td> 
                                            <td colspan=3 class="text-center">Rp. {{ number_format($total_tot_bi, 0, ',', '.') }}</td>
                                            <td colspan=3 class="text-center">Rp. {{ number_format($total, 0, ',', '.') }}</td> 
                                        </tr>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            @endif
        @endif
    @endforeach
    </tbody>
</table>

@endif
</div>
</body>
</html>
<script>
$("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
</script>