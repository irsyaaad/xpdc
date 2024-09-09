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
        text-align: center;
        font-size : 14px;
    }
    .n {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: left;
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
    }
    .hr{
        border-top: 1px solid red;
        margin-top : 10px;
    }
    .hrhead{
        border: 1px solid black;
    }
    .penutup{
        background-color : #e6e6e6;
    }
    .tengah{
        margin-top: 5px;
        text-align: center;
    }

</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1).$urls) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">
    <div class="container" style=" margin-top:10px;">
        <table width="100%">
            <tr>
                <td rowspan="3" width="20%">
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
    <div class="container" style="margin-top:20px">

        <div class="tengah">
            <p class="ptengah">DATA PIUTANG PELANGGAN</p>
            <p class="ptengah">{{ strtoupper($perusahaan->nm_perush) }}</p>
        </div>

        <table width="100%" class="t">
            <thead>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>No Telpn</th>
                <th>Alamat</th>
                <th>Jml Kiriman (STT)</th>
                <th>Jml STT Lunas</th>
                <th>Nominal</th>
                <th>Terbayar</th>
                <th>Sisa</th>
            </thead>
            <tbody>
                @php
                $no = 0;
                @endphp
                @foreach ($group as $key => $value)
                    @isset($data[$value->id_plgn_group])
                    <tr>
                        <td class="n" colspan="9">
                            <b>{{$value->nm_group}}</b>
                        </td>
                    </tr>
                        @php
                            $total_stt = 0;
                            $total_stt_lunas = 0;
                            $total_nominal = 0;
                            $total_terbayar = 0;
                            $total_sisa = 0;
                        @endphp
                        @foreach ($data[$value->id_plgn_group] as $key2 => $value2)
                            <tr>
                                <td class="t">{{++$no}}</td>
                                <td class="n">{{$value2->nm_pelanggan}}</td>
                                <td class="t">{{$value2->telp}}</td>
                                <td class="n">@if(isset($value2->alamat)){{$value2->alamat}}@endif</td>
                                <td class="t">@if(isset($value2->total_stt)){{$value2->total_stt}}@endif</td>
                                <td class="t">@if(isset($value2->total_stt_byr)){{$value2->total_stt_byr}}@endif</td>
                                <td class="t">@if(isset($value2->total)) {{ number_format($value2->total, 0, ',', '.') }}@endif</td>
                                <td class="t">@if(isset($value2->bayar)) {{ number_format($value2->bayar, 0, ',', '.') }}@endif</td>
                                <td class="t">@if(isset($value2->kurang)) {{ number_format($value2->kurang, 0, ',', '.') }}@endif</td>
                            </tr>
                            @php
                                $total_stt += $value2->total_stt;
                                $total_stt_lunas += $value2->total_stt_byr;
                                $total_nominal += $value2->total;
                                $total_terbayar += $value2->bayar;
                                $total_sisa += $value2->kurang;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="4"></td>
                            <td class="t">{{ $total_stt }}</td>
                            <td class="t">{{ $total_stt_lunas }}</td>
                            <td class="t">{{ toNumber($total_nominal) }}</td>
                            <td class="t">{{ toNumber($total_terbayar) }}</td>
                            <td class="t">{{ toNumber($total_sisa) }}</td>
                        </tr>
                    @endisset
                @endforeach
            </tbody>
        </table>
    </div>
    <br>
    <textarea id="printing-css" style="display:none;">
        @media print{
            @page
            {
                size: A4 landscape;
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
            font-size : 10px;
        }
        .t {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size : 9px;
        }
        .n {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: left;
            font-size : 9px;
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
        }
        .hr{
            border-top: 1px solid red;
            margin-top : 10px;
        }
        .hrhead{
            border: 1px solid black;
        }
        .tengah{
            text-align: center;
            line-height : 5px;
        }
        .ptengah{
            font-size : 10px;
            font-weight : bold;
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
