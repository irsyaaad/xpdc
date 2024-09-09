    @extends('template.document2')
    @section('data')
    @section('style')
    <style>
        @media print{
            @page
            {
                /* size: 5.5in 9.4in ; */
                /* size: portrait; */
                size: landscape;
                margin-right: 1cm;
                /* margin-top: -0.1cm; */
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
            {{-- text-align: center; --}}
            font-size : 15px;
            line-height: 16px;
        }
        .n{
            font-size : 15px;
            line-height: 16px;
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
        body{
            font-family: sans-serif !important;
            line-height: 16px;
            font-size: 15px;
            /* font-weight: bold; */
            color: #000;
        }
        .almt {
            vertical-align: text-top;
            font-size : 12px;
        }
        .heading {
            text-align: center;
            padding-bottom: 3px;
        }
    </style>
    @endsection
    <div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
        <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
        <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
    </div>
    <div id="print-kan">
        <div class="container">
            <table width="100%" style="background-color: rgb(147, 247, 147)">
                <tr>
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
                    <td class="heading" style="font-size: 16px; margin-top: 15px;"><strong>{{ strtoupper($perusahaan->nm_perush) }}</strong></td>
                </tr>
                <tr><td class="heading">{{ $perusahaan->alamat }},{{ $perusahaan->kotakab }}-{{ $perusahaan->provinsi }}</td></tr>
                <tr><td class="heading">Telp. {{ $perusahaan->telp }} (Hunting), Fax. {{ $perusahaan->fax }}</td></tr>
            </table>

        </div>
        <br>
        <div class="container">
            <table width="100%" style="border-collapse: collapse;">
                <tr>
                    <td class="n" colspan="2" width="35%"><b>No AWB</b></td>
                    <td class="n">@if(isset($data->no_awb)){{ $data->no_awb }}@endif</td>
                    <td class="t" width="25%" colspan="2" style="border: 1px solid black;">TANGGAL IN</td>
                    <td class="t" width="25%" colspan="2" style="border: 1px solid black;">@if(isset($data->tgl_masuk)){{ daydate($data->tgl_masuk).", ".dateindo($data->tgl_masuk) }}@endif</td>
                </tr>
                <tr>
                    <td class="n" colspan="2"><b>SURAT TANDA TERIMA TITIPAN No.</b></td>
                    <td class="n">@if(isset($data->kode_stt)){{ $data->kode_stt }}@endif</td>
                    <td class="t" colspan="2" >TANGGAL OUT</td>
                    <td class="t" colspan="2" >@if(isset($data->tgl_keluar)){{ daydate($data->tgl_keluar).", ".dateindo($data->tgl_keluar) }}@endif</td>
                </tr>
                <tr style="border-top: 1px solid black; border-left: 1px solid black;">
                    <td class="n" width="10%" >Kepada</td>
                    <td class="n" colspan="2">: &nbsp {{ $data->penerima_nm }}</td>
                    <td colspan="4" class="t text-center">NAMA BARANG</td>
                </tr>
                <tr style="border-left: 1px solid black;" height="50px">
                    <td class="almt">Alamat</td>
                    <td class="n almt" colspan="2" class="almt">: &nbsp {{ strtoupper($data->penerima_alm) }} - {{ $data->penerima_kodepos }}, @if(isset($data->tujuan->nama_wil)){{ $data->tujuan->nama_wil }}@endif</td>
                    <td colspan="4" rowspan="2" class="t text-center" style="border: 1px solid black;padding-top:10px; font-size:20px;"><b>@if(isset($data->tipekirim->nm_tipe_kirim)){{ $data->tipekirim->nm_tipe_kirim }}
                        @endif</b></td>
                    </tr>
                    <tr style="border-left: 1px solid black;">
                        <td class="n" >No Telpn</td>
                        <td class="n" colspan="2">: &nbsp {{ $data->penerima_telp }}</td>
                    </tr>
                    <tr style="border-top: 1px solid black; border-left: 1px solid black;">
                        <td class="n" width="10%">Pengirim</td>
                        <td class="n" colspan="2">: &nbsp {{ $data->pengirim_nm }}</td>
                        <td class="t text-center" colspan="2" style="border: 1px solid black;">CARA KEMAS</td>
                        <td class="t text-center" colspan="2" style="border: 1px solid black;">KETARANGAN</td>
                    </tr>
                    <tr style="border-left: 1px solid black;" height="50px">
                        <td class="almt">Alamat</td>
                        <td colspan="2" class="n almt">: &nbsp {{ strtoupper($data->pengirim_alm) }} - {{ $data->pengirim_kodepos }}, @if(isset($data->asal->nama_wil)){{ $data->asal->nama_wil }} @endif</td>
                        <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px"><b>@if(isset($data->cara_kemas)){{ $data->cara_kemas }}@endif</b></td>
                        <td colspan="2" rowspan="2" class="text-center" style="border: 1px solid black; padding-top:10px"><b>@if(isset($data->info_kirim)){{ $data->info_kirim }}@endif</b></td>
                    </tr>
                    <tr style="border-left: 1px solid black;">
                        <td class="n" >No Telpn</td>
                        <td class="n" colspan="2">: &nbsp {{ $data->pengirim_telp }}</td>
                    </tr>
                    <tr>
                        <td width="10%" class="t text-center">Collie</td>
                        <td colspan="2" class="t text-center">Perincian Koli</td>
                        <td class="t text-center">ANGKA</td>
                        <td class="t text-center">SATUAN</td>
                        <td class="t text-center">Tarif</td>
                        <td class="t text-center">Total</td>
                    </tr>
                    <tr height="50px">
                        <td width="10%" class="t text-center">
                            @isset($data->n_koli)
                            {{$data->n_koli}}
                            @endisset
                        </td>
                        <td colspan="2" class="t" style="border: 1px solid black;">
                            @foreach($detail as $key => $value)
                            ({{ $value->ket_koli }} {{ $value->keterangan }}) ,
                            @endforeach
                        </td>
                        <td class="t text-center" style="border: 1px solid black;">
                            @if(isset($data->c_tarif))
                            @if($data->c_tarif == 1)
                            {{ $data->n_berat }}
                            @elseif($data->c_tarif == 2)
                            {{ $data->n_volume }}
                            @elseif($data->c_tarif == 4)
                            {{ $data->n_kubik }}
                            @endif
                            @endif
                        </td>
                        <td class="t text-center" style="border: 1px solid black;">@if(isset($data->c_tarif))
                            @if($data->c_tarif == 1)
                            Kg
                            @elseif($data->c_tarif == 2)
                            KgV
                            @elseif($data->c_tarif == 4)
                            M3
                            @endif
                            @endif</td>
                            <td class="t text-center" style="border: 1px solid black;">
                                @if (Request::segment(3) == "cetak_pdf")
                                @if(isset($data->c_tarif))
                                @if($data->c_tarif == 1)
                                {{ $data->n_tarif_brt }}
                                @elseif($data->c_tarif == 2)
                                {{ $data->n_tarif_vol }}
                                @elseif($data->c_tarif == 4)
                                {{ $data->n_tarif_kubik }}
                                @endif
                                @endif
                                @else
                                0
                                @endif
                            </td>
                            <td class="t text-center" style="border: 1px solid black;">
                                <p class="text-center">
                                    @if (Request::segment(3) == "cetak_pdf")
                                    @if(isset($data->c_total)){{ "Rp. ".number_format($data->c_total, 2, ',', '.') }}@endif
                                    @else
                                    0
                                    @endif

                                </p>
                            </td>
                        </tr>
                    </table>
                    <br>

                    <table width="100%">
                        <tr style="height:70px">
                            <td rowspan="2">
                                @php
                                $text = "http://lsj-express.id/cekresi/".$id;
                                @endphp
                                <img src="https://chart.googleapis.com/chart?chs=110x110&cht=qr&chl={{$text}}%2F&choe=UTF-8" title="Cek Resi Barang" />
                            </td>
                            <td>Pengirim</td>
                            <td>Penerima</td>
                            <td>Perush</td>
                        </tr>
                        <tr>
                            <td>{{ $data->penerima_nm }}</td>
                            <td>{{ $data->pengirim_nm }}</td>
                            <td>{{ Auth::user()->nm_user }}</td>
                        </tr>
                    </table>


                    <br>



                </div>
                <br>

            </div>

            <textarea id="printing-css" style="display:none;">
                @media print{
                    @page
                    {
                        /* size: 5.5in 9.4in ; */
                        /* size: portrait; */
                        size: landscape;
                        margin-right: 1cm;
                        /* margin-top: -0.1cm; */
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
                    {{-- text-align: center; --}}
                    font-size : 15px;
                    line-height: 16px;
                }
                .n{
                    font-size : 15px;
                    line-height: 16px;
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
                body{
                    font-family: sans-serif !important;
                    line-height: 16px;
                    font-size: 15px;
                    /* font-weight: bold; */
                    color: #000;
                }
                .almt {
                    vertical-align: text-top;
                    font-size : 12px;
                }
                .heading {
                    text-align: center;
                    padding-bottom: 3px;
                }
            </textarea>
            <iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>

            <script>
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
