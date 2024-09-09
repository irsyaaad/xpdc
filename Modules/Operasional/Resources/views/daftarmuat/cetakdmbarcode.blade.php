<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <script src="{{ asset('assets/base/bezier.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/base/jquery.signaturepad.js') }}" type="text/javascript"></script>
    <script type='text/javascript' src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Tahoma' rel='stylesheet' type='text/css'>
    <title>Cetak</title>
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
            font-size: 14px;
            height : 70px;
            vertical-align: text-top;
            text-align : center;
        }

    </style>
</head>
<body class="container">
    <div class="col-md-12 text-right" id="tombol" style="margin-top:20px">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        <button class="btn btn-sm btn-success" id="cetak"><i class="fa fa-print"></i>  Cetak</button>
    </div>
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
                <td>No. Manifest</td>
                <td> : </td>
                <td>{{$dm->kode_dm}}</td>
            </tr>
            @if ($dm->is_vendor)
            <tr>
                <td>Nama Vendor</td>
                <td> : </td>
                <td>@if (isset($dm->vendor->nm_ven))
                    {{$dm->vendor->nm_ven}}
                    @else
                    @isset($dm->perush_tujuan->nm_perush)
                    {{$dm->perush_tujuan->nm_perush}}
                    @endisset
                    @endif</td>
                </tr>
                @else
                <tr>
                    <td>Nama Kapal</td>
                    <td> : </td>
                    <td>@isset($dm->kapal->nm_kapal)
                        {{$dm->kapal->nm_kapal}}
                        @endisset</td>
                    </tr>
                    <tr>
                        <td>Sopir</td>
                        <td> : </td>
                        <td>@isset($dm->sopir->nm_sopir)
                            {{$dm->sopir->nm_sopir}}
                            @endisset</td>
                        </tr>
                        <tr>
                            <td>No Plat</td>
                            <td> : </td>
                            <td>@isset($dm->armada->no_plat)
                                {{$dm->armada->no_plat}}
                                @endisset</td>
                            </tr>
                            <tr>
                                <td>Berangkat / Tujuan</td>
                                <td> : </td>
                                <td>
                                    {{$dm->nm_dari}} / {{$dm->nm_tuju}}
                                </td>
                            </tr>

                            @endif

                            <tr>
                                <td>Tgl Berangkat / Est Tiba</td>
                                <td> : </td>
                                <td>
                                    ({{dateindo($dm->tgl_berangkat)}}) / ({{dateindo($dm->tgl_sampai)}})
                                </td>
                            </tr>
                            @if ($dm->id_layanan == 2)
                            <tr>
                                <td>No Container / No Seal</td>
                                <td> : </td>
                                <td>
                                    {{$dm->no_container}} / {{$dm->no_seal}}
                                </td>
                            </tr>
                            @endif

                        </table>
                        <br>
                        <table width="100%" class="t">
                            <thead>
                                <th id="n">No</th>
                                <th id="n">No Resi</th>
                                <th id="n">No Awb</th>
                                <th id="n">Tgl Masuk</th>
                                <th id="n">Pengirim / Kontak</th>
                                <th id="n">Penerima / Kontak</th>
                                <th id="n">Alamat Tujuan</th>
                                <th id="n">Tipe Kiriman</th>
                                <th id="n">Koli</th>
                                <th id="n">Berat / Volume / Kubik</th>
                                @if(Request::segment(3)=="cetak")
                                <th id="n">Tarif</th>
                                <th id="n">Netto</th>
                                @endif
                                <th id="n">Keterangan</th>
                            </thead>
                            <tbody>
                                @php
                                $total_koli = 0;
                                $total_berat = 0;
                                $total_volume = 0;
                                $total_netto = 0;
                                @endphp
                                @foreach ($carabayar as $key => $value2)
                                @isset($data[$value2->id_cr_byr_o])
                                @php
                                $no = 0;
                                @endphp
                                <tr class="t"><td colspan="11">{{strtoupper($value2->nm_cr_byr_o)}}</td></tr>
                                @foreach ($data[$value2->id_cr_byr_o] as $key2 => $value)
                                <tr>
                                    <td class="t">{{++$no}}</td>
                                    <td class="t" style="padding : 15px"><img alt='{{$value->id_koli}}' src='http://bwipjs-api.metafloor.com/?bcid=code128&text={{$value->kode_stt}}&scaleX=2&scaleY=1'/></td>
                                    <td class="t">{{$value->no_awb}}</td>
                                    <td class="t">{{dateindo($value->tgl_masuk)}}</td>
                                    <td class="t">{{$value->pengirim_nm}}<br>{{$value->pengirim_telp}}</td>
                                    <td class="t">{{$value->penerima_nm}}<br>{{$value->penerima_telp}}</td>
                                    <td class="t">{{$value->penerima_alm}}</td>
                                    <td class="t">{{$value->tipekirim->nm_tipe_kirim}}</td>
                                    <td class="t">{{$value->n_koli}}</td>
                                    <td class="t">{{$value->n_berat}} Kg<br>{{$value->n_volume}} KgV<br>{{$value->n_kubik}} M3</td>
                                    @if(Request::segment(3)=="cetak")
                                    <td class="t">
                                        @if($value->c_tarif == "1")
                                        {{ tonumber($value->n_tarif_brt) }}
                                        @elseif($value->c_tarif == "2")
                                        {{ tonumber($value->n_tarif_vol) }}
                                        @elseif($value->c_tarif == "3")
                                        {{ tonumber($value->n_hrg_borongan) }}
                                        @else
                                        {{ tonumber($value->n_hrg_kubik) }}
                                        @endif
                                    </td>
                                    <td class="t">{{ tonumber($value->c_total) }}</td>
                                    @endif
                                    <td class="t">{{$value->info_kirim}}</td>
                                </tr>
                                @php
                                $total_koli+=$value->n_koli;
                                $total_berat+=$value->n_berat;
                                $total_volume+=$value->n_volume;
                                $total_netto+= $value->c_total;
                                @endphp
                                @endforeach
                                @endisset

                                @endforeach
                                <tr>
                                    <td colspan="8" id="nx" class="text-center">Total</td>
                                    <td class="t">{{$total_koli}}</td>
                                    <td class="t">{{$total_berat}} Kg | {{$total_volume}} M3</td>
                                    @if(Request::segment(3)=="cetak")
                                    <td class="t"></td>
                                    <td class="t">{{ tonumber($total_netto) }}</td>
                                    @endif
                                    <td class="t"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <table width="100%">
                        <tr class="penutup">
                            <td>@if ($dm->is_vendor)
                                @if (isset($dm->vendor->nm_ven))
                                {{$dm->vendor->nm_ven}}
                                @else
                                @isset($dm->perush_tujuan->nm_perush)
                                {{$dm->perush_tujuan->nm_perush}}
                                @endisset
                                @endif
                                @else
                                {{$dm->perush_tujuan->nm_perush}}
                                @endif
                            </td>
                            <td>Sopir</td>
                            <td>{{ $perusahaan->nm_perush }}</td>
                        </tr>
                        <tr class="penutup">
                            <td>{{ $dm->nm_pj_tuju }}</td>
                            <td>@isset($dm->sopir->nm_sopir)
                                {{$dm->sopir->nm_sopir}}
                                @endisset</td>
                                <td>@isset($dm->user->nm_user)
                                    {{ $dm->user->nm_user }}
                                    @endisset</td>
                                </tr>
                            </table>
                        </body>
                        </html>
                        <script>
                            $("#cetak").click(function(){
                                $("#tombol").hide();
                                $("#ttd").hide();
                                window.print();
                            });


                        </script>
