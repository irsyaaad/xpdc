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
    td {
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
        border: 0px
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
    .atas{
        line-height : 5px;
        margin-top : 20px;
    }

</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url($filter["back"]) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">
    <div class="container" style=" margin-top:10px;">
        <table width="100%">
            <tr>
                <td rowspan="3" width="20%" class="heading">
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
    <div class="container" style="margin-top:20px">
        <table width="100%" class="t">
            <thead>
                <th>No</th>
                <th width="10%">Tanggal</th>
                <th>No. Detail</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Total</th>
            </thead>
            <tbody>
                @foreach($ac as $key => $value)
                @if($value->id_ac < 4000)
                @if(isset($data[$value->id_ac]))
                <tr>
                    <td colspan="7"><b>{{strtoupper($value->nama)}}</b></td>
                </tr>
                @php $total=0; @endphp
                @foreach($data[$value->id_ac] as $key2 => $value2)

                <tr>
                    @if($value2->id_debet ==  $value->id_ac)
                    <td></td>
                    <td>@if(isset($value2->tgl_masuk)){{dateindo($value2->tgl_masuk)}}@endif</td>
                    <td>@if(isset($value2->id_detail)){{$value2->id_detail}}@endif</td>
                    <td>@if(isset($value2->info_debet)){{$value2->info_debet}}@endif</td>
                    <td>@if(isset($value2->total_debet))Rp. {{ number_format($value2->total_debet, 0, ',', '.') }} @endif</td>
                    <td>0</td>
                    @php
                    if($value2->pos_d == "D"){
                        $total+=$value2->total_debet;
                    }else{
                        $total-=$value2->total_kredit;
                    }
                    @endphp
                    @else
                    <td></td>
                    <td>@if(isset($value2->tgl_masuk)){{dateindo($value2->tgl_masuk)}}@endif</td>
                    <td>@if(isset($value2->id_detail)){{$value2->id_detail}}@endif</td>
                    <td>@if(isset($value2->info_kredit)){{$value2->info_kredit}}@endif</td>
                    <td>0</td>
                    <td>@if(isset($value2->total_kredit))Rp. {{ number_format($value2->total_kredit, 0, ',', '.') }} @endif</td>
                    @php
                    if($value2->pos_k == "K"){
                        $total+=$value2->total_kredit;
                    }else{
                        $total-=$value2->total_debet;
                    }
                    @endphp
                    @endif

                    <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
                <!-- <tr>
                    <td colspan="6" class="text-right">TOTAL</td>
                    <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                </tr>                 -->
                @endforeach
                @endif
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <br>



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
            font-size : 14px;
        }
        td {
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
            border: 0px
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
        .atas{
            line-height : 5px;
            margin-top : 20px;
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
