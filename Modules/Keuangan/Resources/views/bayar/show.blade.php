@extends('template.document')

@section('data')

<div class="row">
    <div class="col-md-6">
        <h4><i class="fa fa-thumb-tack"></i>
            <b>DATA PEMBAYARAN</b>
        </h4>
    </div>

    <div class="col-md-6 text-right">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-warning">
            <i class="fa fa-reply"></i>	Kembali
        </a>
    </div>

    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="10%">No. Pembayaran</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->no_kwitansi)){{ strtoupper($data->no_kwitansi) }}@endif
                        </b>
                    </td>

                    <td width="10%">No. RESI</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->stt->kode_stt)){{ strtoupper($data->stt->kode_stt) }}@endif
                        </b>
                    </td>

                    <td width="10%">Nama Bayar</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->nm_bayar)){{ strtoupper($data->nm_bayar) }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Tanggal Bayar</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->tgl)){{ daydate($data->tgl).", ".dateindo($data->tgl) }}@endif
                        </b>
                    </td>

                    <td width="10%"> No. Refernsi</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->no_bayar)){{ $data->no_bayar }}@endif
                        </b>
                    </td>

                    <td width="10%">Keterangan</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->info)){{ $data->info }}@endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="10%"> Nominal</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->n_bayar)){{ "Rp. ".number_format($data->n_bayar, 0, ',', '.') }}@endif
                        </b>
                    </td>

                    <td width="10%">Cara Bayar</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->cara->nm_cr_byr_o)){{ strtoupper($data->cara->nm_cr_byr_o) }}@endif
                        </b>
                    </td>
                </tr>

            </thead>
        </table>
    </div>
    @if(isset($dp))
    <h4><i class="fa fa-thumb-tack"></i>
        <b>DATA DP</b>
    </h4>
    <div class="col-md-12" style="margin-top:0.5%">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <td width="10%">No. DP</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($dp->id_dp)){{ strtoupper($dp->id_dp) }}@endif
                        </b>
                    </td>

                    <td width="10%">Nama Bayar</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($data->nm_bayar)){{ strtoupper($data->nm_bayar) }}@endif
                        </b>
                    </td>

                    <td width="10%">Tanggal DP</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($dp->tgl_dp)){{ daydate($dp->tgl_dp).", ".dateindo($dp->tgl_dp) }}@endif
                        </b>
                    </td>

                    <td width="10%">Nominal DP</td>
                    <td width="2%"><b>:</b></td>
                    <td>
                        <b>
                            @if(isset($dp->n_dp)){{ "Rp. ".number_format($dp->n_dp, 0, ',', '.') }}@endif
                        </b>
                    </td>
                </tr>

            </thead>
        </table>
    </div>
    @endif

</div>
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
    .atas{
        font-size: 16px;
        text-align: center;
    }
</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
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
        <div class="atas">
            <p><b>KWITANSI</b></p>
            <p style="font-size: 16px;">No. {{ $data->no_kwitansi }}</p>
        </div>
        <hr class="hr">
        <table class="table table-borderless table-sm">
            <tr>
                <td width="25%">Telah Terima Dari</td>
                <td width="2%">:</td>
                <td>{{strtoupper($data->nm_bayar)}}</td>
            </tr>
            <tr>
                <td>Uang Sejumlah</td>
                <td>:</td>
                <td><b>{{strtoupper(terbilang($data->n_bayar))}} RUPIAH</b></td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>:</td>
                <td>{{$data->info}}</td>
            </tr>
            <tr>
                <td>No RESI</td>
                <td>:</td>
                <td>@if(isset($data->stt->kode_stt)){{strtoupper($data->stt->kode_stt)}}@endif</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>@if(isset($data->id_cr_byr)){{strtoupper($data->cara->nm_cr_byr_o)}}@endif @if(isset($data->id_bank))VIA {{strtoupper($data->bank->id_bank)}}@endif</td>
            </tr>
        </table>

        <br>

        <table width="100%" style="margin-top:20px;" class="penutup">
            <tr>
                <td rowspan="2" style="font-size: 18px"><b>@if(isset($data->n_bayar)){{ "Rp. ".number_format($data->n_bayar, 0, ',', '.') }},-@endif</b></td>
                <td colspan="2" class="text-right">{{$perusahaan->kotakab}}, {{dateindo($data->tgl)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">{{strtoupper($perusahaan->nm_perush)}},</td>
            </tr>
            <tr height="50px">
                <td>
                    @php
                    $text = $data->id_order_pay."/".$data->stt->kode_stt;
                    @endphp
                    <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl={{$text}}%2F&choe=UTF-8" title="Cek Resi Barang" />
                </td>
                <td class="text-right">
                    @if (isset($ttd))
                    {{-- <img id="canvasImage" width="150px"/> --}}
                    <img width="100px" src="{{url('generatettd/'.$ttd->id)}}" alt="ttd">
                    @else
                    <button class="btn btn-sm btn-success" id="ttd" onclick="showttd(1)"> TTD</button>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">@if ($data->user->nm_user)
                {{strtoupper($data->user->nm_user)}}
                @endif</td>
            </tr>
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
        .text-center{
            text-align : center;
        }
        .text-right{
            text-align : right;
        }
        .heading{
            text-align: center;
            font-size: 16px;
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
        .atas{
            margin-top : 20px;
            font-size: 18px;
            text-align: center;
            line-height: 5px;
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
            const data = {level : params, url : q, type : 't_order_pay', id_ref : ref};

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
