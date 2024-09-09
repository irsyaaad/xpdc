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
    .atas{
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        line-height: 10px;
    }

</style>
@endsection
<div class="col-md-12 text-right" id="tombol" style="margin-top:10px">
    <a href="{{ url(Request::segment(1)) }}" class="btn btn-sm btn-warning"><i class="fa fa-reply"></i> Kembali</a>
    <a href="javascript:printDiv('print-kan');" class="btn btn-sm btn-success"><i class="fa fa-print"> Cetak</i></a>
</div>
<div id="print-kan">
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
        <div class="atas">
            <p>
                Laporan Pemasukan {{ strtoupper($perusahaan->nm_perush) }}
            </p>
            <p>
                Periode ( {{dateindo($filter['dr_tgl'])}} s/d {{dateindo($filter['sp_tgl'])}} )
            </p>
        </div>
        <br>
        <table width="100%" class="t">
            <thead>
                <th id="n">No</th>
                <th id="n">No. Transaksi</th>
                <th id="n">Tanggal Masuk</th>
                <th id="n">Perkiraan Akun</th>
                <th id="n">Terima</th>
                <th id="n">Keterangan</th>
                <th id="n">Nominal</th>
                <th id="n">Admin</th>
            </thead>
            <tbody>
                @php
                $total = 0;
                @endphp
                @foreach($data as $key => $value)
                <tr>
                    <td class="t">{{$key+1}}</td>
                    <td class="t">{{ strtoupper($value->kode_pendapatan) }}</td>
                    <td class="t">{{ dateindo($value->tgl_masuk) }}</td>
                    <td class="t">@if(isset($value->debet->nama))
                        {{ strtoupper(" ( ".$value->debet->id_ac." ) ".$value->debet->nama) }}
                        @endif
                    </td>
                    <td class="t">{{ strtoupper($value->terima_dr) }}</td>
                    <td class="t">{{ $value->info }}</td>
                    <td class="t">Rp. @if(isset($value->c_total)){{ number_format($value->c_total, 0, ',', '.') }}
                        @php
                        $total+=$value->c_total;
                        @endphp
                        @else {{ number_format("0", 2, ',', '.') }}@endif,-
                    </td>
                    <td>@if(isset($value->user->nm_user))
                        {{ strtoupper($value->user->nm_user) }}
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" class="t text-center">Total</td>
                    <td colspan="2" class="t text-center">Rp. {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table width="100%">
            <tr class="text-center">
                <td width="25%">Direktur</td>
                <td width="25%">Manager Keuangan</td>
                <td width="25%">Kasir</td>
                <td width="25%">Penyetor</td>
            </tr>
            <tr class="text-center">
                <td width="25%"><br><br><br>{{ $perusahaan->nm_dir }}</td>
                <td width="25%"><br><br><br>{{ $perusahaan->nm_keu }}</td>
                <td width="25%"><br><br><br>@if(isset($user->karyawan->nm_karyawan)){{ $user->karyawan->nm_karyawan }}@endif</td>
                <td width="25%"><br><br><br>@if(isset($perusahaan->nm_perush)){{ $perusahaan->nm_perush }}@endif</td>
            </tr>
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
            font-size : 10px;
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
            font-size : 11px;
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
        .atas{
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            line-height: 2px;
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
