@extends('template.document2')
@section('data')
@section('style')
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
        line-height: 20px;
        font-size: 15px;
        /* font-weight: bold; */
        color: #000;
    }
    th {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 14px;
    }
    td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size : 14px;
    }
    .datanya{
        font-family: sans-serif !important;
        line-height: 12px;
        font-size: 12px;
        /* font-weight: bold; */
        color: #000;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        font-size : 14px;
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
        border: 0px
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
        height : 70px;
        vertical-align: text-top;
        text-align : center;
    }
    .atas{
        line-height : 5px;
        margin-top : 20px;
    }

</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">
    <div class="container" style=" margin-top:10px;">
        <table width="100%">
            <tr>
                <td rowspan="3" width="20%" class="heading">
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
                </td>
                <td class="heading">{{ strtoupper($perusahaan->nm_perush) }}</td>
            </tr>
            <tr><td class="heading">{{ $perusahaan->alamat }},{{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</td></tr>
            <tr><td class="heading">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</td></tr>
        </table>
    </div>
    <hr class="hrhead">
    <div class="atas">
        <p class="text-center"><b>LAPORAN RUGILABA TAHUNAN {{ strtoupper($perusahaan->nm_perush) }}</b></p>
        <p class="text-center"><b>Tahun : {{$filter['tahun']}}</b></p>
    </div>
    <div class="container" style="margin-top:20px">
        @php
            $total_pendapatan = [0,0,0,0,0,0,0,0,0,0,0,0,0];
            $total_rugilaba   = 0;
            $total            = 0;
        @endphp
        <table width="100%" class="t">
            <thead>
                <th>Nama Account</th>
                @php
                    $bulan = array (
                    1 =>   'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Jul',
                    8 => 'Ags',
                    9 => 'Sep',
                    10 => 'Okt',
                    11 => 'Nov',
                    12 => 'Des'
                );
                @endphp
                @foreach ($bulan as $item)
                    <th>{{$item}}</th>
                @endforeach
            </thead>
            <tbody>
                @foreach($data1 as $key => $value)
                    @if($value->id_ac == 4)
                        <tr><td colspan="13">{{$value->nama}}</td></tr>
                        @if(isset($data2[$value->id_ac]))
                            @foreach($data2[$value->id_ac] as $key2 => $value2)
                                @if(isset($data3[$value2->id_ac]))
                                    @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px">{{$value3->nama}}</td>
                                            @for ($i =1; $i<=12; $i++)
                                                @if (isset($data[$i]))
                                                    @if(isset($data[$i][$value3->id_ac]))
                                                        <td>Rp. {{ number_format($data[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                                        @php
                                                            if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                                $total_pendapatan[$i]-=$data[$i][$value3->id_ac];
                                                            }else{
                                                                $total_pendapatan[$i]+=$data[$i][$value3->id_ac];
                                                            }
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endfor
                                        </tr>
                                        @php

                                        @endphp
                                    @endforeach
                                @endif
                            @endforeach
                            <tr>

                                <td class="text-center">Sub Total PENDAPATAN</td>
                                @for ($i=1; $i<=12; $i++)
                                    <td>Rp. {{ number_format($total_pendapatan[$i], 0, ',', '.') }}</td>
                                @endfor
                            </tr>
                        @endif
                    @endif
                @endforeach

                @foreach($data1 as $key => $value)
                    @if($value->id_ac == 5)
                        <tr><td colspan="13">{{$value->nama}}</td></tr>
                        @if(isset($data2[$value->id_ac]))
                            @foreach($data2[$value->id_ac] as $key2 => $value2)
                                @php $total = [0,0,0,0,0,0,0,0,0,0,0,0,0]; @endphp
                                <td style="padding-left:10px" colspan="13"><p>{{$value2->nama}}</p></td>
                                @if(isset($data3[$value2->id_ac]))
                                    @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px">{{$value3->nama}}</td>
                                            @for ($i=1; $i<=12; $i++)
                                                @isset($data[$i])
                                                    @if(isset($data[$i][$value3->id_ac]))
                                                        <td>Rp. {{ number_format($data[$i][$value3->id_ac], 0, ',', '.') }}</td>
                                                        @php
                                                            $total[$i]+=$data[$i][$value3->id_ac];
                                                        @endphp
                                                    @endif
                                                @endisset
                                            @endfor
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                <td class="text-center">Sub Total {{$value2->nama}}</td>
                                @for ($i=1; $i<=12; $i++)
                                    <td>Rp. {{ number_format($total[$i], 0, ',', '.') }}</td>
                                    @php $total_pendapatan[$i]-=$total[$i] @endphp
                                @endfor


                                </tr>
                                <tr>
                                <td class="text-center">Sub Total</td>
                                @for ($i=1; $i<=12; $i++)
                                    <td>Rp. {{ number_format($total_pendapatan[$i], 0, ',', '.') }}</td>
                                @endfor
                                </tr>
                            @endforeach

                        @endif
                    @endif
                @endforeach
            </tbody>

        </table>
    </div>
    <br>



    <br>

    <textarea id="printing-css" style="display:none;">
        @media print{
            @page
            {
                /* size: A4 portrait; */
                size: A4 landscape;
            }
        }
        body {
            font-family: sans-serif !important;
            line-height: 20px;
            font-size: 15px;
            /* font-weight: bold; */
            color: #000;
        }
        th {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size : 14px;
        }
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font-size : 14px;
        }
        .datanya{
            font-family: sans-serif !important;
            line-height: 12px;
            font-size: 12px;
            /* font-weight: bold; */
            color: #000;
        }
        .t {
            border: 1px solid black;
            border-collapse: collapse;
            font-size : 14px;
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
            border: 0px
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
            height : 70px;
            vertical-align: text-top;
            text-align : center;
        }
        .atas{
            line-height : 5px;
            margin-top : 20px;
        }
    </textarea>
    <iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
</div>
<script>
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
    function printDiv(elementId) {
        var a = document.getElementById('printing-css').value;
        var b = document.getElementById(elementId).innerHTML;
        window.frames["print_frame"].document.title = document.title;
        window.frames["print_frame"].document.body.innerHTML = '<style>' + a + '</style>' + b;
        window.frames["print_frame"].window.focus();
        window.frames["print_frame"].window.print();
    }
</script>
@endsection
