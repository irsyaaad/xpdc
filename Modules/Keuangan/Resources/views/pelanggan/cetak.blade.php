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
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
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
        <table width="100%" class="datanya">
            <tr>
                <td>Id Pelanggan</td>
                <td>:</td>
                <td>{{$pelanggan->id_pelanggan}}</td>
            </tr>
            <tr>
                <td>Nama Pelanggan</td>
                <td>:</td>
                <td>{{$pelanggan->nm_pelanggan}}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{$pelanggan->alamat}}</td>
            </tr>
            <tr>
                <td>No Telp</td>
                <td>:</td>
                <td>{{$pelanggan->telp}}</td>
            </tr>

        </table>
        <div class="tengah">
            <p class="ptengah">PIUTANG STT YANG BELUM LUNAS</p>
        </div>
        <table width="100%" class="t">
            <thead>
                <th>No.</th>
                <th>No Stt</th>
                <th>No AWB</th>
                <th>No Invoice</th>
                <th>Penerima - Tujuan</th>
                <th>Tgl Masuk</th>
                <th>Koli</th>
                <th>Kg</th>
                <th>KgV</th>
                <th>M<sup>3</sup></th>
                <th>Piutang</th>
                <th>Bayar</th>
                <th>Kurang</th>
            </thead>
            <tbody>
                @php
                $no = 0; $koli = 0; $berat = 0; $volume = 0; $kubik = 0; $piutang = 0; $bayar=0; $kurang=0;
                @endphp
                @foreach($belum as $key => $value)
                <tr>
                    <td class="t">{{++$no}}</td>
                    <td class="t"><span style="font-weight:bold;">{{ $value->kode_stt }}</span></td>
                    <td class="t">{{ $value->no_awb }}</td>
                    <td class="t">{{ $value->kode_invoice }}</td>
                    <td class="t">{{ $value->penerima_nm }} - {{$value->tujuan}}</td>
                    <td class="t">@if(isset($value->tgl_masuk)){{ dateindo($value->tgl_masuk) }}@endif</td>
                    <td class="t">
                        @if(isset($value->n_koli))
                        {{ $value->n_koli }}
                        @php
                        $koli+=$value->n_koli;
                        @endphp
                        @endif
                    </td>
                    <td class="t">
                        @if(isset($value->n_berat)){{ $value->n_berat }} Kg
                        @php
                        $berat+=$value->n_berat;
                        @endphp
                        @endif
                    </td>
                    <td class="t">
                        @if(isset($value->n_volume)){{ $value->n_volume }} KgV
                        @php
                        $volume+=$value->n_volume;
                        @endphp
                        @endif
                    </td>
                    <td class="t">
                        @if(isset($value->n_kubik)){{ $value->n_kubik }} M3
                        @php
                        $kubik+=$value->n_kubik;
                        @endphp
                        @endif
                    </td>
                    <td class="t">
                        @if(isset($value->piutang))Rp. {{ number_format($value->piutang, 0, ',', '.') }}
                        @php
                        $piutang+=$value->piutang;
                        @endphp
                        @endif
                    </td>
                    <td class="t">
                        @if(isset($value->bayar))Rp. {{ number_format($value->bayar, 0, ',', '.') }}
                        @php
                        $bayar+=$value->bayar;
                        @endphp
                        @endif
                    </td>
                    <td class="t">
                        @if(isset($value->kurang))Rp. {{ number_format($value->kurang, 0, ',', '.') }}
                        @php
                        $kurang+=$value->kurang;
                        @endphp
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr class="penutup">
                    <td class="t" colspan="6">Total</td>
                    <td class="t">{{$koli}}</td>
                    <td class="t">{{$berat}} Kg</td>
                    <td class="t">{{$volume}} KgV</td>
                    <td class="t">{{$kubik}} M3</td>
                    <td class="t">Rp. {{ number_format($piutang, 0, ',', '.') }}</td>
                    <td class="t">Rp. {{ number_format($bayar, 0, ',', '.') }}</td>
                    <td class="t">Rp. {{ number_format($kurang, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <textarea id="printing-css" style="display:none;">
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
            font-size : 10px;
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
        .penutup{
            background-color : #e6e6e6;
        }
        .tengah{
            text-align: center;
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
