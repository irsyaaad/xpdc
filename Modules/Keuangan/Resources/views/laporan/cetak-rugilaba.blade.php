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
    <a href="{{ url($filter["back"]) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
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
        <p class="text-center"><b>LAPORAN RUGILABA {{ strtoupper($perusahaan->nm_perush) }}</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
        @php
        $total_pendapatan = 0;
        $total_rugilaba   = 0;
        @endphp
        <table width="100%">
            @foreach ($data1 as $key => $value)
                @if ($value->id_ac == 4)
                    <tr class="tr-bold">
                        <td>{{ $value->nama }}</td>
                    </tr>
                    @if (isset($data2[$value->id_ac]))
                        @foreach ($data2[$value->id_ac] as $key2 => $value2)
                            @if (isset($data3[$value2->id_ac]))
                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                    {{-- @if (isset($nilai[$value3->id_ac]) && $nilai[$value3->id_ac] != 0) --}}
                                        <tr>
                                            <td style="padding-left:50px"><a
                                                    href="{{ route('showrugilaba', [
                                                        'id_ac' => $value3->id_ac,
                                                        'dr_tgl' => $filter['dr_tgl'],
                                                        'sp_tgl' => $filter['sp_tgl'],
                                                    ]) }}"
                                                    style="color:black;">{{ $value3->nama }}</a>
                                            </td>
                                            @if (isset($nilai[$value3->id_ac]))
                                                <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    if ($value3->id_ac == 411 or $value3->id_ac == 412) {
                                                        $total_pendapatan -= $nilai[$value3->id_ac];
                                                    } else {
                                                        $total_pendapatan += $nilai[$value3->id_ac];
                                                    }
                                                @endphp
                                            @endif
                                            <td class="text-center">{{ round(($nilai[$value3->id_ac]/($total_omset)) * 100,2) }} %</td>
                                        </tr>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                        @endforeach
                        <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                            <td class="text-center"> TOTAL PENDAPATAN</td>
                            <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                            <td class="text-center">{{ round(($total_pendapatan/($total_omset)) * 100,2) }} %</td>
                        </tr>
                    @endif
                @endif
            @endforeach

            @foreach ($data1 as $key => $value)
                @if ($value->id_ac == 5)
                    <tr>
                        <td>{{ $value->nama }}</td>
                    </tr>
                    @if (isset($data2[$value->id_ac]))
                        @foreach ($data2[$value->id_ac] as $key2 => $value2)
                            @php $total = 0; @endphp
                            <td style="padding-left:10px">
                                <p>{{ $value2->nama }}</p>
                            </td>
                            @if (isset($data3[$value2->id_ac]))
                                @foreach ($data3[$value2->id_ac] as $key3 => $value3)
                                    {{-- @if (isset($nilai[$value3->id_ac]) && $nilai[$value3->id_ac] != 0) --}}
                                    <tr>
                                        <td style="padding-left:50px"><a
                                                href="{{ route('showrugilaba', [
                                                    'id_ac' => $value3->id_ac,
                                                    'dr_tgl' => $filter['dr_tgl'],
                                                    'sp_tgl' => $filter['sp_tgl'],
                                                ]) }}"
                                                style="color:black;">{{ $value3->nama }}</a></td>
                                        @if (isset($nilai[$value3->id_ac]))
                                            <td class="text-right"> {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                            @if ($value3->tipe == "K")
                                                @php
                                                    $total += $nilai[$value3->id_ac];
                                                @endphp
                                            @else
                                                @php
                                                    $total -= $nilai[$value3->id_ac];
                                                @endphp
                                            @endif
                                        @endif
                                        <td class="text-center">{{ abs(round(($nilai[$value3->id_ac]/($total_omset)) * 100,2)) }} %</td>
                                    </tr>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                            <tr>
                                <td class="text-center"> TOTAL {{ $value2->nama }}</td>
                                <td class="text-right"> {{ number_format($total, 0, ',', '.') }}</td>
                                <td class="text-center">{{ abs(round(($total/($total_omset)) * 100,2)) }} %</td>
                                @php $total_pendapatan+=$total @endphp
                            </tr>
                            <tr style="background-color: rgb(221, 218, 218)" class="tr-bold">
                                @if ($value2->id_ac == 50)
                                    <td class="text-center"> LABA KOTOR </td>
                                @elseif ($value2->id_ac == 51)
                                    <td class="text-center"> LABA OPERASIONAL </td>
                                @elseif ($value2->id_ac == 52)
                                    <td class="text-center"> LABA SETELAH POKOK DAN BUNGA </td>
                                @elseif ($value2->id_ac == 53)
                                    <td class="text-center"> LABA SEBELUM PAJAK </td>
                                @elseif ($value2->id_ac == 54)
                                    <td class="text-center"> LABA SETELAH PAJAK </td>
                                @endif
                                <td class="text-right"> {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                <td class="text-center">{{ abs(round(($total_pendapatan/($total_omset)) * 100,2)) }} %</td>
                            </tr>
                        @endforeach
                    @endif
                @endif
            @endforeach

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
