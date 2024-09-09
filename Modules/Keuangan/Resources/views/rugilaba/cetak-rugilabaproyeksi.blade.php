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
        font-size: 11px;
        /* font-weight: bold; */
        color: #000;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
    }
    th ,td {
        font-size : 11px;
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
    <div class="atas">
        <p class="text-center"><b>LAPORAN RUGILABA {{ strtoupper($perusahaan->nm_perush) }}</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
        @php
        $total_pendapatan = 0;
        $total_rugilaba   = 0;

        $total_pendapatan_s = 0;
        $total_rugilaba_s   = 0;

        $total_pendapatan_p = 0;
        $total_rugilaba_p   = 0;

        $total_pencapaian   = 0;
        $total_rugilaba_pen = 0;

        $total_pertumbuhan  = 0;
        $total_rugilaba_per = 0;

        @endphp
        <table width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th colspan="2"  class="text-center">Realisasi Sebelum</th>
                    <th colspan="2"  class="text-center">Proyeksi</th>
                    <th colspan="2"  class="text-center">Realisasi</th>
                    <th colspan="2"  class="text-center">Rasio</th>
                </tr>
                <tr>
                    <th></th>
                    <th colspan="2">
                        @php
                            $dr_tgl = date('Y-m-d', strtotime('-1 year', strtotime( $filter['dr_tgl'] )));
                            $sp_tgl = date('Y-m-d', strtotime('-1 year', strtotime( $filter['sp_tgl'] )));
                            echo dateindo($dr_tgl)." s/d ".dateindo($sp_tgl);
                        @endphp
                    </th>

                    <th colspan="4" class="text-center">{{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</th>
                    <th>Pencapaian</th>
                    <th>Pertumbuhan</th>
                </tr>
                <tr>
                    <th></th>
                    <th class="text-center">A</th>
                    <th class="text-center">%</th>
                    <th class="text-center">B</th>
                    <th class="text-center">%</th>
                    <th class="text-center">C</th>
                    <th class="text-center">%</th>
                    <th class="text-center">C - B</th>
                    <th class="text-center">C - A</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data1 as $key => $value)
                    @if($value->id_ac == 4)
                        <tr><td>{{$value->nama}}</td></tr>
                        @if(isset($data2[$value->id_ac]))
                            @foreach($data2[$value->id_ac] as $key2 => $value2)
                                @if(isset($data3[$value2->id_ac]))
                                    @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px">{{$value3->nama}}</td>

                                                {{-- Saldo sebelum --}}
                                            @if(isset($sebelum[$value3->id_ac]))
                                                <td><a href="{{ route('showrugilaba', [
                                                    'id_ac' => $value3->id_ac,
                                                    'dr_tgl' => $dr_tgl,
                                                    'sp_tgl' => $sp_tgl,
                                                    ]) }}" style="color:black;">Rp. {{ number_format($sebelum[$value3->id_ac], 0, ',', '.') }}</a></td>
                                                    <td>%</td>
                                                @php
                                                    if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                        $total_pendapatan_s-=$sebelum[$value3->id_ac];
                                                    }else{
                                                        $total_pendapatan_s+=$sebelum[$value3->id_ac];
                                                    }

                                                @endphp
                                            @endif

                                            {{-- Proyeksi --}}
                                            @if(isset($proyeksi[$value3->id_ac]))
                                                <td>Rp. {{ number_format($proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                                <td>%</td>
                                                @php
                                                    if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                        $total_pendapatan_p-=$proyeksi[$value3->id_ac];
                                                    }else{
                                                        $total_pendapatan_p+=$proyeksi[$value3->id_ac];
                                                    }

                                                @endphp
                                            @endif

                                            {{-- Realisasi --}}
                                            @if(isset($nilai[$value3->id_ac]))
                                                <td><a href="{{ route('showrugilaba', [
                                                    'id_ac' => $value3->id_ac,
                                                    'dr_tgl' => $filter['dr_tgl'],
                                                    'sp_tgl' => $filter['sp_tgl'],
                                                    ]) }}" style="color:black;">Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</a></td>
                                                <td>{{ round(($nilai[$value3->id_ac]/($total_omset)) * 100,2) }} %</td>
                                                @php
                                                    if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                        $total_pendapatan-=$nilai[$value3->id_ac];
                                                    }else{
                                                        $total_pendapatan+=$nilai[$value3->id_ac];
                                                    }

                                                @endphp
                                            @endif

                                            {{-- Pencapaian --}}

                                            @if(isset($nilai[$value3->id_ac]) and isset($proyeksi[$value3->id_ac]))
                                                <td>Rp. {{ number_format($nilai[$value3->id_ac]-$proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                        $total_pencapaian-=($nilai[$value3->id_ac]-$proyeksi[$value3->id_ac]);
                                                    }else{
                                                        $total_pencapaian+=($nilai[$value3->id_ac]-$proyeksi[$value3->id_ac]);
                                                    }

                                                @endphp
                                            @else
                                                <td>0</td>
                                            @endif

                                            {{-- Pertumbuhan --}}

                                            @if(isset($nilai[$value3->id_ac]) and isset($sebelum[$value3->id_ac]))
                                                <td>Rp. {{ number_format($nilai[$value3->id_ac]-$sebelum[$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    if($value3->id_ac == 411 or $value3->id_ac == 412){
                                                        $total_pertumbuhan-=($nilai[$value3->id_ac]-$sebelum[$value3->id_ac]);
                                                    }else{
                                                        $total_pertumbuhan+=($nilai[$value3->id_ac]-$sebelum[$value3->id_ac]);
                                                    }

                                                @endphp
                                            @else
                                                <td>0</td>
                                            @endif


                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            <tr>

                                <td class="text-center">Sub Total PENDAPATAN</td>
                                <td>Rp. {{ number_format($total_pendapatan_s, 0, ',', '.') }}</td>
                                <td>%</td>
                                <td>Rp. {{ number_format($total_pendapatan_p, 0, ',', '.') }}</td>
                                <td>%</td>
                                <td>Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                <td>{{ round(($total_pendapatan/($total_omset)) * 100,2) }} %</td>
                                <td>Rp. {{ number_format($total_pencapaian, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($total_pertumbuhan, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                    @endif
                @endforeach

                @foreach($data1 as $key => $value)
                    @if($value->id_ac == 5)
                        <tr><td>{{$value->nama}}</td></tr>
                        @if(isset($data2[$value->id_ac]))
                            @foreach($data2[$value->id_ac] as $key2 => $value2)
                                @php
                                $total_sebelum      = 0;
                                $total_proyeksi     = 0;
                                $total              = 0;
                                $total_pen          = 0;
                                $total_per          = 0;
                                @endphp
                                <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                                @if(isset($data3[$value2->id_ac]))
                                    @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                        <tr>
                                            <td style="padding-left:50px"><a href="{{ route('showrugilaba', [
                                                'id_ac' => $value3->id_ac,
                                                'dr_tgl' => $filter['dr_tgl'],
                                                'sp_tgl' => $filter['sp_tgl'],
                                                ]) }}" style="color:black;">{{$value3->nama}}</a></td>

                                                {{-- Realisasi sebelum --}}

                                            @if(isset($sebelum[$value3->id_ac]))
                                                <td><a href="{{ route('showrugilaba', [
                                                    'id_ac' => $value3->id_ac,
                                                    'dr_tgl' => $dr_tgl,
                                                    'sp_tgl' => $sp_tgl,
                                                    ]) }}" style="color:black;">Rp. {{ number_format($sebelum[$value3->id_ac], 0, ',', '.') }}</a></td>
                                                    <td>%</td>
                                                @php
                                                    $total_sebelum+=$sebelum[$value3->id_ac];
                                                @endphp
                                            @endif

                                            {{-- Proyeksi --}}

                                            @if(isset($proyeksi[$value3->id_ac]))
                                                <td>Rp. {{ number_format($proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                                <td>%</td>
                                                @php
                                                    $total_proyeksi+=$proyeksi[$value3->id_ac];
                                                @endphp
                                            @endif

                                            {{-- Realisasi --}}

                                            @if(isset($nilai[$value3->id_ac]))
                                                <td><a href="{{ route('showrugilaba', [
                                                    'id_ac' => $value3->id_ac,
                                                    'dr_tgl' => $filter['dr_tgl'],
                                                    'sp_tgl' => $filter['sp_tgl'],
                                                    ]) }}" style="color:black;">Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</a></td>
                                                    <td>{{ round(($nilai[$value3->id_ac]/($total_omset)) * 100,2) }} %</td>
                                                @php
                                                    $total+=$nilai[$value3->id_ac];
                                                @endphp
                                            @endif

                                            {{-- Pencapaian --}}
                                            @if(isset($nilai[$value3->id_ac]) and isset($proyeksi[$value3->id_ac]))
                                                <td>Rp. {{ number_format($nilai[$value3->id_ac]-$proyeksi[$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    $total_pen+=($nilai[$value3->id_ac]-$proyeksi[$value3->id_ac]);
                                                @endphp
                                            @endif

                                            {{-- Pertumbuhan --}}
                                            @if(isset($nilai[$value3->id_ac]) and isset($sebelum[$value3->id_ac]))
                                                <td>Rp. {{ number_format($nilai[$value3->id_ac]-$sebelum[$value3->id_ac], 0, ',', '.') }}</td>
                                                @php
                                                    $total_per+=($nilai[$value3->id_ac]-$sebelum[$value3->id_ac]);
                                                @endphp
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                <td class="text-center">Sub Total {{$value2->nama}}</td>
                                <td>Rp. {{ number_format($total_sebelum, 0, ',', '.') }}</td>
                                <td>%</td>
                                <td>Rp. {{ number_format($total_proyeksi, 0, ',', '.') }}</td>
                                <td>%</td>
                                <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                                <td>{{ round(($total/($total_omset)) * 100,2) }} %</td>
                                <td>Rp. {{ number_format($total_pen, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($total_per, 0, ',', '.') }}</td>
                                @php
                                    $total_pendapatan-=$total;
                                    $total_pendapatan_s-=$total_sebelum;
                                    $total_pendapatan_p-=$total_proyeksi;
                                    $total_pencapaian-=$total_pen;
                                    $total_pertumbuhan-=$total_per;
                                @endphp
                                </tr>
                                <tr>
                                <td class="text-center">Sub Total</td>
                                <td>Rp. {{ number_format($total_pendapatan_s, 0, ',', '.') }}</td>
                                <td>%</td>
                                <td>Rp. {{ number_format($total_pendapatan_p, 0, ',', '.') }}</td>
                                <td>%</td>
                                <td>Rp. {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                <td>{{ round(($total_pendapatan/($total_omset)) * 100,2) }} %</td>
                                <td>Rp. {{ number_format($total_pencapaian, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($total_pertumbuhan, 0, ',', '.') }}</td>

                                </tr>
                            @endforeach

                        @endif
                    @endif
                @endforeach
            </tbody>
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
        font-size: 11px;
        /* font-weight: bold; */
        color: #000;
    }
    .t {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size : 12px;
    }
    th ,td {
        font-size : 11px;
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
