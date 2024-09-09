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
        text-align: left;
        font-size : 12px;
    }
    .total {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
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
    .hrhead{
        border: 1px solid black;
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
    <div class="text-center">
        <p style="font-size: 16px;"><b>DAFTAR STT BELUM LUNAS</b></p>
    </div>
    <div class="container">
        <table width="100%" style="border-collapse: collapse;">
            <thead>
                <th>No</th>
                <th>No. STT</th>
                <th>Tgl Masuk</th>
                <th>Pelanggan</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kurang</th>
            </thead>

            <tbody>
                @php
                $n_total = 0;
                $n_bayar = 0;
                $n_kurang= 0;
                @endphp
                @if(count($data)==null)
                <tr>
                    <td colspan="11" class="text-center"> Tidak ada data </td>
                </tr>
                @endif
                @foreach($data as $key => $value)
                <tr>
                    <td class="total">{{ $key+1 }}</td>
                    <td class="total">{{ strtoupper($value->kode_stt) }}</td>
                    <td class="t">{{ dateindo($value->tgl_masuk) }}</td>
                    <td class="t">
                        @if(isset($value->nm_pelanggan))
                        {{ strtoupper($value->nm_pelanggan) }}
                        @endif
                    </td>
                    <td class="t">
                        @if(isset($value->asal)){{ $value->asal }}@endif
                    </td>
                    <td class="t">
                        @if(isset($value->tujuan)){{ $value->tujuan}}@endif
                    </td>
                    <td class="t">{{ toRupiah($value->c_total) }}</td>
                    <td class="t">{{ toRupiah($value->n_bayar) }}</td>
                    <td class="t">
                        @php
                            $kurang = $value->c_total - $value->n_bayar;
                        @endphp
                        {{ toRupiah($kurang) }}
                    </td>
                    @php
                    $n_total += $value->c_total;
                    $n_bayar += $value->n_bayar;
                    $n_kurang += ($value->c_total - $value->n_bayar);
                    @endphp
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" class="total"><b>Total</b></td>
                    <td class="t">{{ toRupiah($n_total) }}</td>
                    <td class="t">{{ toRupiah($n_bayar) }}</td>
                    <td class="t">{{ toRupiah($n_kurang) }}</td>
                </tr>
            </tbody>
        </table>

        <br>



    </div>
</div>

<textarea id="printing-css" style="display:none;">
    @media print{
        @page
        {
            /* size: A4 portrait; */
            size: A4 landscape;
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
        text-align: left;
        font-size : 12px;
    }
    .total {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
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
    .hrhead{
        border: 1px solid black;
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
