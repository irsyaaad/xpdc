<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL::asset('bb.png') }}" type="image/x-icon">
    <title>Cetak RUGILABA | Lsj Express Group</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style>
    @media print{
        @page
        {
            size: A4 portrait;
            /* size: landscape; */
        }
    }
    body {
        font-family: sans-serif !important;
        line-height: 15px;
        font-size: 12px;
        /* font-weight: bold; */
        color: #000;
    }
    th {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
    }

    .head{
        font-size: 18px;
        font-weight: bold;
        height: 50px;
    }
    .text-center{
        text-align : center;
    }
    .text-right{
        text-align : right;
    }
    .heading{
        text-align: center;
        font-size: 14px;
        line-height : 15px;
    }
    .kepada{
        line-height: 15px;
    }
    .hr{
        border-top: 1px solid red;
        margin-top : 10px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .penutup{
        font-size: 14px;
    }
    .tr-bold{
        font-weight: bold !important;
    }
    .atas{
        line-height : 5px;
    }
</style>
</head>
<body>
    <div class="container">
        <table width="100%">
            <tr width="30%">
                <td style="text-align: center;">
                    @php
                    
                    if (Storage::exists('public/uploads/perusahaan/'.$perusahaan->logo)) {
                        $path = 'public/uploads/perusahaan/'.$perusahaan->logo;
                        
                        $full_path = Storage::path($path);
                        $base64 = base64_encode(Storage::get($path));
                        $image = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
                        $perusahaan->logo = $image;
                    }
                    
                    @endphp
                    <img src="{{ $perusahaan->logo }}" style="height: 50px; margin-top:-20px">
                </td>
                <td class="heading">
                    <center>
                        <b style="font-size:16px">{{ strtoupper($perusahaan->nm_perush) }}</b><br>
                        <label style="font-size:12px;">
                            {!! $perusahaan->header !!}
                        </label>
                    </center>   
                </td>                
            </tr>            
        </table>
        <hr>
    </div>
    <div class="atas">
        <p class="text-center" style="font-size:15px;"><b>LAPORAN NERACA</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
        @php $total_aktiva = 0; @endphp
        <table width="100%">
            <tr>
                <td>
                    <table width="100%">
                        @foreach($data1 as $key => $value)
                            @if($value->id_ac == 1)
                                <tr class="tr-bold"><td>{{$value->nama}}</td></tr>
                                    @if(isset($data2[$value->id_ac]))
                                        @foreach($data2[$value->id_ac] as $key2 => $value2)
                                            <tr class="tr-bold">
                                                <td style="padding-left:10px">
                                                    <p>{{$value2->nama}}</p>
                                                </td>
                                            </tr>
                                                @if(isset($data3[$value2->id_ac]))
                                                    @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                        <tr>
                                                            <td style="padding-left:50px">{{$value3->nama}}</td>

                                                            {{-- <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td> --}}
                                                            @if(isset($nilai[$value3->id_ac]))
                                                                <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                                @php
                                                                    $total_aktiva+=$nilai[$value3->id_ac];
                                                                @endphp
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                @endif
                                        @endforeach

                                    @endif
                            @endif
                        @endforeach
                    </table>
                </td>
                <td style="vertical-align: top;">
                    @php $total_pasiva = 0;  @endphp
                    <table width="100%">
                        @foreach($data1 as $key => $value)
                            @if($value->id_ac == 2)
                                <tr class="tr-bold"><td>{{$value->nama}}</td></tr>
                                    @if(isset($data2[$value->id_ac]))
                                        @foreach($data2[$value->id_ac] as $key2 => $value2)
                                            <tr class="tr-bold">
                                                <td style="padding-left:10px">
                                                    <p>{{$value2->nama}}</p>
                                                </td>
                                            </tr>
                                            @if(isset($data3[$value2->id_ac]))
                                                @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                    <tr>
                                                        <td style="padding-left:50px">{{$value3->nama}}</td>
                                                        @if(isset($nilai[$value3->id_ac]))
                                                            <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                            @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                            @endif
                        @endforeach
                        @foreach($data1 as $key => $value)
                            @if($value->id_ac == 3)
                                <tr class="tr-bold"><td>{{$value->nama}}</td></tr>
                                    @if(isset($data2[$value->id_ac]))
                                        @foreach($data2[$value->id_ac] as $key2 => $value2)
                                            
                                        @if(isset($data3[$value2->id_ac]))
                                            @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                <tr>
                                                    <td style="padding-left:50px">{{$value3->nama}}</td>
                                                    @if(isset($lababerjalan) and $value3->id_ac == 321)
                                                        <td class="text-right"> {{ number_format($lababerjalan, 0, ',', '.') }}</td>
                                                        @php $total_pasiva+=$lababerjalan @endphp
                                                    @elseif(isset($nilai[$value3->id_ac]))
                                                        <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                        @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif

                                        @endforeach
                                    @endif
                            @endif
                        @endforeach
                    </table>
                </td>
            </tr>
            {{-- total --}}
            <tr>
                <td colspan="2">
                    <hr class="hr">
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr class="tr-bold">
                            <td>Total Aktiva</td>
                            <td class="text-right"> {{ number_format($total_aktiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table width="100%">
                        <tr class="tr-bold">
                            <td>Total Pasiva</td>
                            <td class="text-right"> {{ number_format($total_pasiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    </body>    
    </html>
    