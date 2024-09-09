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

</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">
    <div class="container">
        <table width="100%">
            <tr width="30%">
                <td rowspan="3">
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
    <div class="container">
        <table width="100%" class="kepada">
            <tr>
                <td colspan="2" class="head text-center" >INVOICE HANDLING</td>
            </tr>
            <tr></tr>
            <tr>
                <td>Tanggal : </td>
                <td class="text-right">Kode Invoice : {{$invoice->kode_invoice}}</td>
            </tr>
            <tr>
                <td>{{dateindo($invoice->tgl_invoice)}}</td>
                <td></td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td></td>
                <td class="text-right">Kepada : </td>
            </tr>
            <tr>
                @if (Request::segment(1) == "invoicehandling")
                <td colspan="2" class="text-right">{{$invoice->nm_perush}}</td>
                @else
                <td colspan="2" class="text-right">{{$perushtj->nm_perush}}</td>
                @endif

            </tr>
            <tr>
                @if (Request::segment(1) == "invoicehandling")
                <td colspan="2" class="text-right">{{$invoice->alamat}}</td>
                @else
                <td colspan="2" class="text-right">{{$perushtj->alamat}}</td>
                @endif
            </tr>
            <tr>
                @if (Request::segment(1) == "invoicehandling")
                <td colspan="2" class="text-right">{{$invoice->telp}}</td>
                @else
                <td colspan="2" class="text-right">{{$perushtj->telp}}</td>
                @endif
            </tr>
        </table>
        <hr class="hr">
        <div style="line-height: 3px">
            <p>Kepada, Yth</p>
            @if (Request::segment(1) == "invoicehandling")
            <p>{{$invoice->nm_perush}}</p>
            @else
            <p>{{$perushtj->nm_perush}}</p>
            @endif
            <p>Di Tempat</p>
            <br>
            <p>Dengan ini kami lampirkan Invoice untuk tagihan biaya handling (Penerusan) anda,</p>
            <p>dengan rincian sebagai berikut :</p>
        </div>

        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <th rowspan="2">Group Biaya</th>
                <th rowspan="2">Kelompok</th>
                <th rowspan="2">Nomor Handling</th>
                <th rowspan="2">Nomor DM</th>
                <th rowspan="2">Nomor STT</th>
                <th colspan="3" class="text-center">Nominal</th>
            </thead>

            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($biaya as $key => $value)
                <tr>
                    <td class="t">{{ $value->nm_biaya_grup }}</td>
                    <td class="t">{{ $value->klp }}</td>
                    <td class="t">{{ $value->kode_handling }}</td>
                    <td class="t">{{ $value->kode_dm }}</td>
                    <td class="t">{{ $value->kode_stt }}</td>

                    <td class="t">{{ torupiah($value->nominal) }}</td>
                    @php
                    $total+=$value->nominal;
                    @endphp
                </tr>
                @endforeach
                <tr>
                    <td class="t text-center" colspan="5">Total</td>
                    <td class="t text-center">{{ torupiah($total) }}</td>
                </tr>
                <tr>
                    <td class="t" colspan="6"><b>Terbilang : {{ terbilang($total) }} Rupiah</b></td>
                </tr>
            </tbody>
        </table>

        <br>

        <table width="100%" style="margin-top:20px" class="penutup">
            <tr>
                <td class="text-right">{{$perusahaan->kotakab}}, {{dateindo($invoice->tgl_invoice)}}</td>
            </tr>
            <tr>
                <td class="text-right">Hormat Kami,</td>
            </tr>
            <tr height="50px">
                <td class="text-right">
                    @if (isset($ttd))
                        {{-- <img id="canvasImage" width="150px"/> --}}
                        <img width="100px" src="{{url('generatettd/'.$ttd->id)}}" alt="ttd">
                    @else
                        @if (Request::segment(1) == "invoicehandling")
                            <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(1)"> TTD</button>
                        @endif
                    @endif
                </td>
            </tr>
            <tr><td class="text-right">{{strtoupper($perusahaan->nm_perush)}}</td></tr>
        </table>

    </div>
</div>

<textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            size: A4 portrait;
            /* size: landscape; */
        }
        #tombol{
            display: none !important;
        }
        #ttd{
            display: none !important;
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
</textarea>
<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>
<script src="{{ asset('assets/base/bezier.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/base/jquery.signaturepad.js') }}" type="text/javascript"></script>
<script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
<script>
    function showttd(params) {
        var q = "{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3))}}";
        console.log(q);
        var url = "{{url('/createdttd')}}";
        console.log(url);
        var ref = "{{Request::segment(2)}}";
        const data = {level : params, url : q, type : 'invoicehandling', id_ref : ref};

        var urlParam = []

        for (var i in data){
            urlParam.push(encodeURI(i) + "=" + encodeURI(data[i]));
        }
        console.log(urlParam);
        location.href = "{{url('/createttd')}}?"+ urlParam.join("&");
    }
    $("#cetak").click(function(){
        $("#tombol").hide();
        window.print();
    });
    @isset($ttd)
    var ttd = "{{$ttd->ttd_admin}}";
    document.getElementById("canvasImage").src="data:image/gif;base64,"+ttd;
    @endisset

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
