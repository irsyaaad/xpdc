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
    <div class="atas">
        <p class="text-center"><b>LAPORAN NERACA {{ strtoupper($perusahaan->nm_perush) }}</b></p>
        <p class="text-center"><b>Periode : {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}}</b></p>
    </div>
    <div class="container">
        @php $total_aktiva = 0; @endphp
        <table width="100%">
            <tr>
                <td>
                    <table width="100%">
                        @foreach($data1 as $key => $value)
                            @if($value->id_ac == 1)
                                <tr><td>{{$value->nama}}</td></tr>
                                    @if(isset($data2[$value->id_ac]))
                                        @foreach($data2[$value->id_ac] as $key2 => $value2)
                                            <tr>
                                                <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                                                    @if(isset($data3[$value2->id_ac]))
                                                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                            <tr>
                                                                <td style="padding-left:50px">{{$value3->nama}}</td>

                                                                {{-- <td style="padding-left:50px"><a href="{{ url(Request::segment(1)."/".$value3->id_ac."/show") }}" style="color:black;">{{$value3->nama}}</a></td> --}}
                                                                @if(isset($nilai[$value3->id_ac]))
                                                                    <td>Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                                    @php
                                                                        $total_aktiva+=$nilai[$value3->id_ac];
                                                                    @endphp
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endif
                            @endif
                        @endforeach
                    </table>
                </td>
                <td>
                    @php $total_pasiva = 0;  @endphp
                    <table width="100%">
                        @foreach($data1 as $key => $value)
                            @if($value->id_ac == 2)
                                <tr><td>{{$value->nama}}</td></tr>
                                    @if(isset($data2[$value->id_ac]))
                                        @foreach($data2[$value->id_ac] as $key2 => $value2)
                                            <tr>
                                                <td style="padding-left:10px"><p>{{$value2->nama}}</p></td>
                                                    @if(isset($data3[$value2->id_ac]))
                                                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                            <tr>
                                                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                                                @if(isset($nilai[$value3->id_ac]))
                                                                    <td>Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                                    @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                            @endif
                        @endforeach
                        @foreach($data1 as $key => $value)
                            @if($value->id_ac == 3)
                                <tr><td>{{$value->nama}}</td></tr>
                                    @if(isset($data2[$value->id_ac]))
                                        @foreach($data2[$value->id_ac] as $key2 => $value2)
                                            <tr>
                                                    @if(isset($data3[$value2->id_ac]))
                                                        @foreach($data3[$value2->id_ac] as $key3 => $value3)
                                                            <tr>
                                                                <td style="padding-left:50px">{{$value3->nama}}</td>
                                                                @if(isset($lababerjalan) and $value3->id_ac == 321)
                                                                    <td>Rp. {{ number_format($lababerjalan, 0, ',', '.') }}</td>
                                                                    @php $total_pasiva+=$lababerjalan @endphp
                                                                @elseif(isset($nilai[$value3->id_ac]))
                                                                    <td>Rp. {{ number_format($nilai[$value3->id_ac], 0, ',', '.') }}</td>
                                                                    @php $total_pasiva+=$nilai[$value3->id_ac] @endphp
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                            @endif
                        @endforeach
                    </table>
                </td>
            </tr>
            {{-- total --}}
            <tr>
                <td colspan="2">
                    <hr class="hr">
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td>Total Aktiva</td>
                            <td style="text-align:center">Rp. {{ number_format($total_aktiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table width="100%">
                        <tr>
                            <td>Total Pasiva</td>
                            <td style="text-align:center">Rp. {{ number_format($total_pasiva, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
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
