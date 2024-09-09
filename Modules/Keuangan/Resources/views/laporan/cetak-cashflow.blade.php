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
    <a href="{{ $filter["back"] }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
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
    <div class="atas">
        <p class="text-center"><b>LAPORAN CASHFLOW {{ strtoupper($perusahaan->nm_perush) }}</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
    <table class="table table-borderless table-sm ">
        @php
            $total_cashin = 0;
            $total_cashout = 0;
        @endphp
        <tr>
            <td>CASH IN</td>
        </tr>
        @foreach ($head as $key => $value)
            @if ($value->tipe == 1)
                <tr>
                    <td>{{$value->nama_cashflow}}</td>
                </tr>
                @if (isset($child[$value->id_cf]))
                    @foreach ($child[$value->id_cf] as $key2 => $value2)
                        <tr>
                            <td style="padding-left: 100px"><a href="{{ route('showcashflow', [
                                'id_ac' => $value2->id_cf, 
                                'dr_tgl' => $filter['dr_tgl'], 
                                'sp_tgl' => $filter['sp_tgl'],
                                ]) }}" style="color:black;">{{$value2->nama_cashflow}}</a></td>
                            <td>
                                @if (isset($total[$value2->id_cf]))
                                    Rp. {{ number_format($total[$value2->id_cf], 0, ',', '.') }}
                                    @php
                                        $total_cashin+=$total[$value2->id_cf];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td class="text-center">SUB TOTAL {{$value->nama_cashflow}}</td>
                        <td></td>
                    </tr> --}}
                @endif
            @endif
        @endforeach
        <tr>
            <td style="padding-left: 100px">
                Pemasukan Lain (Pemasukan yang blum di Mapping)
                <ul>
                    @if (isset($ac_in_belum_mapping))
                        @foreach ($ac_in_belum_mapping as $key => $value)
                            <li>{{$value}}</li>
                        @endforeach
                    @endif
                </ul>
            </td>
            <td>
                Rp. {{ number_format($total["inlain"], 0, ',', '.') }}
                @php
                    $total_cashin+=$total["inlain"];
                @endphp
            </td>
        </tr>
        <tr>
            <td class="text-center">TOTAL CASH IN</td>
            <td>Rp. {{ number_format($total_cashIn, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>CASH OUT</td>
        </tr>
        @foreach ($head as $key => $value)
            @if ($value->tipe == 2)
                <tr>
                    <td>{{$value->nama_cashflow}}</td>
                </tr>
                @if (isset($child[$value->id_cf]))
                    @foreach ($child[$value->id_cf] as $key2 => $value2)
                        <tr>
                            <td style="padding-left: 100px"><a href="{{ route('showcashflow', [
                                'id_ac' => $value2->id_cf, 
                                'dr_tgl' => $filter['dr_tgl'], 
                                'sp_tgl' => $filter['sp_tgl'],
                                ]) }}" style="color:black;">{{$value2->nama_cashflow}}</a></td>
                            <td>
                                @if (isset($total[$value2->id_cf]))
                                    Rp. {{ number_format($total[$value2->id_cf], 0, ',', '.') }}
                                    @php
                                        $total_cashout+=$total[$value2->id_cf];
                                    @endphp
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-center">SUB TOTAL {{$value->nama_cashflow}}</td>
                        <td></td>
                    </tr>
                @endif
            @endif
        @endforeach
        <tr>
            <td style="padding-left: 100px">
                Pengeluaran Lain (Pengeluaran yang blum di Mapping)
                <ul>
                    @if (isset($ac_out_belum_mapping))
                        @foreach ($ac_out_belum_mapping as $key => $value)
                            <li>{{$value}}</li>
                        @endforeach
                    @endif
                </ul>
            </td>
            <td>
                Rp. {{ number_format($total["outlain"], 0, ',', '.') }}
                @php
                    $total_cashout+=$total["outlain"];
                @endphp
            </td>
        </tr>
        <tr>
            <td class="text-center">TOTAL CASH OUT</td>
            <td>Rp. {{ number_format($total_cashOut, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-center">Surplus / Defisit Setelah Investasi</td>
            @php
                $total_akhir = $total_cashIn - $total_cashOut;
            @endphp
            <td>Rp. {{ number_format($total_akhir-$saldo_awal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-center">Saldo Awal</td>
            <td>Rp. {{ number_format($saldo_awal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-center">Saldo Akhir</td>
            <td>Rp. {{ number_format($total_akhir, 0, ',', '.') }}</td>
        </tr>
    </table>
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
            .atas{
                line-height : 5px;
                margin-top : 20px;
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
